<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PolicyController extends Controller
{
    /**
     * load constructor method 
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:policy-read|policy-create|policy-update|policy-delete', ['only' => ['index']]);
        $this->middleware('permission:policy-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:policy-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:policy-delete', ['only' => ['destroy']]);
        $this->middleware('permission:policy-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $policies = $this->filter($request)->paginate(10)->withQueryString();
        return view('policy.index', compact('policies'));
    }

    private function filter(Request $request)
    {
        $query = Policy::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('title', 'like', '%' . $request->name . '%');

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('policy.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Policy::create([
                'company_id' => Session::get('company_id'),
                'title' => $request->title,
                'description' => $request->description,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('policy.index')->with('success', trans('Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('policy.index')->with('error', trans('Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Policy $policy)
    {
        return view('policy.details', compact('policy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Policy $policy)
    {
        return view('policy.edit', compact('policy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Policy $policy)
    {
        $this->validate($request, [
            'title' => 'required',
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $policy->update([
                'company_id' => Session::get('company_id'),
                'title' => $request->title,
                'description' => $request->description,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('policy.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('policy.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Policy $policy)
    {
        $policy->delete();
        return redirect()->route('notice.index')->with('success', trans('Deleted Successfully'));
    }
}
