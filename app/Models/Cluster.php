<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_km',
        'name_en',
        'province',
    ];

    /**
     * Get schools in this cluster
     */
    public function schools(): HasMany
    {
        return $this->hasMany(PilotSchool::class);
    }

    /**
     * Get teachers in this cluster
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name_en . ' (' . $this->name_km . ')';
    }

    /**
     * Scope by province
     */
    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }
}