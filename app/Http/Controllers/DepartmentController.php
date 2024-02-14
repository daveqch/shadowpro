<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:department-read|department-create|department-update|department-delete', ['only' => ['index']]);
        $this->middleware('permission:department-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:department-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:department-delete', ['only' => ['destroy']]);
        $this->middleware('permission:department-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $departments = $this->filter($request)->paginate(10)->withQueryString();
        return view('department.index', compact('departments'));
    }

    private function filter(Request $request)
    {
        $query = Department::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('department_name', 'like', '%' . $request->name . '%');

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'department_name' => 'required|string|' . Rule::unique('departments', 'department_name')->where('company_id', Session::get('company_id')),
            'enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            Department::create([
                'company_id' => Session::get('company_id'),
                'department_name' => $request->department_name,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('department.index')->with('success', trans('Create Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('department.index')->with('error', trans('Not Create Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $this->validate($request, [
            'department_name' => ['required', 'string', Rule::unique('departments', 'department_name')->where('company_id', Session::get('company_id'))->ignore($department->id)],
            'enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $department->update([
                'company_id' => Session::get('company_id'),
                'department_name' => $request->department_name,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('department.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('department.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('department.index')->with('success', trans('Deleted Successfully'));
    }
}
