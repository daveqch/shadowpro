<?php

namespace App\Http\Controllers;


use App\Models\Salary;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SalaryController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:salary-read|salary-create|salary-update|salary-delete', ['only' => ['index']]);
        $this->middleware('permission:salary-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:salary-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:salary-delete', ['only' => ['destroy']]);
        $this->middleware('permission:salary-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $salaries = $this->filter($request)->paginate(10)->withQueryString();
        return view('salaries.index', compact('salaries'));
    }

    private function filter(Request $request)
    {
        $query = Salary::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%' . $request->name . '%');

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        return view('salaries.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'salary_name' => ['required', 'string', 'max:255'],
            'basic_salary' => ['required', 'numeric'],
            'house_rent' => ['nullable', 'numeric'],
            'house_rent_amount' => ['nullable', 'numeric'],
            'medical_allowance' => ['nullable', 'numeric'],
            'medical_allowance_amount' => ['nullable', 'numeric'],
            'conveyance_allowance' => ['nullable', 'numeric'],
            'conveyance_allowance_amount' => ['nullable', 'numeric'],
            'food_allowance' => ['nullable', 'numeric'],
            'food_allowance_amount' => ['nullable', 'numeric'],
            'communication_allowance' => ['nullable', 'numeric'],
            'communication_allowance_amount' => ['nullable', 'numeric'],
            'other' => ['nullable', 'numeric'],
            'other_amount' => ['nullable', 'numeric'],
        ]);

        $basicSalary =  ($request->basic_salary == (int) $request->basic_salary) ? (int) $request->basic_salary : (float) $request->basic_salary;
        $houseRent =  ($request->house_rent == (int) $request->house_rent) ? (int) $request->house_rent : (float) $request->house_rent;
        $medicalAllowance =  ($request->medical_allowance == (int) $request->medical_allowance) ? (int) $request->medical_allowance : (float) $request->medical_allowance;
        $conveyanceAllowance =  ($request->conveyance_allowance == (int) $request->conveyance_allowance) ? (int) $request->conveyance_allowance : (float) $request->conveyance_allowance;
        $foodAllowance =  ($request->food_allowance == (int) $request->food_allowance) ? (int) $request->food_allowance : (float) $request->food_allowance;
        $communicationAllowance =  ($request->communication_allowance == (int) $request->communication_allowance) ? (int) $request->communication_allowance : (float) $request->communication_allowance;
        $other =  ($request->other == (int) $request->other) ? (int) $request->other : (float) $request->other;
        $houseRentAmount = ($basicSalary * $houseRent) / 100;
        $medicalAllowanceAmount = ($medicalAllowance * $basicSalary) / 100;
        $conveyanceAllowanceAmount = ($conveyanceAllowance * $basicSalary) / 100;
        $foodAllowanceAmount = ($foodAllowance * $basicSalary) / 100;
        $communicationAllowanceAmount = ($communicationAllowance * $basicSalary) / 100;
        $otherAmount = ($other * $basicSalary) / 100;

        DB::beginTransaction();
        try {
            Salary::create([
                'parent_id' => auth()->user()->id,
                'company_id' => session('company_id'),
                'salary_name' => $request->salary_name,
                'basic_salary' => $request->basic_salary,
                'house_rent' => $request->house_rent,
                'house_rent_amount' => $houseRentAmount,
                'medical_allowance' => $request->medical_allowance,
                'medical_allowance_amount' => $medicalAllowanceAmount,
                'conveyance_allowance' => $request->conveyance_allowance,
                'conveyance_allowance_amount' => $conveyanceAllowanceAmount,
                'food_allowance' => $request->food_allowance,
                'food_allowance_amount' => $foodAllowanceAmount,
                'communication_allowance' => $request->communication_allowance,
                'communication_allowance_amount' => $communicationAllowanceAmount,
                'other' => $request->other,
                'other_amount' => $otherAmount,
                'enabled' => 1
            ]);

            DB::commit();
            Session::flash('successMessage', 1);
            return redirect()->route('salary.index')->with('success', trans('Salary Added Successfully'));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return redirect()->route('salary.index')->with('error', trans('Salary Not Added Successfully'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        return view('salaries.edit', compact('company', 'salary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        $request->validate([
            'salary_name' => ['required', 'string', 'max:255'],
            'basic_salary' => ['required', 'numeric'],
            'house_rent' => ['nullable', 'numeric'],
            'house_rent_amount' => ['nullable', 'numeric'],
            'medical_allowance' => ['nullable', 'numeric'],
            'medical_allowance_amount' => ['nullable', 'numeric'],
            'conveyance_allowance' => ['nullable', 'numeric'],
            'conveyance_allowance_amount' => ['nullable', 'numeric'],
            'food_allowance' => ['nullable', 'numeric'],
            'food_allowance_amount' => ['nullable', 'numeric'],
            'communication_allowance' => ['nullable', 'numeric'],
            'communication_allowance_amount' => ['nullable', 'numeric'],
            'other' => ['nullable', 'numeric'],
            'other_amount' => ['nullable', 'numeric'],
        ]);

        $data = $request->only(['salary_name', 'basic_salary', 'house_rent', 'house_rent_amount', 'medical_allowance', 'medical_allowance_amount', 'conveyance_allowance', 'conveyance_allowance_amount', 'food_allowance', 'food_allowance_amount', 'communication_allowance', 'communication_allowance_amount', 'other', 'other_amount']);
        $salary->update($data);
        return redirect()->route('salary.index')->with('success', trans('Salary Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salary.index')->with('success', trans('Salary Deleted Successfully'));
    }

    /**
     * Method to select employee data
     *
     * @param Request $request
     * @access public
     * @return mixed
     */
    public function selectedEmployeeData(Request $request)
    {
        $employees = Employee::orderBy('id', 'DESC')
            ->where('branch_id', $request->branchId)
            ->pluck('name', 'id');
        return response()->json($employees);
    }
}
