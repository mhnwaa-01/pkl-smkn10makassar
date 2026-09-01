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
}
