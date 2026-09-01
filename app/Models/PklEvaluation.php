<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklEvaluation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pkl_evaluations';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'student_id',
        'industry_id',
        'evaluator_user_id',
        'aspect_attitude',
        'aspect_technical',
        'aspect_managerial',
        'aspect_report',
        'aspect_presentation',
        'final_score',
        'predicate',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }
}
