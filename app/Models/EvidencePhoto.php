<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvidencePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'category',
        'file_path',
        'original_name',
    ];

    protected $appends = ['url'];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'on_site' => 'On Site',
            'work_area' => 'Area Pekerjaan',
            'work_proof' => 'Bukti Pekerjaan',
            'other' => 'Dokumentasi Lain',
            default => 'Unknown',
        };
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
