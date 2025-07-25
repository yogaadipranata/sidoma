<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data mata kuliah dari database
        $courses = Course::all();
        // Tampilkan view 'courses.index' dan kirim data mata kuliah
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan view formulir untuk menambah mata kuliah baru
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'course_code' => ['required', 'string', 'max:255', Rule::unique(Course::class)], // Kode unik
            'course_name' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1', 'max:6'], // SKS antara 1-6
        ]);

        // Buat mata kuliah baru di database
        Course::create($validated);

        // Redirect kembali ke halaman daftar mata kuliah dengan pesan sukses
        return redirect()->route('courses.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return redirect()->route('courses.edit', $course)->with('info', 'Anda bisa mengedit mata kuliah ini.');
    }

    public function edit(Course $course)
    {
        // Tampilkan view formulir untuk mengedit mata kuliah
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'course_code' => ['required', 'string', 'max:255', Rule::unique(Course::class)->ignore($course->id)],
            'course_name' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        // Update mata kuliah di database
        $course->update($validated);

        // Redirect kembali ke halaman daftar mata kuliah dengan pesan sukses
        return redirect()->route('courses.index')->with('success', 'Mata Kuliah berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        // Hapus mata kuliah dari database
        $course->delete();

        // Redirect kembali ke halaman daftar mata kuliah dengan pesan sukses
        return redirect()->route('courses.index')->with('success', 'Mata Kuliah berhasil dihapus!');
    }
}
