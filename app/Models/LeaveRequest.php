<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'leave_requests';
    protected $guarded = [];

    protected static function booted()
    {
        static::updated(function ($leaveRequest) {
            if ($leaveRequest->status == 'approved') {
                $leaveRequest->createAttendanceRecords();
            }
        });
    }

    // Method untuk membuat record attendance
    public function createAttendanceRecords()
    {
        $startDate = \Carbon\Carbon::parse($this->start_date);
        $endDate = \Carbon\Carbon::parse($this->end_date);

        $dates = [];
        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->toDateString();
            $startDate->addDay();
        }

        foreach ($dates as $date) {
            \App\Models\Attendance::create([
                'employee_id' => $this->employee_id,
                'date' => $date,
                'status' => 'absent',
            ]);
        }
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('employee_id', 'like', '%' . $search . '%')
            ->orWhere('start_date', 'like', '%' . $search . '%')
            ->orWhere('end_date', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%');
    }

    public function employe()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
