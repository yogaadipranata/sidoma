<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_id',
        'lecturer_id',
        'grade',
        'letter_grade',
        'notes',
    ];
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function lecturer() { return $this->belongsTo(User::class, 'lecturer_id'); }
}
