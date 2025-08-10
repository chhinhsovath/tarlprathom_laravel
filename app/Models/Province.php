<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'province_code',
        'name_en',
        'name_kh',
    ];

    public function geographic()
    {
        return $this->hasMany(Geographic::class);
    }
}
