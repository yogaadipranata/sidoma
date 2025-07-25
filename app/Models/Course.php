<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Course extends Model
{
    use HasFactory;
    protected $fillable = [ 'course_code', 'course_name', 'credits', ];

    public function grades() { return $this->hasMany(Grade::class); }
    
    public function enrollments() { return $this->hasMany(Enrollment::class); }

    // Relasi baru untuk Jadwal: Satu Mata Kuliah bisa memiliki banyak jadwal (jika ada di beberapa semester/hari)
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}