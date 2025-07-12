<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'cycle',
        'subject',
        'level',
        'score',
        'assessed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assessed_at' => 'date',
        'score' => 'float',
    ];

    /**
     * Get the student that the assessment belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
