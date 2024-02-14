<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'company_id',
        'branch_id',
        'department_id',
        'designation_id',
        'employee_id',
        'current_date',
        'present',
        'in_time',
        'out_time',
        'working_time',
        'late',
        'absent',
        'leave',
        'absent_leave_type'
    ];

    /**
     * Relation belongs to company
     *
     * @return mixed
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relation belongs to branch
     *
     * @return mixed
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relation belongs to department
     *
     * @return mixed
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relation belongs to designation
     *
     * @return mixed
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Relation belongs to employee
     *
     * @return mixed
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relation belongs to leave
     *
     * @return mixed
     */
    public function absentLeaveType(): BelongsTo
    {
        return $this->belongsTo(Leave::class);
    }

    public static function inTime($attendance)
    {
        $inTime = $attendance->where('current_date', request('attendance_date'))->first();
        $inTime = str_replace("[", "", $inTime);
        $inTime = str_replace("]", "", $inTime);
        $inTime = json_decode($inTime, true);
        if (isset($inTime['in_time']) && !empty($inTime)) {
            return date('h:i A', strtotime($inTime['in_time']));
        } else {
            return "Not Available";
        }
    }

    public static function outTime($attendance)
    {
        $outTime = $attendance->where('current_date', request('attendance_date'))->first();
        $outTime = str_replace("[", "", $outTime);
        $outTime = str_replace("]", "", $outTime);
        $outTime = json_decode($outTime, true);
        if (isset($outTime['out_time']) && !empty($outTime)) {
            return date('h:i A', strtotime($outTime['out_time']));
        } else {
            return "Not Available";
        }
    }

    public static function workingTime($attendance)
    {
        $workingTime = $attendance->where('current_date', request('attendance_date'))->first();
        $workingTime = str_replace("[", "", $workingTime);
        $workingTime = str_replace("]", "", $workingTime);
        $workingTime = json_decode($workingTime, true);
        if (isset($workingTime['working_time']) && !empty($workingTime)) {
            return date('H:i', strtotime($workingTime['working_time']));
        } else {
            return "Not Available";
        }
    }

    public static function leaveType($attendance, $type = "")
    {
        if ($type === 'employee_attendance')
            $status = $attendance;
        else
            $status = $attendance->where('current_date', request('attendance_date'))->first();

        if ($status) {
            if ($status->present == "0") {
                if (isset($status->absent_leave_type) && !empty($status->absent_leave_type)) {
                    $leaveType = Leave::select('type')->where([['id', '=', $status->absent_leave_type]])->first();
                    $leaveType = $leaveType->type;
                } else {
                    $leaveType = "Unpaid Leave";
                }
            } else {
                $leaveType = "On duty";
            }
        } else {
            $leaveType = "No Attendance";
        }
        return $leaveType;
    }
}
