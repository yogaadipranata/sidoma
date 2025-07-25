<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [ 'name', 'email', 'password', 'role', 'phone_number', 'address', 'nim', 'nidn', ];

    protected $hidden = [ 'password', 'remember_token', ];

    protected $casts = [ 'email_verified_at' => 'datetime', 'password' => 'hashed', ];

    public function grades() { return $this->hasMany(Grade::class, 'student_id'); }

    public function givenGrades() { return $this->hasMany(Grade::class, 'lecturer_id'); }

    // Mahasiswa memiliki banyak pendaftaran mata kuliah
    public function enrollments() { return $this->hasMany(Enrollment::class, 'student_id'); }
}
