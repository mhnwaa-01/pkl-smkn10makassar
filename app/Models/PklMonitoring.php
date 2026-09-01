<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklMonitoring extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pkl_monitorings';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teacher_id',
        'industry_id',
        'student_id',
        'visit_date',
        'notes',
        'obstacles',
        'recommendations',
        'photo',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Resolves photo URL with automatic fallback for uploads and storage.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://') || str_starts_with($this->photo, 'data:')) {
            return $this->photo;
        }

        if (str_starts_with($this->photo, 'uploads/')) {
            return asset($this->photo);
        }

        if (str_starts_with($this->photo, 'storage/')) {
            return asset($this->photo);
        }

        if (file_exists(public_path('uploads/' . $this->photo))) {
            return asset('uploads/' . $this->photo);
        }

        if (file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }

        return asset('storage/' . $this->photo);
    }
}
