<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorting_records', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pilah')->unique();
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('report_id')->nullable()->constrained('waste_reports')->nullOnDelete();
            $table->date('tanggal');
            $table->string('lokasi_tps');
            $table->decimal('organik_kg', 8, 2)->default(0);
            $table->decimal('plastik_kg', 8, 2)->default(0);
            $table->decimal('kertas_karton_kg', 8, 2)->default(0);
            $table->decimal('logam_kaca_kg', 8, 2)->default(0);
            $table->decimal('residu_b3_kg', 8, 2)->default(0);
            $table->decimal('total_kg', 8, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorting_records');
    }
};
