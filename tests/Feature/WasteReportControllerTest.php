<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WasteReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_menu_sections_show_only_the_selected_page(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        WasteReport::create([
            'user_id' => $warga->id,
            'kode_laporan' => 'LPR-2026-777',
            'alamat' => 'Jalan Uji RT 01 / RW 02',
            'jenis_tumpukan' => 'Sampah campuran',
            'deskripsi' => 'Laporan contoh warga',
            'address' => 'Jalan Uji RT 01 / RW 02',
            'description' => 'Laporan contoh warga',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Warga')
            ->assertDontSee('Buat Pengaduan Baru');

        $this->get(route('warga.dashboard', ['section' => 'create']))
            ->assertOk()
            ->assertSee('Buat Laporan Risiko')
            ->assertSee('Buat Pengaduan Baru')
            ->assertDontSee('LPR-2026-777');

        $this->get(route('warga.dashboard', ['section' => 'reports']))
            ->assertOk()
            ->assertSee('Status & Riwayat Aduan')
            ->assertSee('Riwayat Aduan Saya')
            ->assertSee('LPR-2026-777')
            ->assertDontSee('Buat Pengaduan Baru');
    }

    public function test_index_shows_active_reports_in_risk_score_order_to_officers(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $lowerRiskReport = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Alamat risiko rendah',
            'description' => 'Laporan aktif',
            'status' => 'reported',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'is_near_river' => false,
        ]);
        $higherRiskReport = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Alamat risiko tinggi',
            'description' => 'Laporan prioritas',
            'status' => 'in_process',
            'latitude' => -6.3,
            'longitude' => 106.9,
            'is_near_river' => true,
            'is_dense_populated' => true,
        ]);
        WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Alamat selesai',
            'description' => 'Laporan selesai',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($officer)->get(route('reports.index'));

        $response->assertOk()
            ->assertViewIs('reports.index')
            ->assertViewHas('reports', function ($reports) use ($higherRiskReport, $lowerRiskReport): bool {
                return $reports->pluck('id')->all() === [
                    $higherRiskReport->id,
                    $lowerRiskReport->id,
                ];
            })
            ->assertViewHas('mapPoints', fn($mapPoints): bool => $mapPoints->count() === 2);
    }

    public function test_admin_can_view_risk_summary_grouped_by_area(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Lokasi risiko tinggi',
            'description' => 'Laporan aktif di RW 04',
            'status' => 'reported',
            'rw' => '04',
            'is_near_river' => true,
            'is_dense_populated' => true,
            'history_burned' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.heatmap'))
            ->assertOk()
            ->assertViewIs('reports.index')
            ->assertSee('Heatmap Risiko Wilayah')
            ->assertSee('data-risk-map', false)
            ->assertSee('Peta titik risiko')
            ->assertDontSee('Koordinat')
            ->assertSee('RW 04')
            ->assertSee('Prioritas tinggi/kritis')
            ->assertViewHas('riskZones', fn($zones): bool => $zones->first()['reports_count'] === 1)
            ->assertViewHas('riskSummary', fn(array $summary): bool => $summary['high_priority_reports'] === 1);
    }

    public function test_warga_can_store_report_with_uploaded_photo_and_calculated_priority(): void
    {
        Storage::fake('public');
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $photo = UploadedFile::fake()->image('report.jpg');

        $response = $this->actingAs($warga)
            ->from(route('home'))
            ->post(route('reports.store'), [
                'photo' => $photo,
                'latitude' => '-6.2',
                'longitude' => '106.8',
                'address' => 'Jalan Contoh 10',
                'description' => 'Sampah dekat sungai',
                'is_near_river' => '1',
                'is_dense_populated' => '1',
                'history_burned' => '0',
            ]);

        $response->assertRedirect(route('home'))
            ->assertSessionHas('success');

        $report = WasteReport::query()->firstOrFail();

        $this->assertSame($warga->id, $report->user_id);
        $this->assertSame('reported', $report->status);
        $this->assertSame('50.00', $report->risk_score);
        $this->assertSame('medium', $report->priority_level);
        $this->assertTrue(Storage::disk('public')->exists($report->photo));
    }

    public function test_invalid_coordinates_do_not_create_report(): void
    {
        Storage::fake('public');
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);

        $response = $this->actingAs($warga)
            ->from(route('home'))
            ->post(route('reports.store'), [
                'photo' => UploadedFile::fake()->image('report.jpg'),
                'latitude' => '120',
                'longitude' => '106.8',
                'address' => 'Jalan Contoh 10',
                'description' => 'Laporan tidak valid',
            ]);

        $response->assertRedirect(route('home'))
            ->assertSessionHasErrors('latitude');
        $this->assertDatabaseCount('waste_reports', 0);
    }
}
