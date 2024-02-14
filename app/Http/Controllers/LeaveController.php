<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LeaveController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:leave-read|leave-create|leave-update|leave-delete', ['only' => ['index']]);
        $this->middleware('permission:leave-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:leave-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:leave-delete', ['only' => ['destroy']]);
        $this->middleware('permission:leave-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $leaves = $this->filter($request)->paginate(10)->withQueryString();
        return view('leave.index', compact('leaves'));
    }

    private function filter(Request $request)
    {
        $query = Leave::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('type', 'like', '%' . $request->name . '%');

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string',
            'days' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            Leave::create([
                'company_id' => Session::get('company_id'),
                'parent_id' => Auth::user()->id,
                'type' => $request->type,
                'days' => $request->days,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('leave.index')->with('success', trans('Create Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('leave.index')->with('error', trans('Not Create Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        return view('leave.edit', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $this->validate($request, [
            'type' => 'required|string',
            'days' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $leave->update([
                'company_id' => Session::get('company_id'),
                'parent_id' => Auth::user()->id,
                'type' => $request->type,
                'days' => $request->days,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('leave.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('leave.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leave.index')->with('success', trans('Deleted Successfully'));
    }
}
