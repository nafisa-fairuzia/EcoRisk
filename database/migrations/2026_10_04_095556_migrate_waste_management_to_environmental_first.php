<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['warga', 'petugas', 'admin', 'mitra_daur_ulang'])
                ->default('warga')
                ->change();
        });

        Schema::table('waste_categories', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->enum('type', ['organik', 'anorganik', 'kertas'])->nullable();
            $table->decimal('carbon_offset_factor_per_kg', 10, 6)->nullable();
        });

        DB::table('waste_categories')->update([
            'name' => DB::raw('nama'),
            'type' => DB::raw("CASE WHEN kelompok = 'Organik' THEN 'organik' ELSE 'anorganik' END"),
            'carbon_offset_factor_per_kg' => 0,
        ]);

        Schema::table('waste_categories', function (Blueprint $table) {
            $table->string('kode', 20)->nullable()->change();
            $table->string('nama')->nullable()->change();
            $table->enum('kelompok', ['Organik', 'Anorganik Daur Ulang', 'Residu / B3'])
                ->nullable()
                ->change();
            $table->text('instruksi_penanganan')->nullable()->change();
            $table->string('name')->nullable(false)->change();
            $table->enum('type', ['organik', 'anorganik', 'kertas'])->nullable(false)->change();
            $table->decimal('carbon_offset_factor_per_kg', 10, 6)->default(0)->change();
            $table->index('type', 'waste_categories_type_index');
        });

        Schema::table('waste_reports', function (Blueprint $table) {
            $table->renameColumn('status', 'legacy_status');
        });

        Schema::table('waste_reports', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_near_river')->default(false);
            $table->boolean('is_dense_populated')->default(false);
            $table->boolean('history_burned')->default(false);
            $table->decimal('risk_score', 8, 2)->default(0);
            $table->enum('status', ['reported', 'approved', 'in_process', 'completed', 'rejected'])
                ->default('reported');
            $table->enum('priority_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description')->nullable();
        });

        DB::table('waste_reports')->update([
            'address' => DB::raw('alamat'),
            'photo' => DB::raw('foto_url'),
            'description' => DB::raw('deskripsi'),
        ]);

        DB::table('waste_reports')
            ->where('legacy_status', 'menunggu')
            ->update(['status' => 'reported']);
        DB::table('waste_reports')
            ->where('legacy_status', 'proses')
            ->update(['status' => 'in_process']);
        DB::table('waste_reports')
            ->where('legacy_status', 'selesai')
            ->update(['status' => 'completed']);
        DB::table('waste_reports')
            ->where('legacy_status', 'ditolak')
            ->update(['status' => 'rejected']);

        DB::table('waste_reports')->where('urgensi', 'rendah')->update(['priority_level' => 'low']);
        DB::table('waste_reports')->where('urgensi', 'sedang')->update(['priority_level' => 'medium']);
        DB::table('waste_reports')->where('urgensi', 'tinggi')->update(['priority_level' => 'high']);

        Schema::table('waste_reports', function (Blueprint $table) {
            $table->string('kode_laporan')->nullable()->change();
            $table->string('nama_pelapor')->nullable()->change();
            $table->string('telepon_pelapor')->nullable()->change();
            $table->string('alamat')->nullable()->change();
            $table->string('rt', 5)->nullable()->change();
            $table->string('rw', 5)->nullable()->change();
            $table->string('jenis_tumpukan')->nullable()->change();
            $table->text('deskripsi')->nullable()->change();
            $table->text('description')->nullable(false)->change();
            $table->index('status', 'waste_reports_status_index');
            $table->index('risk_score', 'waste_reports_risk_score_index');
            $table->index('priority_level', 'waste_reports_priority_level_index');
        });

        Schema::create('report_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_report_id')->constrained('waste_reports')->cascadeOnDelete();
            $table->foreignId('waste_category_id')->constrained('waste_categories')->restrictOnDelete();
            $table->decimal('estimated_weight_kg', 10, 2)->unsigned();
            $table->timestamps();
            $table->unique(
                ['waste_report_id', 'waste_category_id'],
                'report_details_report_category_unique'
            );
        });

        Schema::create('pickup_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_report_id')->constrained('waste_reports')->cascadeOnDelete();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('pickup_time')->nullable();
            $table->enum('status', ['pending', 'assigned', 'in_process', 'completed', 'cancelled'])
                ->default('pending');
            $table->timestamps();
            $table->index(['status', 'pickup_time'], 'pickup_logs_status_time_index');
        });

        Schema::create('waste_sortings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_report_id')
                ->unique()
                ->constrained('waste_reports')
                ->cascadeOnDelete();
            $table->decimal('organic_weight_kg', 10, 2)->unsigned()->default(0);
            $table->decimal('inorganic_weight_kg', 10, 2)->unsigned()->default(0);
            $table->decimal('paper_weight_kg', 10, 2)->unsigned()->default(0);
            $table->enum('channel_type', [
                'maggot_bsf',
                'compost_community',
                'waste_bank',
                'recycling_umkm',
            ])->nullable();
            $table->decimal('prevented_carbon_emissions_kg', 12, 3)->unsigned()->default(0);
            $table->boolean('prevented_burning_risk')->default(false);
            $table->timestamps();
        });

        Schema::create('environmental_impact_summaries', function (Blueprint $table) {
            $table->id();
            $table->enum('period_type', ['daily', 'weekly', 'monthly']);
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedInteger('reports_count')->default(0);
            $table->unsignedInteger('completed_reports_count')->default(0);
            $table->unsignedInteger('critical_reports_count')->default(0);
            $table->unsignedInteger('prevented_burning_count')->default(0);
            $table->decimal('total_waste_weight_kg', 14, 2)->unsigned()->default(0);
            $table->decimal('prevented_carbon_emissions_kg', 14, 3)->unsigned()->default(0);
            $table->timestamps();
            $table->unique(
                ['period_type', 'period_start'],
                'environmental_impact_period_unique'
            );
            $table->index('period_start', 'environmental_impact_period_start_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environmental_impact_summaries');
        Schema::dropIfExists('waste_sortings');
        Schema::dropIfExists('pickup_logs');
        Schema::dropIfExists('report_details');

        DB::table('waste_reports')->update([
            'deskripsi' => DB::raw('description'),
            'alamat' => DB::raw('address'),
            'foto_url' => DB::raw('photo'),
        ]);
        DB::table('waste_reports')->where('status', 'reported')->update(['legacy_status' => 'menunggu']);
        DB::table('waste_reports')->where('status', 'approved')->update(['legacy_status' => 'menunggu']);
        DB::table('waste_reports')->where('status', 'in_process')->update(['legacy_status' => 'proses']);
        DB::table('waste_reports')->where('status', 'completed')->update(['legacy_status' => 'selesai']);
        DB::table('waste_reports')->where('status', 'rejected')->update(['legacy_status' => 'ditolak']);

        DB::table('waste_reports')->where('priority_level', 'low')->update(['urgensi' => 'rendah']);
        DB::table('waste_reports')->where('priority_level', 'medium')->update(['urgensi' => 'sedang']);
        DB::table('waste_reports')
            ->whereIn('priority_level', ['high', 'critical'])
            ->update(['urgensi' => 'tinggi']);

        Schema::table('waste_reports', function (Blueprint $table) {
            $table->dropIndex('waste_reports_status_index');
            $table->dropIndex('waste_reports_risk_score_index');
            $table->dropIndex('waste_reports_priority_level_index');
            $table->dropColumn([
                'address',
                'photo',
                'latitude',
                'longitude',
                'is_near_river',
                'is_dense_populated',
                'history_burned',
                'risk_score',
                'status',
                'priority_level',
                'description',
            ]);
            $table->renameColumn('legacy_status', 'status');
        });

        DB::table('waste_categories')->whereNull('nama')->update(['nama' => DB::raw('name')]);
        DB::table('waste_categories')->whereNull('kelompok')->update([
            'kelompok' => DB::raw(
                "CASE WHEN type = 'organik' THEN 'Organik' ELSE 'Anorganik Daur Ulang' END"
            ),
        ]);

        Schema::table('waste_categories', function (Blueprint $table) {
            $table->dropIndex('waste_categories_type_index');
            $table->dropColumn(['name', 'type', 'carbon_offset_factor_per_kg']);
        });

        DB::table('users')->where('role', 'mitra_daur_ulang')->update(['role' => 'warga']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas', 'warga'])->default('warga')->change();
        });
    }
};
