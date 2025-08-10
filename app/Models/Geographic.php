<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Geographic extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geographic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'province_code',
        'province_id',
        'province_name_kh',
        'province_name_en',
        'district_code',
        'district_name_kh',
        'district_name_en',
        'commune_code',
        'commune_name_kh',
        'commune_name_en',
        'village_code',
        'village_name_kh',
        'village_name_en',
    ];

    /**
     * Get the province that owns the geographic entry.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get unique provinces (now from provinces table)
     */
    public static function getProvinces()
    {
        return Province::select('province_code', 'name_kh as province_name_kh', 'name_en as province_name_en')
            ->orderBy('name_kh')
            ->get();
    }

    /**
     * Get unique districts for the current geographic structure
     */
    public static function getDistricts()
    {
        return self::select('district_code', 'district_name_kh', 'district_name_en', 'province_code')
            ->distinct()
            ->whereNotNull('district_code')
            ->orderBy('province_code')
            ->orderBy('district_name_kh')
            ->get();
    }

    /**
     * Get unique communes for the current geographic structure
     */
    public static function getCommunes()
    {
        return self::select('commune_code', 'commune_name_kh', 'commune_name_en', 'district_code', 'province_code')
            ->distinct()
            ->whereNotNull('commune_code')
            ->orderBy('province_code')
            ->orderBy('district_code')
            ->orderBy('commune_name_kh')
            ->get();
    }

    /**
     * Get districts by province
     */
    public static function getDistrictsByProvince($provinceCode)
    {
        return self::select('district_code', 'district_name_kh', 'district_name_en')
            ->where('province_code', $provinceCode)
            ->distinct()
            ->whereNotNull('district_code')
            ->orderBy('district_name_kh')
            ->get();
    }

    /**
     * Get communes by district
     */
    public static function getCommunesByDistrict($districtCode)
    {
        return self::select('commune_code', 'commune_name_kh', 'commune_name_en')
            ->where('district_code', $districtCode)
            ->distinct()
            ->whereNotNull('commune_code')
            ->orderBy('commune_name_kh')
            ->get();
    }

    /**
     * Get villages by commune
     */
    public static function getVillagesByCommune($communeCode)
    {
        return self::select('village_code', 'village_name_kh', 'village_name_en')
            ->where('commune_code', $communeCode)
            ->distinct()
            ->whereNotNull('village_code')
            ->orderBy('village_name_kh')
            ->get();
    }
}
