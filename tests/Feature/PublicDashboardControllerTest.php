<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteReport;
use App\Models\WasteSorting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_environmental_totals_channel_shares_and_recent_handled_locations(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $handledReport = new WasteReport([
            'user_id' => $warga->id,
            'address' => "Jalan <script>alert('xss')</script>",
            'description' => 'Laporan selesai dan dipilah',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'is_near_river' => true,
            'is_dense_populated' => true,
            'history_burned' => true,
            'status' => 'completed',
        ]);
        $handledReport->created_at = now()->subDays(2);
        $handledReport->save();
        WasteSorting::create([
            'waste_report_id' => $handledReport->id,
            'organic_weight_kg' => 10,
            'inorganic_weight_kg' => 0,
            'paper_weight_kg' => 0,
            'channel_type' => 'maggot_bsf',
            'prevented_burning_risk' => true,
        ]);
        WasteSorting::create([
            'waste_report_id' => WasteReport::create([
                'user_id' => $warga->id,
                'address' => 'Jalan Daur Ulang 2',
                'description' => 'Laporan selesai dan dipilah',
                'status' => 'completed',
            ])->id,
            'organic_weight_kg' => 0,
            'inorganic_weight_kg' => 5,
            'paper_weight_kg' => 0,
            'channel_type' => 'waste_bank',
            'prevented_burning_risk' => false,
        ]);
        WasteSorting::create([
            'waste_report_id' => WasteReport::create([
                'user_id' => $warga->id,
                'address' => 'Jalan Kompos 3',
                'description' => 'Laporan selesai dan dipilah',
                'status' => 'completed',
            ])->id,
            'organic_weight_kg' => 0,
            'inorganic_weight_kg' => 0,
            'paper_weight_kg' => 3,
            'channel_type' => 'compost_community',
            'prevented_burning_risk' => true,
        ]);
        WasteReport::create([
            'user_id' => $warga->id,
            'address' => 'Laporan belum selesai',
            'description' => 'Tidak termasuk daftar penanganan selesai',
            'status' => 'in_process',
        ]);

        $this->get(route('public.dashboard'))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert', false)
            ->assertViewIs('public.dashboard')
            ->assertViewHas('totalWeightKg', 18.0)
            ->assertViewHas('totalWeightTon', 0.018)
            ->assertViewHas('preventedEmissionsKg', 27.0)
            ->assertViewHas('preventedBurningCount', 2)
            ->assertViewHas('channelDistribution', function ($distribution): bool {
                return $distribution->get('maggot_bsf')['percentage'] === 55.56
                    && $distribution->get('compost_community')['percentage'] === 16.67
                    && $distribution->get('waste_bank')['percentage'] === 27.78
                    && $distribution->get('recycling_umkm')['percentage'] === 0;
            })
            ->assertViewHas('recentHandledReports', function ($reports) use ($handledReport): bool {
                return $reports->count() === 3
                    && $reports->contains(fn (WasteReport $report): bool => $report->is($handledReport)
                        && $report->priority_level === 'critical');
            });
    }

    public function test_dashboard_shows_only_the_five_most_recent_handled_reports(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);

        for ($index = 1; $index <= 6; $index++) {
            $report = WasteReport::create([
                'user_id' => $warga->id,
                'address' => "Lokasi {$index}",
                'description' => 'Laporan selesai',
                'status' => 'completed',
            ]);

            DB::table('waste_reports')
                ->where('id', $report->id)
                ->update(['updated_at' => now()->addMinutes($index)]);

            WasteSorting::create([
                'waste_report_id' => $report->id,
                'organic_weight_kg' => 0,
                'inorganic_weight_kg' => 0,
                'paper_weight_kg' => 0,
                'channel_type' => null,
                'prevented_burning_risk' => false,
            ]);
        }

        $this->get(route('public.dashboard'))
            ->assertOk()
            ->assertViewHas('recentHandledReports', function ($reports): bool {
                return $reports->count() === 5
                    && $reports->first()->address === 'Lokasi 6';
            });
    }
}
