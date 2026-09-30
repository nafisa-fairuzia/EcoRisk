<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_reports', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // warga pelapor
            $table->string('nama_pelapor');
            $table->string('telepon_pelapor');
            $table->string('alamat');
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('patokan')->nullable();
            $table->string('jenis_tumpukan');
            $table->text('deskripsi');
            $table->string('foto_url')->nullable();
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['menunggu', 'proses', 'selesai', 'ditolak'])->default('menunggu');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_petugas')->nullable();
            $table->string('foto_selesai_url')->nullable();
            $table->timestamp('tanggal_update_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_reports');
    }
};
