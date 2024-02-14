<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Branch;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:branch-read|branch-create|branch-update|branch-delete', ['only' => ['index']]);
        $this->middleware('permission:branch-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:branch-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:branch-delete', ['only' => ['destroy']]);
        //$this->middleware('permission:branch-show', ['only' => ['show']]);
        $this->middleware('permission:branch-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $branches = $this->filter($request)->paginate(10)->withQueryString();
        $locations = Location::select('id', 'location_name')->get();
        return view('branch.index', compact('branches', 'locations'));
    }

    private function filter(Request $request)
    {
        $query = Branch::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('branch_name', 'like', '%' . $request->name . '%');

        if ($request->location_id)
            $query->where('location_id', $request->location_id);

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companyId = Session::get('company_id');
        if (empty($companyId)) abort(500, 'Something went wrong');

        $locations = Location::select('id', 'location_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        return view('branch.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'location_id' => 'required',
            'branch_name' => 'required|' . Rule::unique('branches', 'branch_name')->where('company_id', Session::get('company_id')),
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Branch::create([
                'company_id' => Session::get('company_id'),
                'location_id' => $request->location_id,
                'branch_name' => $request->branch_name,
                'note' => $request->note,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('branch.index')->with('success', trans('Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('branch.index')->with('error', trans('Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return view('branch.details', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $companyId = Session::get('company_id');
        if (empty($companyId)) abort(500, 'Something went wrong');

        $locations = Location::select('id', 'location_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        return view('branch.edit', compact('branch', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {

        $this->validate($request, [
            'location_id' => 'required',
            'branch_name' => 'required|' . Rule::unique('branches', 'branch_name')->where('company_id', Session::get('company_id'))->ignore($branch->id),
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $branch->update([
                'company_id' => Session::get('company_id'),
                'location_id' => $request->location_id,
                'branch_name' => $request->branch_name,
                'note' => $request->note,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('branch.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('branch.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branch.index')->with('success', trans('Deleted Successfully'));
    }
}
