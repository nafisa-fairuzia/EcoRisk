<?php

namespace Tests\Feature;

use App\Models\PickupLog;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfficerPickupControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_officer_sees_waiting_reports_ordered_by_risk_score(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $lowRiskReport = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Laporan prioritas rendah',
            'description' => 'Butuh pengangkutan',
            'status' => 'reported',
        ]);
        $highRiskReport = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Laporan prioritas tinggi',
            'description' => 'Dekat sungai',
            'is_near_river' => true,
            'status' => 'approved',
        ]);
        WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Laporan berjalan',
            'description' => 'Sudah mulai diangkut',
            'status' => 'in_process',
        ]);

        $this->actingAs($officer)
            ->get(route('petugas.pickups.index'))
            ->assertOk()
            ->assertViewIs('officer.pickups.index')
            ->assertViewHas('reports', function ($reports) use ($highRiskReport, $lowRiskReport): bool {
                return $reports->pluck('id')->all() === [
                    $highRiskReport->id,
                    $lowRiskReport->id,
                ];
            })
            ->assertViewHas('inProcessReports', fn($reports): bool => $reports->count() === 1);
    }

    public function test_pickup_badge_clears_after_viewing_radar_and_returns_for_new_reports(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $reporter = User::factory()->create(['role' => 'warga']);
        WasteReport::create([
            'user_id' => $reporter->id,
            'address' => 'Lokasi laporan pertama',
            'description' => 'Laporan pertama',
            'status' => 'reported',
        ]);

        $this->actingAs($officer)
            ->get(route('petugas.dashboard'))
            ->assertOk()
            ->assertSee('1 laporan baru');

        $this->get(route('petugas.pickups.index'))
            ->assertOk()
            ->assertDontSee('laporan baru');

        $this->get(route('petugas.dashboard'))
            ->assertOk()
            ->assertDontSee('laporan baru');

        WasteReport::create([
            'user_id' => $reporter->id,
            'address' => 'Lokasi laporan kedua',
            'description' => 'Laporan baru setelah radar dibuka',
            'status' => 'reported',
        ]);

        $this->get(route('petugas.dashboard'))
            ->assertOk()
            ->assertSee('1 laporan baru');
    }

    public function test_officer_can_open_prioritized_route_as_a_separate_page(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Laporan dengan koordinat',
            'status' => 'reported',
            'latitude' => -6.2,
            'longitude' => 106.8,
        ]);

        $this->actingAs($officer)
            ->get(route('petugas.routes.index'))
            ->assertOk()
            ->assertViewIs('officer.routes.index')
            ->assertSee('Rute Pengangkutan')
            ->assertSee('Buka navigasi')
            ->assertSee('google.com/maps/dir/', false)
            ->assertViewHas('routeReports', fn($reports): bool => $reports->count() === 1);
    }

    public function test_starting_pickup_updates_report_and_creates_officer_log(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $report = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Perlu diangkut',
            'status' => 'reported',
        ]);

        $this->actingAs($officer)
            ->from(route('petugas.pickups.index'))
            ->patch(route('petugas.pickups.update-status', $report->id), [
                'status' => 'in_process',
            ])
            ->assertRedirect(route('petugas.pickups.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'in_process',
        ]);
        $this->assertDatabaseHas('pickup_logs', [
            'waste_report_id' => $report->id,
            'officer_id' => $officer->id,
            'status' => 'in_process',
        ]);
        $this->assertNotNull(PickupLog::query()->firstOrFail()->pickup_time);
    }

    public function test_completing_pickup_updates_existing_log_without_creating_another(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $report = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Sedang diangkut',
            'status' => 'in_process',
        ]);
        PickupLog::create([
            'waste_report_id' => $report->id,
            'officer_id' => $officer->id,
            'pickup_time' => now(),
            'status' => 'in_process',
        ]);

        $this->actingAs($officer)
            ->patch(route('petugas.pickups.update-status', $report->id), [
                'status' => 'completed',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('pickup_logs', [
            'waste_report_id' => $report->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseCount('pickup_logs', 1);
    }
}
