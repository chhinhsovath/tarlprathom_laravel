<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceView extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'user_id',
        'ip_address',
        'watch_duration',
        'total_duration',
        'completion_percentage',
    ];

    protected $casts = [
        'watch_duration' => 'integer',
        'total_duration' => 'integer',
        'completion_percentage' => 'decimal:2',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
