<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'student_id',
        'date',
        'activity_title',
        'activity_description',
        'photo',
        'status',
        'verification_notes',
        'verified_at',
        'verified_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
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
