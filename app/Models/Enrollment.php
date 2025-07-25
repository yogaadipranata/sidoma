<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'academic_year',
        'semester',
        'is_approved',
        'rejection_reason',
    ];

    // Relasi: Pendaftaran ini milik satu Mahasiswa
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relasi: Pendaftaran ini untuk satu Mata Kuliah
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}