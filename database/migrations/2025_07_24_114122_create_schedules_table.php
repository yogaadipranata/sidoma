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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // Mata Kuliah yang dijadwalkan
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');

            // Hari (contoh: Senin, Selasa)
            $table->string('day');

            // Waktu mulai (contoh: 08:00:00)
            $table->time('start_time');

            // Waktu selesai (contoh: 10:00:00)
            $table->time('end_time');

            // Ruangan (contoh: A101)
            $table->string('room');

            // Tahun Akademik (misal: 2025/2026)
            $table->string('academic_year');

            // Semester (misal: Ganjil)
            $table->string('semester');      
            $table->timestamps();

            // Satu mata kuliah hanya punya satu jadwal di hari dan waktu tertentu per semester
            $table->unique(['course_id', 'day', 'start_time', 'academic_year', 'semester'], 'unique_course_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};