<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'nisn',
        'name',
        'class_name',
        'major_id',
        'teacher_id',
        'industry_id',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function evaluation()
    {
        return $this->hasOne(PklEvaluation::class);
    }
}
