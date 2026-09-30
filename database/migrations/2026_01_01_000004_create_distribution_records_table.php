<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribution_records', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan')->unique();
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('mitra_tujuan');
            $table->string('kategori_sampah');
            $table->decimal('berat_kg', 8, 2);
            $table->text('catatan')->nullable();
            $table->enum('status_serah_terima', ['Terkirim & Diterima', 'Dalam Perjalanan'])->default('Terkirim & Diterima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_records');
    }
};
