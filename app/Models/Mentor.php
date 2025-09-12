<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_km',
        'name_en',
        'province',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user account for this mentor
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get teachers mentored by this mentor
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get schools through teachers
     */
    public function schools()
    {
        return $this->hasManyThrough(
            PilotSchool::class,
            Teacher::class,
            'mentor_id',
            'id',
            'id',
            'school_id'
        )->distinct();
    }

    /**
     * Scope for active mentors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name_km . ' (' . $this->name_en . ')';
    }
}