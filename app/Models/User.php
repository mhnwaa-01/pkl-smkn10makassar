<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isIndustri(): bool
    {
        return $this->role === 'industri';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function industry()
    {
        return $this->hasOne(Industry::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
