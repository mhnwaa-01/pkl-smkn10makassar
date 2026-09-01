<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'attendance_settings';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'check_in_start',
        'check_in_late_time',
        'check_out_time',
        'check_out_early_time',
    ];

    public static function getSetting()
    {
        $setting = self::first();
        if (!$setting) {
            $setting = self::create([
                'check_in_start' => '06:00:00',
                'check_in_late_time' => '08:00:00',
                'check_out_time' => '16:00:00',
                'check_out_early_time' => '15:30:00',
            ]);
        }
        return $setting;
    }
}
