<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class DesignationController extends Controller
{

    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:designation-read|designation-create|designation-update|designation-delete', ['only' => ['index']]);
        $this->middleware('permission:designation-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:designation-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:designation-delete', ['only' => ['destroy']]);
        $this->middleware('permission:designation-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $designations = $this->filter($request)->paginate(10)->withQueryString();
        return view('designation.index', compact('designations'));
    }

    private function filter(Request $request)
    {
        $query = Designation::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('designation_name', 'like', '%' . $request->name . '%');

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('designation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'designation_name' => 'required|string|' .  Rule::unique('designations', 'designation_name')->where('company_id', Session::get('company_id')),
            'enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            Designation::create([
                'company_id' => Session::get('company_id'),
                'designation_name' => $request->designation_name,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('designation.index')->with('success', trans('Create Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('designation.index')->with('error', trans('Not Create Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        return view('designation.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $this->validate($request, [
            'designation_name' => 'required|string|' . Rule::unique('designations', 'designation_name')->where('company_id', Session::get('company_id'))->ignore($designation->id),
            'enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $designation->update([
                'company_id' => Session::get('company_id'),
                'designation_name' => $request->designation_name,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('designation.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('designation.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designation.index')->with('success', trans('Deleted Successfully'));
    }
}
