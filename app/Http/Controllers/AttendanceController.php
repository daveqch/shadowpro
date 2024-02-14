<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Leave;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class AttendanceController extends Controller
{

    /**
     * load constructor method 
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:attendance-create', ['only' => ['index']]);
        $this->middleware('permission:attendance-dayWiseAttendance', ['only' => ['storeUpdate', 'getInTimeOutTime', 'selectedLeaveType']]);
        $this->middleware('permission:attendance-employeeAttendance', ['only' => ['employeeWiseAttendance', 'selectedEmployeeData']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = collect();
        if ($request->branch_id) {
            $employees = Employee::with(['designation', 'attendance' => function ($q) use ($request) {
                return $q->select("*")->whereDate('current_date', $request->attendance_date);
            }])
                ->orderBy('id', 'DESC')
                ->where('company_id', Session::get('company_id'))
                ->where('branch_id', $request->branch_id)->get();
        }


        $branches = Branch::select('id', 'branch_name')->orderBy('id', 'DESC')
            ->where('company_id', session('company_id'))
            ->get();

        return view('attendance.index', compact('branches', 'employees'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function storeUpdate(Request $request)
    {
        $ApplicationSetting = ApplicationSetting::first();
        if ($ApplicationSetting->is_demo == "1") {
            Session::flash('demo_error', 'This Feature Is Disabled In Demo Version');
            return redirect()->back();
        }
        $this->validate($request, [
            'employee_id' => 'required',
            'presentAbsent' => 'required'
        ]);

        $mySqlOutTime = NULL;
        $mySqlInTime = NULL;
        $mySqlWorkingTime = NULL;
        $absent = "0";
        if ($request->presentAbsent == "0") {
            $absent = "1";
            $mySqlInTime = "00:00:00";
            $mySqlOutTime = "00:00:00";
        }
        if ($request->in_time == NULL) {
            $mySqlInTime  = NULL;
        } else {
            $mySqlInTime  = date("H:i", strtotime($request->in_time));
        }
        if ($request->out_time == NULL) {
            $mySqlOutTime  = NULL;
        } else {
            $mySqlOutTime  = date("H:i", strtotime($request->out_time));
        }
        if (isset($request->out_time)) {
            $diff = (strtotime($mySqlOutTime) - strtotime($mySqlInTime));
            $total = $diff / 60;
            $mySqlWorkingTime = sprintf("%02d:%02d", floor($total / 60), $total % 60);
            if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $mySqlWorkingTime) == "0") {
                Session::flash('errorMessage', 3);
                //echo json_encode(array("status" => "3"));
                return response()->json(array("status" => "3"));

                //throw ValidationException::withMessages(['Advance Salary Cant Bigger Than One Month Total Salary']);
            }
        }
        $leave_type = NULL;
        if (isset($request->leave_type) && !empty($request->leave_type)) {
            $leave_type = $request->leave_type;
        } else {
            $leave_type = $request->unpainLeave;
        }
        $userId = Auth::user()->id;
        $employee = Employee::select('id', 'department_id', 'branch_id', 'designation_id')->find($request->employee_id);
        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $exists = Attendance::where([['company_id', '=', Session::get('company_id')], ['branch_id', '=', $employee->branch_id], ['department_id', '=', $employee->department_id], ['designation_id', '=', $employee->designation_id], ['employee_id', '=', $employee->id], ['current_date', '=', $request->requestDate]])->first();
            if ($exists === null) {
                Attendance::create([
                    'parent_id' => $userId,
                    'company_id' => Session::get('company_id'),
                    'branch_id' => $employee->branch_id,
                    'department_id' => $employee->department_id,
                    'designation_id' =>  $employee->designation_id,
                    'employee_id' =>  $employee->id,
                    'current_date' => $request->requestDate,
                    'present' => $request->presentAbsent,
                    'in_time' => $mySqlInTime,
                    'out_time' => $mySqlOutTime,
                    'working_time' => $mySqlWorkingTime,
                    'late' => $absent,
                    'absent' => $absent,
                    'leave' => $absent,
                    'absent_leave_type' => $leave_type
                ]);
            } else {
                $exists->present = $request->presentAbsent;
                $exists->in_time = $mySqlInTime;
                $exists->out_time = $mySqlOutTime;
                $exists->working_time = $mySqlWorkingTime;
                $exists->late = $absent;
                $exists->absent = $absent;
                $exists->leave = $absent;
                $exists->absent_leave_type = $leave_type;
                $exists->save();
            }
            DB::commit();
            Session::flash('successMessage', 1);
            echo json_encode(array("status" => "1"));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 0);
            echo json_encode(array("status" => "0"));
        }
    }


    /**
     * Method to get intime and out time data
     *
     * @access public
     * @param Request $request
     */
    public function getInTimeOutTime(Request $request)
    {
        $employee = Employee::select('id', 'department_id', 'branch_id')->find($request->employee_id);
        $inTimeOutTime = Attendance::select('in_time', 'out_time')->where([['company_id', '=', Session::get('company_id')], ['branch_id', '=', $employee->branch_id], ['department_id', '=', $employee->department_id], ['employee_id', '=', $employee->id], ['current_date', '=', $request->requestDate]])->get();

        $inTimeOutTime = str_replace("[", "", $inTimeOutTime);
        $inTimeOutTime = str_replace("]", "", $inTimeOutTime);
        $inTimeOutTime = json_decode($inTimeOutTime, true);
        if (isset($inTimeOutTime['in_time']) && !empty($inTimeOutTime['in_time'])) {
            $inTime = date('h:iA', strtotime($inTimeOutTime['in_time']));
        } else {
            $inTime = NULL;
        }
        if (isset($inTimeOutTime['out_time']) && !empty($inTimeOutTime['out_time'])) {
            $outTime = date('h:iA', strtotime($inTimeOutTime['out_time']));
        } else {
            $outTime = NULL;
        }
        $output = array(
            'in_time' =>  $inTime,
            'out_time' =>  $outTime,
        );
        echo json_encode($output);
    }

    /**
     * Method to select leave type
     *
     * @param Request $request
     * @access public
     * @return mixed
     */
    public function selectedLeaveType()
    {
        $leaves = Leave::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->pluck('type', 'id');
        return response()->json($leaves);
    }

    public function dayWiseAttendance(Request $request)
    {
        $employees = collect();
        $branches = Branch::select('branch_name', 'id')->orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->get();

        if ($request->branch_id) {
            $employees = Employee::with(['designation', 'attendance' => function ($q) use ($request) {
                return $q->select("*")->whereDate('current_date', $request->attendance_date);
            }])
                ->orderBy('id', 'DESC')
                ->where('company_id', Session::get('company_id'))
                ->where('branch_id', $request->branch_id)->get();
        }

        return view('attendance.day_wise_attendance', compact('employees', 'branches'));
    }

    public function employeeWiseAttendance(Request $request)
    {
        $attendance = collect();
        $employeeList = collect();
        $startDate = '';
        $endDate = '';

        $branches = Branch::select('branch_name', 'id')->orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->get();

        if ($request->attendance_date) {
            $attendanceDate = $request->attendance_date;
            $attendanceDateArray = explode(" to ", $attendanceDate);
            $startDate = $attendanceDateArray[0];
            $endDate = $attendanceDateArray[1];
        }

        if ($request->branch_id && $request->attendance_date) {
            $employeeList = Employee::select('id', 'name')->where("branch_id", $request->branch_id)->get();

            $attendance = Attendance::orderBy('id', 'DESC')
                ->where('company_id', Session::get('company_id'))
                ->where('branch_id', $request->branch_id)
                ->where('employee_id', $request->employee_id)
                ->whereBetween('current_date', [$startDate, $endDate])
                ->get();
        }

        return view('attendance.employee_attendance', compact('attendance', 'branches', 'employeeList'));
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
        $table_id = $request->branchId;
        $employee = Employee::where('branch_id', $table_id)->pluck('name', 'id');
        return response()->json($employee);
    }
}
