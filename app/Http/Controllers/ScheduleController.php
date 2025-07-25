<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal yang ada. (Untuk Dosen)
     */
    public function index()
    {
        $schedules = Schedule::with('course')->get(); // Ambil semua jadwal dengan info mata kuliah
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        $courses = Course::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $academicYears = ['2025/2026'];
        $semesters = ['Ganjil'];

        return view('schedules.create', compact('courses', 'days', 'academicYears', 'semesters'));
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'day' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat'],
            'start_time' => ['required', 'date_format:H:i'], // Format jam:menit (contoh: 08:00)
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'], // Harus setelah waktu mulai
            'room' => ['required', 'string', 'max:50'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'string', 'max:20'],
        ]);

        // Cek unik untuk mencegah duplikasi jadwal (walaupun tidak ada validasi bentrok kompleks)
        $existingSchedule = Schedule::where('course_id', $validated['course_id'])
                                    ->where('day', $validated['day'])
                                    ->where('start_time', $validated['start_time'])
                                    ->where('academic_year', $validated['academic_year'])
                                    ->where('semester', $validated['semester'])
                                    ->first();
        if ($existingSchedule) {
            return redirect()->back()->with('error', 'Jadwal untuk mata kuliah ini pada waktu yang sama sudah ada.');
        }

        Schedule::create($validated);

        return redirect()->route('dosen.schedules_index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit jadwal.
     */
    public function edit(Schedule $schedule)
    {
        $courses = Course::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $academicYears = ['2025/2026'];
        $semesters = ['Ganjil'];

        return view('schedules.edit', compact('schedule', 'courses', 'days', 'academicYears', 'semesters'));
    }

    /**
     * Mengupdate jadwal.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'day' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['required', 'string', 'max:50'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'string', 'max:20'],
        ]);

        // Cek unik untuk mencegah duplikasi jadwal saat update (abaikan jadwal yang sedang diedit)
        $existingSchedule = Schedule::where('course_id', $validated['course_id'])
                                    ->where('day', $validated['day'])
                                    ->where('start_time', $validated['start_time'])
                                    ->where('academic_year', $validated['academic_year'])
                                    ->where('semester', $validated['semester'])
                                    ->where('id', '!=', $schedule->id) // Abaikan ID jadwal yang sedang diedit
                                    ->first();
        if ($existingSchedule) {
            return redirect()->back()->with('error', 'Jadwal untuk mata kuliah ini pada waktu yang sama sudah ada.');
        }

        $schedule->update($validated);

        return redirect()->route('dosen.schedules_index')->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Menghapus jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('dosen.schedules_index')->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Menampilkan jadwal kuliah untuk mahasiswa yang sedang login.
     * Ini akan menampilkan jadwal dari mata kuliah yang sudah disetujui di KRS, dikelompokkan per hari.
     */
    public function showStudentSchedule()
    {
        $student = Auth::user();

        $currentAcademicYear = '2025/2026';
        $currentSemester = 'Ganjil';

        // Ambil ID mata kuliah yang sudah disetujui di KRS mahasiswa untuk semester ini
        $approvedEnrollmentCourseIds = $student->enrollments()
                                               ->where('academic_year', $currentAcademicYear)
                                               ->where('semester', $currentSemester)
                                               ->where('is_approved', true) // Hanya yang sudah disetujui
                                               ->pluck('course_id');

        // Ambil jadwal untuk ID mata kuliah tersebut, dan kelompokkan berdasarkan hari
        $schedulesGroupedByDay = Schedule::whereIn('course_id', $approvedEnrollmentCourseIds)
                                        ->where('academic_year', $currentAcademicYear)
                                        ->where('semester', $currentSemester)
                                        ->with('course')
                                        ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
                                        ->orderBy('start_time')
                                        ->get()
                                        ->groupBy('day');

        // Kirim data jadwal yang sudah dikelompokkan
        return view('schedules.student_schedule', compact('schedulesGroupedByDay', 'currentAcademicYear', 'currentSemester'));
    }
}