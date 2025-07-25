
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
        Schema::table('users', function (Blueprint $table) {
            // Kolom umum
            $table->string('phone_number')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone_number');

            // Kolom spesifik role
            $table->string('nim')->nullable()->unique()->after('address'); // Nomor Induk Mahasiswa
            $table->string('nidn')->nullable()->unique()->after('address'); // Nomor Induk Dosen Nasional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'address', 'nim', 'nidn']);
        });
    }
};