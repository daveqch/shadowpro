<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Loan;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\LoansPay;
use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoanController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:loan-read|loan-create|loan-update|loan-delete', ['only' => ['index']]);
        $this->middleware('permission:loan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:loan-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:loan-delete', ['only' => ['destroy']]);
        $this->middleware('permission:loan-approve', ['only' => ['loanStatus']]);
        $this->middleware('permission:loan-receive', ['only' => ['loanReceiveList', 'adjustmentLoan', 'receiveLoanAction']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $loans = $this->filter($request)->paginate(10)->withQueryString();
        $branches = Branch::select('id', 'branch_name')->orderBy('id', 'DESC')->where('company_id', session('company_id'))->get();
        return view('loan.index', compact('loans', 'branches'));
    }

    private function filter(Request $request)
    {
        $query = Loan::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%' . $request->name . '%');
        if ($request->branch_id)
            $query->Where('branch_id', $request->branch_id);

        if ($request->loan_status > -1)
            $query->where('loan_status', $request->loan_status);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::select('id', 'branch_name')->where('enabled', 1)->get();
        return view('loan.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'branch_id' => 'required',
            'employee_id' => 'required',
            'interest' => 'required',
            'loan_amount' => 'required',
            'loan_installment' => 'required',
            'loan_date' => 'required',
            'from_date' => 'required',
        ]);
        $interestP = $request->interest;
        $interest_amount = (($request->loan_amount * $request->interest) / 100);
        $loanInstallmentAmount = ($request->loan_amount + $interest_amount) / $request->loan_installment;
        $userId = Auth::user()->id;
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $loan = Loan::create([
                'parent_id' => $userId,
                'name' => $request->name,
                'receive_loan' => $request->receive_loan,
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id,
                'employee_id' => $request->employee_id,
                'interest' => $interestP,
                'loan_amount' => $request->loan_amount,
                'loan_installment' => $request->loan_installment,
                'loan_installment_amount' => $loanInstallmentAmount,
                'loan_date' => $request->loan_date,
                'from_date' => $request->from_date,
                'note' => $request->note,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('loan.index')->with('success', trans('Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('loan.index')->with('error', trans('Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::with(['employee:id,name', 'branch:id,branch_name'])->find($id);
        return view('loan.details', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $loan = Loan::with('employee:id,name,e_id')->find($id);
        $branches = Branch::select('id', 'branch_name')->where('enabled', 1)->get();
        $employee = Employee::select('id', 'name')->where('branch_id', $loan->branch_id)->get();
        return view('loan.edit', compact('branches', 'loan', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $this->validate($request, [
            'name' => 'required',
            'branch_id' => 'required',
            'employee_id' => 'required',
            'interest' => 'required',
            'loan_amount' => 'required',
            'loan_installment' => 'required',
            'loan_date' => 'required',
            'from_date' => 'required',
        ]);
        $interestP = $request->interest;
        $interest_amount = (($request->loan_amount * $request->interest) / 100);
        $loanInstallmentAmount = ($request->loan_amount + $interest_amount) / $request->loan_installment;
        $userId = Auth::user()->id;
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $loan = $loan->update([
                'parent_id' => $userId,
                'name' => $request->name,
                'receive_loan' => $request->receive_loan,
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id,
                'employee_id' => $request->employee_id,
                'interest' => $interestP,
                'loan_amount' => $request->loan_amount,
                'loan_installment' => $request->loan_installment,
                'loan_installment_amount' => $loanInstallmentAmount,
                'loan_date' => $request->loan_date,
                'from_date' => $request->from_date,
                'note' => $request->note,
                'enabled' => $request->enabled
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('loan.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('loan.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loan.index')->with('success', trans('Deleted Successfully'));
    }

    /**
     * Loan Receive list
     *
     * @access public
     * @return mixed
     */
    public function loanReceiveList(Request $request)
    {
        $userId = Auth::user()->id;
        $branches = Branch::select('id', 'branch_name')->orderBy('id', 'DESC')->where('company_id', session('company_id'))->get();
        $loans = collect();

        if ($request->branch_id) {
            $loans = DB::table('loans')
                ->select(
                    'loans.*',
                    'employees.name as employee_name',
                    'employees.e_id as employee_id',
                    DB::raw('loan_installment_amount * loan_installment as loan_amount'),
                    DB::raw('loan_installment_amount * loan_installment - COALESCE(SUM(loans_pays.pay_amount), 0) as due_amount'),
                    DB::raw('COUNT(loans_pays.id) as due_installment'),
                    DB::raw('loan_installment_amount * loan_installment - COALESCE(SUM(loans_pays.pay_amount), 0) as due_loan_amount'),
                )
                ->join('employees', 'loans.employee_id', '=', 'employees.id')
                ->leftJoin('loans_pays', 'loans.id', '=', 'loans_pays.loan_id')
                ->where('loans.company_id', Session::get('company_id'))
                ->where('loans.branch_id', $request->branch_id)
                ->where('loans.loan_status', '3')
                ->whereNotIn('loans.receive_loan', ['salary'])
                ->groupBy('loans.id', 'employees.name', 'employees.e_id', 'loan_installment_amount', 'loan_installment')
                ->orderBy('loans.id', 'DESC')
                ->get();
            //->toSql();
        }
        //dd($loans);
        return view('loan.receive_list', compact('userId', 'branches', 'loans'));
    }

    public function selectedEmployeeData(Request $request)
    {
        $tableId = $request->branchId;
        $employee = Employee::where('branch_id', $tableId)->pluck('name', 'id');
        return response()->json($employee);
    }

    public function loanStatus(Request $request)
    {
        $this->validate($request, [
            'loan_id' => 'required',
            'loan_status' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $exists = Loan::find($request->loan_id);

            if ($request->loan_status == "3") {
                if ($exists->receive_loan == "salary") {
                    $date = $exists->from_date;
                    $month = date("m", strtotime($date));
                    $year = date('Y', strtotime($date));
                    $monthStart = $year . "-" . $month . "-01";
                    $installment = $exists->loan_installment;
                    $loanPayExists = LoansPay::find($request->loan_id);
                    if (!$loanPayExists) {
                        for ($i = 1; $i <= $installment; $i++) {
                            $receiveLoan = LoansPay::create([
                                'loan_id' => $exists->id,
                                'employee_id' => $exists->employee_id,
                                'branch_id' => $exists->branch_id,
                                'company_id' => $exists->company_id,
                                'pay_amount' => $exists->loan_installment_amount,
                                'pay_date' => date("Y-m-d", strtotime(date($monthStart) . " +$i months")),
                                'description' => "",
                                'bank_name' => "",
                                'branch_name' => "",
                                'account_name' => "",
                                'cheque_number' => ""
                            ]);
                        }
                    }
                }
            }
            $exists->loan_status = $request->loan_status;
            $exists->save();
            DB::commit();
            Session::flash('successMessage', 1);
            echo json_encode(array("status" => "1"));
        } catch (Exception $e) {
            DB::rollback();
            return $e;
            Session::flash('errorMessage', 0);
            echo json_encode(array("status" => "0"));
        }
    }

    public function adjustmentLoan(string $id)
    {
        $loan = Loan::with(['branch:id,branch_name', 'employee:id,name', 'loansPay', 'company'])->find($id);
        $loanAmount = $loan->loan_installment_amount * $loan->loan_installment;

        $pay =  $loan->loansPay;
        $paySum = 0;
        foreach ($pay as $value) {
            $paySum = $paySum + $value->pay_amount;
        }

        $dueLoanAmount = $loanAmount - $paySum;

        return view('loan.adjustment_loan', compact('loan', 'paySum', 'loanAmount', 'dueLoanAmount'));
    }

    public function receiveLoanAction(Request $request)
    {
        $ApplicationSetting = ApplicationSetting::first();
        if ($ApplicationSetting->is_demo == "1") {
            Session::flash('demo_error', 'This Feature Is Disabled In Demo Version');
            return redirect()->back();
        }
        $this->validate($request, [
            'loan_id' => 'required',
            'branch_id' => 'required',
            'employee_id' => 'required',
            'pay_amount' => 'required',
            'pay_date' => 'required'
        ]);
        DB::beginTransaction();
        try {
            LoansPay::create([
                'loan_id' => $request->loan_id,
                'employee_id' => $request->employee_id,
                'branch_id' => $request->branch_id,
                'company_id' => Session::get('company_id'),
                'pay_amount' => $request->pay_amount,
                'pay_date' => $request->pay_date,
                'description' => $request->description,
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_name' => $request->account_name,
                'cheque_number' => $request->cheque_number
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('loan-receive')->with('success', trans('Loan Receive Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 0);
            return redirect()->route('loan.index')->with('error', trans('Not Receive Successfully'));
        }
    }

    public function viewLoanAction(string $id)
    {
        $loan = Loan::with(['branch:id,branch_name', 'loansPay', 'employee:id,designation_id,name', 'employee' => function ($q) {
            return $q->with('designation:id,designation_name');
        }])->find($id);
        return view('loan.receive_view', compact('loan'));
    }
}
