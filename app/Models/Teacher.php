<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_km',
        'name_en',
        'subject',
        'province',
        'school_id',
        'cluster_id',
        'mentor_id',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the primary school for this teacher
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(PilotSchool::class, 'school_id');
    }

    /**
     * Get all schools this teacher is assigned to
     */
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(PilotSchool::class, 'teacher_schools')
            ->withPivot('subject', 'assigned_date', 'end_date', 'is_primary')
            ->withTimestamps();
    }

    /**
     * Get the cluster for this teacher
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    /**
     * Get the mentor for this teacher
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    /**
     * Get the user account if exists
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get students taught by this teacher
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            PilotSchool::class,
            'id', // Foreign key on pilot_schools table
            'pilot_school_id', // Foreign key on students table
            'school_id', // Local key on teachers table
            'id' // Local key on pilot_schools table
        );
    }

    /**
     * Scope for active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for teachers by subject
     */
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope for teachers by province
     */
    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    /**
     * Get display name (Khmer name with English in parentheses)
     */
    public function getDisplayNameAttribute()
    {
        return $this->name_km . ' (' . $this->name_en . ')';
    }
}