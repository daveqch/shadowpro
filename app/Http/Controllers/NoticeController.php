<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Branch;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NoticeController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:notice-read|notice-create|notice-update|notice-delete', ['only' => ['index']]);
        $this->middleware('permission:notice-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:notice-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:notice-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:notice-show', ['only' => ['show']]);
        $this->middleware('permission:notice-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notices = $this->filter($request)->paginate(10)->withQueryString();
        return view('notice.index', compact('notices'));
    }

    private function filter(Request $request)
    {
        $query = Notice::where('company_id', session('company_id'))->latest();

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
        $companyId = Session::get('company_id');
        if (empty($companyId)) abort(500, 'Something went wrong');

        $branches = Branch::select('id', 'branch_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        return view('notice.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Notice::create([
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id ?: null,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('notice.index')->with('success', trans('Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('notice.index')->with('error', trans('Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Notice $notice)
    {
        return view('notice.details', compact('notice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notice $notice)
    {
        $companyId = Session::get('company_id');
        if (empty($companyId)) abort(500, 'Something went wrong');

        $branches = Branch::select('id', 'branch_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        return view('notice.edit', compact('branches', 'notice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'enabled' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $notice->update([
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id ?: null,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('notice.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('notice.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notice.index')->with('success', trans('Deleted Successfully'));
    }
}
