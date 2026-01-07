<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_city',
        'location_district',
        'location_village',
        'work_date',
        'start_time',
        'end_time',
        'activity_details',
        'site_pic',
        'status',
        'bast_scan_path',
        'notes',
    ];

    protected $casts = [
        'work_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evidencePhotos(): HasMany
    {
        return $this->hasMany(EvidencePhoto::class);
    }

    public function getOnSitePhotosAttribute(): array
    {
        return $this->evidencePhotos()->where('category', 'on_site')->get()->toArray();
    }

    public function getWorkAreaPhotosAttribute(): array
    {
        return $this->evidencePhotos()->where('category', 'work_area')->get()->toArray();
    }

    public function getWorkProofPhotosAttribute(): array
    {
        return $this->evidencePhotos()->where('category', 'work_proof')->get()->toArray();
    }

    public function getOtherPhotosAttribute(): array
    {
        return $this->evidencePhotos()->where('category', 'other')->get()->toArray();
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('location_city', 'like', "%{$search}%")
                ->orWhere('location_district', 'like', "%{$search}%")
                ->orWhere('location_village', 'like', "%{$search}%");
        });
    }

    public function scopeByDateRange($query, $from, $to)
    {
        if ($from) {
            $query->where('work_date', '>=', $from);
        }
        if ($to) {
            $query->where('work_date', '<=', $to);
        }
        return $query;
    }

    public function getLocationFullAttribute(): string
    {
        return "{$this->location_city}, {$this->location_district}, {$this->location_village}";
    }

    public function isFinal(): bool
    {
        return $this->status === 'Final';
    }

    public function hasRequiredEvidence(): bool
    {
        $required = ['on_site', 'work_area', 'work_proof'];
        $existing = $this->evidencePhotos()->pluck('category')->unique()->toArray();

        return empty(array_diff($required, $existing));
    }

    public function canSubmitFinal(): bool
    {
        return !empty($this->end_time) && !empty($this->bast_scan_path) && $this->hasRequiredEvidence();
    }
}
