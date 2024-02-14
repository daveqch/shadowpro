<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Branch;
use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\AdvanceSalary;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AdvanceSalaryController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:advanceSalary-read|advanceSalary-create|advanceSalary-update|advanceSalary-delete', ['only' => ['index']]);
        $this->middleware('permission:advanceSalary-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:advanceSalary-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:advanceSalary-delete', ['only' => ['destroy']]);
        $this->middleware('permission:advanceSalary-approve', ['only' => ['advanceSalaryStatus']]);
        $this->middleware('permission:advanceSalary-receive', ['only' => ['advanceSalaryStatus']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $advanceSalaries = $this->filter($request)->paginate(10)->withQueryString();
        return view('advance_salary.index', compact('advanceSalaries'));
    }


    private function filter(Request $request)
    {
        $query = AdvanceSalary::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('reason', 'like', '%' . $request->name . '%');

        if ($request->status > -1)
            $query->where('status', $request->status);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::select('id', 'branch_name')->get();
        return view('advance_salary.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'branch_id' => 'required|string|',
            'employee_id' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        /**
         * Method to check salary
         */
        $amount = intval($request->amount);
        $data = Employee::find($request->employee_id)->get('salary_id')->first();
        $data = Salary::where('id', $data->salary_id)->get()->first();

        if ($data->basic_salary) {
            $basic = $data->basic_salary;
        } else {
            $basic = 0;
        }

        if ($data->house_rent_amount) {
            $house = $data->house_rent_amount;
        } else {
            $house = 0;
        }

        if ($data->medical_allowance_amount) {
            $medical = $data->medical_allowance_amount;
        } else {
            $medical = 0;
        }

        if ($data->conveyance_allowance_amount) {
            $conveyance = $data->conveyance_allowance_amount;
        } else {
            $conveyance = 0;
        }

        if ($data->food_allowance_amount) {
            $food = $data->food_allowance_amount;
        } else {
            $food = 0;
        }

        if ($data->communication_allowance_amount) {
            $communication = $data->communication_allowance_amount;
        } else {
            $communication = 0;
        }

        if ($data->other_amount) {
            $other = $data->other_amount;
        } else {
            $other = 0;
        }

        $total = $basic + $house + $medical + $conveyance + $food + $communication + $other;

        if ($total < $amount) {
            throw ValidationException::withMessages(['Advance Salary Cant Bigger Than One Month Total Salary']);
        }

        /**
         * Method to check date
         */
        $date = $request->date;

        $month = date("m", strtotime($date));
        $year = date('Y', strtotime($date));

        $monthStart = $year . "-" . $month . "-01";
        $monthEnd = $year . "-" . $month . "-31";

        $data = AdvanceSalary::where('employee_id', $request->employee_id)->whereBetween('date', [$monthStart, $monthEnd])->get()->first();


        if (isset($data->date)) {

            if (date("m", strtotime($data->date)) == $month) {
                throw ValidationException::withMessages(['You Can Only Give Advance Salary To An Employee Once A Month']);
                //return json_encode(array("status" => 4));
            }
        }


        $applicationSetting = ApplicationSetting::first();
        if ($applicationSetting->is_demo == "1") {
            Session::flash('demo_error', 'This Feature Is Disabled In Demo Version');
            return redirect()->back();
        }

        $userId = Auth::user()->id;
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            AdvanceSalary::create([
                'parent_id' => $userId,
                'reason' => $request->reason,
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id,
                'employee_id' => $request->employee_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'remarks' => $request->note
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('advance-salary.index')->with('success', trans('Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('advance-salary.index')->with('error', trans('Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $advanceSalary = AdvanceSalary::with('branch:id,branch_name', 'employee:id,name')->find($id);
        return view('advance_salary.details', compact('advanceSalary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $advanceSalary = AdvanceSalary::with('employee:id,name')->find($id);
        $branches = Branch::select('id', 'branch_name')->get();
        $employees = Employee::select('id', 'name')->where('branch_id', $advanceSalary->branch_id)->get();
        return view('advance_salary.edit', compact('branches', 'advanceSalary', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdvanceSalary $advanceSalary)
    {
        $this->validate($request, [
            'reason' => 'required',
            'branch_id' => 'required',
            'employee_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        /**
         * Method to check salary
         */
        $amount = intval($request->amount);
        $data = Employee::find($request->employee_id)->get('salary_id')->first();
        $data = Salary::where('id', $data->salary_id)->get()->first();

        if ($data->basic_salary) {
            $basic = $data->basic_salary;
        } else {
            $basic = 0;
        }

        if ($data->house_rent_amount) {
            $house = $data->house_rent_amount;
        } else {
            $house = 0;
        }

        if ($data->medical_allowance_amount) {
            $medical = $data->medical_allowance_amount;
        } else {
            $medical = 0;
        }

        if ($data->conveyance_allowance_amount) {
            $conveyance = $data->conveyance_allowance_amount;
        } else {
            $conveyance = 0;
        }

        if ($data->food_allowance_amount) {
            $food = $data->food_allowance_amount;
        } else {
            $food = 0;
        }

        if ($data->communication_allowance_amount) {
            $communication = $data->communication_allowance_amount;
        } else {
            $communication = 0;
        }

        if ($data->other_amount) {
            $other = $data->other_amount;
        } else {
            $other = 0;
        }

        $total = $basic + $house + $medical + $conveyance + $food + $communication + $other;

        if ($total < $amount) {
            throw ValidationException::withMessages(['Advance Salary Cant Bigger Than One Month Total Salary']);
            //return json_encode(array("status" => 3));
        }


        $applicationSetting = ApplicationSetting::first();
        if ($applicationSetting->is_demo == "1") {
            Session::flash('demo_error', 'This Feature Is Disabled In Demo Version');
            return redirect()->back();
        }

        $userId = Auth::user()->id;
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $advanceSalary->update([
                'parent_id' => $userId,
                'reason' => $request->reason,
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id,
                'employee_id' => $request->employee_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'remarks' => $request->note
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('advance-salary.index')->with('success', trans('Update Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('advance-salary.index')->with('error', trans('Not Update Successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceSalary $advanceSalary)
    {
        $advanceSalary->delete();
        return redirect()->route('advance-salary.index')->with('success', trans('Deleted Successfully'));
    }

    public function advanceSalaryStatus(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);

        $applicationSetting = ApplicationSetting::first();
        if ($applicationSetting->is_demo == "1") {
            Session::flash('demo_error', 'This Feature Is Disabled In Demo Version');
            return redirect()->back();
        }

        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $data = AdvanceSalary::find($request->advanceSalaryId);
            $data->status = $request->status;
            $data->save();
            DB::commit();
            Session::flash('successMessage', 1);
            echo json_encode(array("status" => "1"));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 0);
            echo json_encode(array("status" => "0"));
        }
    }
}
