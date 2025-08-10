<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'school_id', 'class_id', 'teacher_id', 'attendance_date',
        'status', 'arrival_time', 'departure_time', 'minutes_late', 'late_reason',
        'absence_reason', 'parent_notified', 'notification_method', 'notes',
        'period', 'participated_in_activities', 'subjects_attended', 'subjects_missed',
        'recorded_by', 'recorded_at', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'arrival_time' => 'datetime:H:i',
        'departure_time' => 'datetime:H:i',
        'parent_notified' => 'boolean',
        'participated_in_activities' => 'boolean',
        'subjects_attended' => 'array',
        'subjects_missed' => 'array',
        'recorded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'sclAutoID');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->whereIn('status', ['absent', 'sick']);
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function getIsLateAttribute()
    {
        return $this->status === 'late' || $this->minutes_late > 0;
    }

    public function getIsPresentAttribute()
    {
        return in_array($this->status, ['present', 'late']);
    }

    public function getIsAbsentAttribute()
    {
        return in_array($this->status, ['absent', 'sick', 'excused']);
    }

    public static function calculateAttendanceRate($studentId, $startDate = null, $endDate = null)
    {
        $query = self::where('student_id', $studentId);

        if ($startDate && $endDate) {
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        }

        $total = $query->count();

        if ($total === 0) {
            return 0;
        }

        $present = $query->whereIn('status', ['present', 'late'])->count();

        return round(($present / $total) * 100, 2);
    }
}
