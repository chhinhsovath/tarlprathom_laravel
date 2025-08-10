<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotSchool extends Model
{
    use HasFactory;

    protected $table = 'pilot_schools';

    protected $fillable = [
        'province',
        'district', 
        'cluster',
        'school_name',
        'school_code',
    ];

    /**
     * Get unique provinces from pilot schools
     */
    public static function getProvinces()
    {
        return self::select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');
    }

    /**
     * Get unique districts from pilot schools
     */
    public static function getDistricts($province = null)
    {
        $query = self::select('district')->distinct();
        
        if ($province) {
            $query->where('province', $province);
        }
        
        return $query->orderBy('district')->pluck('district');
    }

    /**
     * Get unique clusters from pilot schools
     */
    public static function getClusters($province = null, $district = null)
    {
        $query = self::select('cluster')->distinct();
        
        if ($province) {
            $query->where('province', $province);
        }
        
        if ($district) {
            $query->where('district', $district);
        }
        
        return $query->orderBy('cluster')->pluck('cluster');
    }

    /**
     * Get schools by filters
     */
    public static function getSchoolsByFilters($province = null, $district = null, $cluster = null)
    {
        $query = self::query();
        
        if ($province) {
            $query->where('province', $province);
        }
        
        if ($district) {
            $query->where('district', $district);
        }
        
        if ($cluster) {
            $query->where('cluster', $cluster);
        }
        
        return $query->orderBy('school_name')->get();
    }

    /**
     * Find school by code
     */
    public static function findByCode($schoolCode)
    {
        return self::where('school_code', $schoolCode)->first();
    }

    /**
     * Map to old School model fields for compatibility
     */
    public function getSclAutoIDAttribute()
    {
        return $this->id;
    }

    public function getSclNameAttribute()
    {
        return $this->school_name;
    }

    public function getSclCodeAttribute()
    {
        return $this->school_code;
    }

    public function getSclProvinceNameAttribute()
    {
        return $this->province;
    }

    public function getSclDistrictNameAttribute()
    {
        return $this->district;
    }

    public function getSclClusterNameAttribute()
    {
        return $this->cluster;
    }
}
