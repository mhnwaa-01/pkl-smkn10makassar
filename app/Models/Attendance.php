<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'student_id',
        'date',
        'check_in_time',
        'check_in_status',
        'check_in_notes',
        'check_in_photo',
        'check_out_time',
        'check_out_status',
        'check_out_notes',
        'check_out_photo',
        'location',
        'location_out',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Resolves Check-in Photo URL with fallback for direct public uploads and storage paths.
     */
    public function getCheckInPhotoUrlAttribute(): ?string
    {
        return $this->resolvePhotoUrl($this->check_in_photo);
    }

    /**
     * Resolves Check-out Photo URL with fallback for direct public uploads and storage paths.
     */
    public function getCheckOutPhotoUrlAttribute(): ?string
    {
        return $this->resolvePhotoUrl($this->check_out_photo);
    }

    private function resolvePhotoUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(public_path('uploads/' . $path))) {
            return asset('uploads/' . $path);
        }

        return asset('storage/' . $path);
    }

    /**
     * Google Maps link for Check-In Location
     */
    public function getCheckInMapUrlAttribute(): ?string
    {
        if (empty($this->location)) {
            return null;
        }
        return 'https://www.google.com/maps?q=' . urlencode(trim($this->location));
    }

    /**
     * Google Maps link for Check-Out Location
     */
    public function getCheckOutMapUrlAttribute(): ?string
    {
        $loc = $this->location_out ?: $this->location;
        if (empty($loc)) {
            return null;
        }
        return 'https://www.google.com/maps?q=' . urlencode(trim($loc));
    }

    /**
     * Hitung durasi lama kerja dari jam datang hingga jam pulang.
     */
    public function getWorkDurationAttribute(): string
    {
        if (!$this->check_in_time) {
            return '-';
        }

        if (!$this->check_out_time) {
            return 'Sedang Berlangsung';
        }

        try {
            $in = Carbon::parse($this->date . ' ' . $this->check_in_time);
            $out = Carbon::parse($this->date . ' ' . $this->check_out_time);

            if ($out->lessThan($in)) {
                $out->addDay();
            }

            $totalMinutes = $in->diffInMinutes($out);
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            if ($hours > 0 && $minutes > 0) {
                return "{$hours} Jam {$minutes} Menit";
            } elseif ($hours > 0) {
                return "{$hours} Jam";
            } else {
                return "{$minutes} Menit";
            }
        } catch (\Exception $e) {
            return '-';
        }
    }
}
