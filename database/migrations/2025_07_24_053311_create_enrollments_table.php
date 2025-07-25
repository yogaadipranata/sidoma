<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // Mahasiswa yang mengambil MK
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            // Mata Kuliah yang diambil
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');

            // Tahun Akademik (contoh: 2024/2025)
            $table->string('academic_year');
            
            // Semester (contoh: Ganjil, Genap)
            $table->string('semester');

            // Status persetujuan, default false (menunggu)
            $table->boolean('is_approved')->default(false); 
            $table->timestamps();

            // Mahasiswa hanya bisa mengambil mata kuliah yang sama sekali per semester/tahun
            $table->unique(['student_id', 'course_id', 'academic_year', 'semester'], 'unique_enrollment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};