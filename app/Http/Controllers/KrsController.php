<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KrsController extends Controller
{
    // Menampilkan formulir KRS untuk mahasiswa.
    public function showKrsForm()
    {
        // Dapatkan user mahasiswa yang sedang login
        $student = Auth::user();

        // Ambil semua mata kuliah yang tersedia
        $availableCourses = Course::all();

        // Asumsikan semester dan tahun akademik saat ini (bisa dinamis di real project)
        $currentAcademicYear = '2025/2026';
        $currentSemester = 'Ganjil';

        // Ambil mata kuliah yang sudah diambil mahasiswa ini untuk semester & tahun tertentu
        $enrolledCourses = $student->enrollments()
                                   ->where('academic_year', $currentAcademicYear)
                                   ->where('semester', $currentSemester)
                                   ->with('course')
                                   ->get();

        return view('krs.form', compact('student', 'availableCourses', 'enrolledCourses', 'currentAcademicYear', 'currentSemester'));
    }

    // Menyimpan pilihan mata kuliah KRS mahasiswa.
    public function storeKrs(Request $request)
    {
        $student = Auth::user();
        $currentAcademicYear = '2025/2026';
        $currentSemester = 'Ganjil';

        $validated = $request->validate([
            // Array ID mata kuliah yang dipilih
            'selected_courses' => ['nullable', 'array'],

             // Setiap ID harus ada di tabel courses
            'selected_courses.*' => ['exists:courses,id'],
        ]);

        $selectedCourseIds = $validated['selected_courses'] ?? [];

        // Hapus pendaftaran yang TIDAK dipilih lagi untuk semester ini
        // Ini penting untuk menangani jika mahasiswa membatalkan pilihan mata kuliah
        Enrollment::where('student_id', $student->id)
                  ->where('academic_year', $currentAcademicYear)
                  ->where('semester', $currentSemester)
                  ->where('is_approved', false)
                  ->whereNull('rejection_reason')
                  ->whereNotIn('course_id', $selectedCourseIds)
                  ->delete();
        
        $existingEnrollments = Enrollment::where('student_id', $student->id)
                                    ->where('academic_year', $currentAcademicYear)
                                    ->where('semester', $currentSemester)
                                    ->get()
                                    ->keyBy('course_id');

        // Tambahkan pendaftaran baru atau update jika sudah ada
        foreach ($selectedCourseIds as $courseId) {

            // Coba temukan pendaftaran yang sudah ada untuk mahasiswa, mata kuliah, tahun, dan semester ini
            $enrollment = Enrollment::firstOrNew(
                [
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'academic_year' => $currentAcademicYear,
                    'semester' => $currentSemester
                ]
            );

            // Jika pendaftaran baru atau pendaftaran yang ada belum disetujui/ditolak, set status menunggu
            if (!$enrollment->exists || ($enrollment->exists && !$enrollment->is_approved && is_null($enrollment->rejection_reason))) {
                $enrollment->is_approved = false;
                $enrollment->rejection_reason = null;
                $enrollment->save();
            }
        }

        return redirect()->route('mahasiswa.krs_form')->with('success', 'KRS berhasil disimpan! Menunggu persetujuan dosen.');
    }

    /**
     * Menampilkan daftar KRS yang menunggu validasi oleh dosen.
     */
    public function showValidationList()
    {
        // Tampilkan yang 'is_approved' false DAN 'rejection_reason' NULL (menunggu)
        $pendingKrs = Enrollment::where('is_approved', false)
                                ->whereNull('rejection_reason')
                                ->with(['student', 'course'])
                                ->get();

        return view('krs.validation_list', compact('pendingKrs'));
    }

    /**
     * Mengubah status validasi KRS oleh dosen.
     */
    public function validateKrs(Request $request, Enrollment $enrollment)
    {
        // Validasi input, tambahkan rejection_reason jika action adalah reject
        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'rejection_reason' => ['nullable', 'string', 'max:500'], // Field baru untuk alasan penolakan
        ]);

        if ($validated['action'] === 'approve') {
            $enrollment->is_approved = true;
            $enrollment->rejection_reason = null; // Hapus alasan penolakan jika disetujui
            $message = 'KRS mahasiswa ' . $enrollment->student->name . ' untuk ' . $enrollment->course->course_name . ' berhasil disetujui!';
        } else { // Jika action adalah 'reject'
            $enrollment->is_approved = false; // Status ditolak
            $enrollment->rejection_reason = $validated['rejection_reason']; // Simpan alasan penolakan
            $message = 'KRS mahasiswa ' . $enrollment->student->name . ' untuk ' . $enrollment->course->course_name . ' berhasil ditolak!';
        }

        $enrollment->save(); // Simpan perubahan status dan alasan penolakan

        return redirect()->route('dosen.krs_validation')->with('success', $message);
    }

    /**
     * Menghapus entri KRS tertentu (khususnya yang ditolak) oleh mahasiswa.
     */
    public function deleteKrs(Enrollment $enrollment)
    {
        // Pastikan mahasiswa yang login adalah pemilik KRS ini
        // dan KRS belum disetujui (hanya bisa hapus yang ditolak atau menunggu)
        if (Auth::id() === $enrollment->student_id && !$enrollment->is_approved) {
            $enrollment->delete();
            return redirect()->route('mahasiswa.krs_form')->with('success', 'Mata kuliah dari KRS berhasil dihapus!');
        }

        return redirect()->route('mahasiswa.krs_form')->with('error', 'Anda tidak diizinkan menghapus KRS yang sudah disetujui atau bukan milik Anda.');
    }
}