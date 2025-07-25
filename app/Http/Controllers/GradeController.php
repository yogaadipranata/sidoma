<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    /**
     * Menampilkan formulir untuk dosen menginput nilai.
     */
    public function inputGradeForm()
    {
        $courses = Course::all();
        $students = User::where('role', 'mahasiswa')->get();
        return view('grades.input', compact('courses', 'students'));
    }

    /**
     * Menyimpan nilai yang diinput oleh dosen.
     */
    public function storeGrade(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'grade' => ['required', 'integer', 'min:0', 'max:100'],
            'letter_grade' => ['nullable', 'string', 'max:2'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['lecturer_id'] = Auth::id();
        $validated['letter_grade'] = $this->calculateLetterGrade($validated['grade']);

        $existingGrade = Grade::where('student_id', $validated['student_id'])
                              ->where('course_id', $validated['course_id'])
                              ->first();

        if ($existingGrade) {
            $existingGrade->update($validated);
            $message = 'Nilai berhasil diupdate!';
        } else {
            Grade::create($validated);
            $message = 'Nilai berhasil ditambahkan!';
        }

        return redirect()->route('dosen.input_nilai')->with('success', $message);
    }

    /**
     * Menampilkan transkrip nilai untuk mahasiswa yang sedang login.
     */
    public function showTranskrip() // <---- PASTIKAN METHOD INI ADA
    {
        $student = Auth::user(); // Dapatkan user mahasiswa yang sedang login
        // Ambil semua nilai mahasiswa ini, dengan relasi ke mata kuliah dan dosen
        $grades = $student->grades()->with(['course', 'lecturer'])->get();

        return view('grades.transkrip', compact('student', 'grades'));
    }

    /**
     * Fungsi helper untuk mengkonversi nilai angka ke nilai huruf.
     */
    private function calculateLetterGrade($grade)
    {
        if ($grade >= 80) {
            return 'A';
        } elseif ($grade >= 75) {
            return 'B+';
        } elseif ($grade >= 70) {
            return 'B';
        } elseif ($grade >= 65) {
            return 'C+';
        } elseif ($grade >= 60) {
            return 'C';
        } elseif ($grade >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }
}