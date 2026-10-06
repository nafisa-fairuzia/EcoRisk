<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteReport;
use App\Models\WasteSorting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WasteSortingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_officer_can_open_the_separate_sorting_queue(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $report = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Menunggu pencatatan pemilahan',
            'status' => 'completed',
        ]);

        $this->actingAs($officer)
            ->get(route('petugas.sortings.index'))
            ->assertOk()
            ->assertViewIs('officer.sortings.index')
            ->assertSee('Antrean Pemilahan')
            ->assertSee('Jalan Contoh 10')
            ->assertViewHas('reports', fn($reports): bool => $reports->contains('id', $report->id));
    }

    public function test_officer_can_open_sorting_form_for_an_active_pickup_report(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $report = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Sudah diangkut',
            'status' => 'in_process',
        ]);

        $this->actingAs($officer)
            ->get(route('petugas.sortings.create', $report->id))
            ->assertOk()
            ->assertViewIs('officer.sortings.create')
            ->assertViewHas(
                'report',
                fn(WasteReport $viewReport): bool => $viewReport->is($report)
            );
    }

    public function test_officer_can_save_sorting_impact_and_complete_the_report(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $report = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Sampah telah diangkut',
            'status' => 'in_process',
        ]);

        $this->actingAs($officer)
            ->post(route('petugas.sortings.store', $report->id), [
                'organic_weight_kg' => 10,
                'inorganic_weight_kg' => 5,
                'paper_weight_kg' => 3,
                'channel_type' => 'waste_bank',
                'prevented_burning_risk' => '1',
            ])
            ->assertRedirect(route('petugas.sortings.index'))
            ->assertSessionHas('success');

        $sorting = WasteSorting::query()->firstOrFail();
        $this->assertSame($report->id, $sorting->waste_report_id);
        $this->assertSame('27.000', $sorting->prevented_carbon_emissions_kg);
        $this->assertTrue($sorting->prevented_burning_risk);
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'completed',
        ]);
    }

    public function test_invalid_sorting_values_do_not_create_a_record(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $report = WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Jalan Contoh 10',
            'description' => 'Sampah telah diangkut',
            'status' => 'completed',
        ]);

        $this->actingAs($officer)
            ->from(route('petugas.sortings.create', $report->id))
            ->post(route('petugas.sortings.store', $report->id), [
                'organic_weight_kg' => -1,
                'inorganic_weight_kg' => 0,
                'paper_weight_kg' => 0,
                'channel_type' => 'invalid',
                'prevented_burning_risk' => 'yes',
            ])
            ->assertRedirect(route('petugas.sortings.create', $report->id))
            ->assertSessionHasErrors([
                'organic_weight_kg',
                'channel_type',
                'prevented_burning_risk',
            ]);

        $this->assertDatabaseCount('waste_sortings', 0);
    }
}
