<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Bank;
use App\Models\User;
use App\Models\Branch;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * load constructor method 
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:employee-read|employee-create|employee-update|employee-delete', ['only' => ['index']]);
        $this->middleware('permission:employee-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:employee-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
        $this->middleware('permission:employee-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = $this->filter($request)->paginate(10)->withQueryString();
        return view('employee.index', compact('employees'));
    }

    private function filter(Request $request)
    {
        $query = Employee::where('company_id', session('company_id'))->latest();

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
        $companyId = Session::get('company_id');
        if (empty($companyId)) abort(500, 'Something went wrong');

        $branches = Branch::select('id', 'branch_name')
            ->where('enabled', 1)
            ->where('company_id', $companyId)
            ->get();

        $departments = Department::select('id', 'department_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        $designations = Designation::select('id', 'designation_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        $roles = Role::select('id', 'name')
            ->where('name', '!=', 'Super Admin')
            ->get();

        $salaries = Salary::select('id', 'salary_name')
            ->where('company_id', $companyId)
            ->get();

        return view('employee.create', compact('branches', 'departments', 'designations', 'roles', 'salaries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bankId = NULL;
        $photo = "";
        $resume = "";
        $offerLetter = "";
        $joiningLetter = "";
        $contractAndAgreement = "";
        $identityProof = "";

        $this->validate($request, [
            'full_name' => 'required',
            'phone' => 'required|unique:employees',
            'birth_date' => 'required',
            'gender' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'role_id' => 'required',
            'e_id' => 'required|unique:employees',
            'department_id' => 'required',
            'designation_id' => 'required',
            'job_type' => 'required',
            'branch_id' => 'required',
            'salary_id' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password',
            'joining_date' => 'required|date'
        ]);

        if ($request->hasFile('photo')) {
            $this->validate($request, ['photo' => 'image|mimes:png,jpg,jpeg|max:5120']);
            $photo = $request->photo;
            $photoNewName = time() . $photo->getClientOriginalName();
            $photo->move('uploads/employee', $photoNewName);
            $photo = 'uploads/employee/' . $photoNewName;
        }

        if ($request->hasFile('resume')) {
            $this->validate($request, ['resume' => 'mimes:pdf,doc,docx|max:400']);
            $resume = $request->resume;
            $resumeNewName = "resume_" . time() . $resume->getClientOriginalName();
            $resume->move('uploads/employee', $resumeNewName);
            $resume = 'uploads/employee/' . $resumeNewName;
        }

        if ($request->hasFile('offer_letter')) {
            $this->validate($request, ['offer_letter' => 'mimes:pdf,doc,docx|max:400']);
            $offerLetter = $request->offer_letter;
            $offerLetterNewName = "offer_letter_" . time() . $offerLetter->getClientOriginalName();
            $offerLetter->move('uploads/employee', $offerLetterNewName);
            $offerLetter = 'uploads/employee/' . $offerLetterNewName;
        }

        if ($request->hasFile('joining_letter')) {
            $this->validate($request, ['joining_letter' => 'mimes:pdf,doc,docx|max:400']);
            $joiningLetter = $request->joining_letter;
            $joiningLetterNewName = "joining_letter_" . time() . $joiningLetter->getClientOriginalName();
            $joiningLetter->move('uploads/employee', $joiningLetterNewName);
            $joiningLetter = 'uploads/employee/' . $joiningLetterNewName;
        }

        if ($request->hasFile('contract_and_agreement')) {
            $this->validate($request, ['contract_and_agreement' => 'mimes:pdf,doc,docx|max:400']);

            $contractAndAgreement = $request->contract_and_agreement;
            $contractAndAgreementNewName = "contract_and_agreement_" . time() . $contractAndAgreement->getClientOriginalName();
            $contractAndAgreement->move('uploads/employee', $contractAndAgreementNewName);
            $contractAndAgreement = 'uploads/employee/' . $contractAndAgreementNewName;
        }

        if ($request->hasFile('identity_proof')) {
            $this->validate($request, ['identity_proof' => 'mimes:pdf,doc,docx|max:250']);
            $identityProof = $request->identity_proof;
            $identityProofNewName = "identity_proof_" . time() . $identityProof->getClientOriginalName();
            $identityProof->move('uploads/employee', $identityProofNewName);
            $identityProof = 'uploads/employee/' . $identityProofNewName;
        }
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => "1",
                'address' => $request->permanent_address,
                'phone' => $request->phone
            ]);
            $user->assignRole($request->input('role_id'));
            // Attach company
            $user->companies()->attach($request->company);

            $userId = $user->id;

            if ($request->bank_name && $request->account_name && $request->account_number) {
                $bank = Bank::create([
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'account_name' => $request->account_name,
                    'account_number' => $request->account_number
                ]);
                $bankId = $bank->id;
            }

            $employee = Employee::create([
                'user_id' => $userId,
                'bank_id' => $bankId,
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'e_id' => $request->e_id,
                'name' => $request->full_name,
                'photo' => $photo,
                'joining_date' => $request->joining_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'present_address' => $request->present_address,
                'permanent_address' => $request->permanent_address,
                'email' => $request->email,
                'salary_id' => $request->salary_id,
                'password' => "",
                'job_type' => $request->job_type,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_number' => $request->emergency_contact_number,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'emergency_contact_note' => $request->emergency_contact_note,
                'resume' => $resume,
                'offer_letter' => $offerLetter,
                'joining_letter' => $joiningLetter,
                'contract_and_agreement' => $contractAndAgreement,
                'identity_proof' => $identityProof,
                'note' => $request->note
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            return response()->json(['status' => 'failed']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with(['user', 'bank', 'company:id'])->find($id);
        return view('employee.details', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = Session::get('company_id');
        if (empty($companyId)) abort(500, 'Something went wrong');


        $employee = Employee::with(['user', 'bank'])->find($id);

        $branches = Branch::select('id', 'branch_name')
            ->where('enabled', 1)
            ->where('company_id', $companyId)
            ->get();

        $departments = Department::select('id', 'department_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        $designations = Designation::select('id', 'designation_name')
            ->where('company_id', $companyId)
            ->where('enabled', 1)
            ->get();

        $roles = Role::select('id', 'name')
            ->where('name', '!=', 'Super Admin')
            ->get();

        $salaries = Salary::select('id', 'salary_name')
            ->where('company_id', $companyId)
            ->get();

        return view('employee.edit', compact('employee', 'branches', 'departments', 'designations', 'roles', 'salaries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request->all());
        $photo = "";
        $resume = "";
        $offerLetter = "";
        $joiningLetter = "";
        $contractAndAgreement = "";
        $identityProof = "";
        $bankId = NULL;
        $employee = Employee::find($id);

        $this->validate($request, [
            'full_name' => 'required',
            'phone' => 'required|' . Rule::unique('employees', 'phone')->ignore($id),
            'birth_date' => 'required',
            'gender' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'role_id' => 'required',
            'e_id' => 'required|' . Rule::unique('employees', 'e_id')->ignore($id),
            'department_id' => 'required',
            'designation_id' => 'required',
            'job_type' => 'required',
            'branch_id' => 'required',
            'salary_id' => 'required',
            'email' => 'required|email|' . Rule::unique('employees', 'email')->ignore($id),
            'joining_date' => 'required|date'
        ]);

        if ($request->hasFile('photo')) {
            if (file_exists($employee->photo)) {
                unlink($employee->photo);
            }
            $this->validate($request, ['photo' => 'image|mimes:png,jpg,jpeg|max:5120']);
            $photo = $request->photo;
            $photoNewName = time() . $photo->getClientOriginalName();
            $photo->move('uploads/employee', $photoNewName);
            $photo = 'uploads/employee/' . $photoNewName;
        } else $photo = $employee->photo;


        if ($request->hasFile('resume')) {
            if (file_exists($employee->resume)) {
                unlink($employee->resume);
            }
            $this->validate($request, ['resume' => 'mimes:pdf,doc,docx|max:400']);
            $resume = $request->resume;
            $resumeNewName = "resume_" . time() . $resume->getClientOriginalName();
            $resume->move('uploads/employee', $resumeNewName);
            $resume = 'uploads/employee/' . $resumeNewName;
        } else $resume = $employee->resume;

        if ($request->hasFile('offer_letter')) {
            if (file_exists($employee->offer_letter)) {
                unlink($employee->offer_letter);
            }
            $this->validate($request, ['offer_letter' => 'mimes:pdf,doc,docx|max:400']);
            $offerLetter = $request->offer_letter;
            $offerLetterNewName = "offer_letter_" . time() . $offerLetter->getClientOriginalName();
            $offerLetter->move('uploads/employee', $offerLetterNewName);
            $offerLetter = 'uploads/employee/' . $offerLetterNewName;
        } else $offerLetter = $employee->offer_letter;

        if ($request->hasFile('joining_letter')) {
            if (file_exists($employee->joining_letter)) {
                unlink($employee->joining_letter);
            }
            $this->validate($request, ['joining_letter' => 'mimes:pdf,doc,docx|max:400']);
            $joiningLetter = $request->joining_letter;
            $joiningLetterNewName = "joining_letter_" . time() . $joiningLetter->getClientOriginalName();
            $joiningLetter->move('uploads/employee', $joiningLetterNewName);
            $joiningLetter = 'uploads/employee/' . $joiningLetterNewName;
        } else $joiningLetter = $employee->joining_letter;

        if ($request->hasFile('contract_and_agreement')) {
            if (file_exists($employee->contract_and_agreement)) {
                unlink($employee->contract_and_agreement);
            }
            $this->validate($request, ['contract_and_agreement' => 'mimes:pdf,doc,docx|max:400']);

            $contractAndAgreement = $request->contract_and_agreement;
            $contractAndAgreementNewName = "contract_and_agreement_" . time() . $contractAndAgreement->getClientOriginalName();
            $contractAndAgreement->move('uploads/employee', $contractAndAgreementNewName);
            $contractAndAgreement = 'uploads/employee/' . $contractAndAgreementNewName;
        } else $contractAndAgreement = $employee->contract_and_agreement;


        if ($request->hasFile('identity_proof')) {
            if (file_exists($employee->identity_proof)) {
                unlink($employee->identity_proof);
            }
            $this->validate($request, ['identity_proof' => 'mimes:pdf,doc,docx|max:250']);
            $identityProof = $request->identity_proof;
            $identityProofNewName = "identity_proof_" . time() . $identityProof->getClientOriginalName();
            $identityProof->move('uploads/employee', $identityProofNewName);
            $identityProof = 'uploads/employee/' . $identityProofNewName;
        } else $identityProof = $employee->identity_proof;
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $user = User::find($request->user_id);
            $user->update([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => "1",
                'address' => $request->permanent_address,
                'phone' => $request->phone
            ]);

            if (DB::table('model_has_roles')->where('model_id', $user->id)->delete())
                $user->assignRole($request->input('role_id'));
            // Attach company
            //$user->companies()->attach($request->company);


            if ($request->bank_name && $request->account_name && $request->account_number && $request->bank_id) {
                $bank = Bank::find($request->bank_id)->update(
                    [
                        'bank_name' => $request->bank_name,
                        'branch_name' => $request->branch_name,
                        'account_name' => $request->account_name,
                        'account_number' => $request->account_number
                    ]
                );
                $bankId = $request->bank_id;
            } else if ($request->bank_name && $request->account_name && $request->account_number) {
                $bank = Bank::create(
                    [
                        'bank_name' => $request->bank_name,
                        'branch_name' => $request->branch_name,
                        'account_name' => $request->account_name,
                        'account_number' => $request->account_number
                    ]
                );
                $bankId = $bank->id;
            }

            $employee->update([
                'user_id' => $request->user_id,
                'bank_id' => $bankId,
                'company_id' => Session::get('company_id'),
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'e_id' => $request->e_id,
                'name' => $request->full_name,
                'photo' => $photo ?? $employee->photo,
                'joining_date' => $request->joining_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'present_address' => $request->present_address,
                'permanent_address' => $request->permanent_address,
                'email' => $request->email,
                'salary_id' => $request->salary_id,
                'password' => "",
                'job_type' => $request->job_type,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_number' => $request->emergency_contact_number,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'emergency_contact_note' => $request->emergency_contact_note,
                'resume' => $resume,
                'offer_letter' => $offerLetter,
                'joining_letter' => $joiningLetter,
                'contract_and_agreement' => $contractAndAgreement,
                'identity_proof' => $identityProof,
                'note' => $request->note
            ]);
            DB::commit();
            Session::flash('successMessage', 1);
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            return $e;
            DB::rollback();
            Session::flash('errorMessage', 1);
            return response()->json(['status' => 'failed']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if (file_exists($employee->photo)) {
            unlink($employee->photo);
        }
        if (file_exists($employee->resume)) {
            unlink($employee->resume);
        }
        if (file_exists($employee->offer_letter)) {
            unlink($employee->offer_letter);
        }
        if (file_exists($employee->joining_letter)) {
            unlink($employee->joining_letter);
        }
        if (file_exists($employee->contract_and_agreement)) {
            unlink($employee->contract_and_agreement);
        }
        if (file_exists($employee->identity_proof)) {
            unlink($employee->identity_proof);
        }
        $employee->delete();
        return redirect()->route('employee.index')->with('success', trans('Deleted Successfully'));
    }

    /**
     * Method to check unique employee id
     *
     * @access public
     * @param Request $request
     */
    public function uniqueEidCheck(Request $request)
    {
        $companyId = Session::get('company_id');
        $eId = $request->e_id;
        $info = Employee::select('id')->where('company_id', $companyId)->where('e_id', $eId)->first();
        if ($info === NULL) {
            return response()->json(["status" => 0]);
        } else {
            return response()->json(["status" => 1]);
        }
    }
}
