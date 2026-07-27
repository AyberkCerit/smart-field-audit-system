<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Task extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Görev {$eventName}");
    }

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'audit_point_id',
        'assigned_to',
        'assigned_manager',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function auditPoint(): BelongsTo
    {
        return $this->belongsTo(AuditPoint::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_manager');
    }

    public function locationLogs(): HasMany
    {
        return $this->hasMany(TaskLocationLog::class);
    }
}