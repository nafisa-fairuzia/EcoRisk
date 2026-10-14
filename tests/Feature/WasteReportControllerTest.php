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
            ->assertSee('Pantau laporan terakhir')
            ->assertSee('LPR-2026-777')
            ->assertSee('Perbarui aduan')
            ->assertDontSee('Buat Pengaduan Baru');

        $this->get(route('warga.dashboard', ['section' => 'create']))
            ->assertOk()
            ->assertSee('Buat Laporan Risiko')
            ->assertSee('data-location-picker', false)
            ->assertSee('Gunakan lokasi saya')
            ->assertSee('Buat Pengaduan Baru')
            ->assertDontSee('LPR-2026-777');

        $this->get(route('warga.dashboard', ['section' => 'reports']))
            ->assertOk()
            ->assertSee('Status & Riwayat Aduan')
            ->assertSee('Riwayat Aduan Saya')
            ->assertSee('LPR-2026-777')
            ->assertDontSee('Buat Pengaduan Baru');
    }

    public function test_warga_dashboard_guides_first_time_report_creation(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);

        $this->actingAs($warga)
            ->get(route('warga.dashboard'))
            ->assertOk()
            ->assertSee('Belum ada aduan yang perlu dipantau.')
            ->assertSee('Tandai lokasi di peta')
            ->assertSee('Buat laporan pertama');
    }

    public function test_warga_can_view_their_report_details_and_status_timeline(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'kode_laporan' => 'LPR-2026-881',
            'alamat' => 'Jalan Melati 12',
            'rt' => '02',
            'rw' => '04',
            'patokan' => 'Dekat balai warga',
            'jenis_tumpukan' => 'Sampah domestik',
            'deskripsi' => 'Tumpukan sampah di sudut jalan',
            'foto_url' => '/images/sample_report.jpg',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.laporan.show', $report))
            ->assertSee('LPR-2026-881')
            ->assertSee('Jalan Melati 12')
            ->assertSee('Dekat balai warga')
            ->assertSee('Tumpukan sampah di sudut jalan')
            ->assertSee('/images/sample_report.jpg')
            ->assertSee('Timeline penanganan')
            ->assertSee('Aduan dikirim')
            ->assertSee('Edit aduan')
            ->assertSee('Hapus aduan');
    }

    public function test_admin_can_open_any_report_detail_from_the_risk_report_list(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $reporter = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $reporter->id,
            'kode_laporan' => 'LPR-2026-992',
            'alamat' => 'Jalan Kenanga 15',
            'address' => 'Jalan Kenanga 15',
            'deskripsi' => 'Aduan yang perlu ditinjau administrator.',
            'description' => 'Aduan yang perlu ditinjau administrator.',
            'is_near_river' => true,
            'is_dense_populated' => true,
            'history_burned' => true,
            'status' => 'reported',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.heatmap'))
            ->assertOk()
            ->assertSee(route('admin.laporan.show', $report), false)
            ->assertSee('Lihat aduan');

        $this->get(route('admin.laporan.show', $report))
            ->assertOk()
            ->assertSee('LPR-2026-992')
            ->assertSee('Aduan yang perlu ditinjau administrator.')
            ->assertSee('Menunggu')
            ->assertSee('Tinggi')
            ->assertDontSee('reported')
            ->assertDontSee('high')
            ->assertSee('Kembali ke daftar aduan');
    }

    public function test_warga_report_detail_displays_the_uploaded_photo_from_public_storage(): void
    {
        Storage::fake('public');
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $photoPath = UploadedFile::fake()->image('report.jpg')->store('reports', 'public');
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'photo' => $photoPath,
            'foto_url' => '/storage/'.$photoPath,
            'address' => 'Lokasi aduan',
            'description' => 'Foto harus tampil di detail.',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.laporan.show', $report))
            ->assertSee('src="'.Storage::disk('public')->url($photoPath).'"', false)
            ->assertSee('Foto Saat Aduan Dikirim');
    }

    public function test_warga_can_edit_their_unprocessed_report_and_replace_its_photo(): void
    {
        Storage::fake('public');
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        Storage::disk('public')->put('reports/old-photo.jpg', 'old photo');
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'photo' => 'reports/old-photo.jpg',
            'foto_url' => '/storage/reports/old-photo.jpg',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'address' => 'Lokasi lama',
            'description' => 'Deskripsi lama.',
            'rt' => '01',
            'rw' => '02',
            'jenis_tumpukan' => 'Sampah campuran',
            'urgensi' => 'sedang',
            'status' => 'reported',
        ]);
        $newPhoto = UploadedFile::fake()->image('new-report.jpg');

        $this->actingAs($warga)
            ->get(route('warga.laporan.edit', $report))
            ->assertOk()
            ->assertSee('Edit aduan')
            ->assertSee('data-location-picker', false);

        $response = $this->put(route('warga.laporan.update', $report), [
            'latitude' => '-6.25',
            'longitude' => '106.85',
            'rt' => '03',
            'rw' => '04',
            'patokan' => 'Dekat taman',
            'jenis_tumpukan' => 'Sampah plastik',
            'deskripsi' => 'Deskripsi yang sudah diperbarui.',
            'urgensi' => 'tinggi',
            'foto' => $newPhoto,
        ]);

        $response->assertRedirect(route('warga.laporan.show', $report))
            ->assertSessionHas('success');

        $report->refresh();
        $this->assertSame(-6.25, (float) $report->latitude);
        $this->assertSame(106.85, (float) $report->longitude);
        $this->assertSame('03', $report->rt);
        $this->assertSame('04', $report->rw);
        $this->assertSame('Dekat taman', $report->patokan);
        $this->assertSame('Sampah plastik', $report->jenis_tumpukan);
        $this->assertSame('Deskripsi yang sudah diperbarui.', $report->description);
        $this->assertSame('tinggi', $report->urgensi);
        $this->assertSame('reported', $report->status);
        Storage::disk('public')->assertMissing('reports/old-photo.jpg');
        Storage::disk('public')->assertExists($report->photo);
    }

    public function test_warga_can_delete_their_unprocessed_report_and_its_photo(): void
    {
        Storage::fake('public');
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        Storage::disk('public')->put('reports/to-delete.jpg', 'uploaded photo');
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'photo' => 'reports/to-delete.jpg',
            'address' => 'Lokasi aduan',
            'description' => 'Aduan yang akan dihapus.',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->delete(route('warga.laporan.destroy', $report))
            ->assertRedirect(route('warga.dashboard', ['section' => 'reports']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('waste_reports', ['id' => $report->id]);
        Storage::disk('public')->assertMissing('reports/to-delete.jpg');
    }

    public function test_warga_cannot_edit_or_delete_a_report_after_officer_processing_starts(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'address' => 'Lokasi sedang ditangani',
            'description' => 'Aduan yang sudah diproses.',
            'status' => 'in_process',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.laporan.edit', $report))
            ->assertForbidden();

        $this->get(route('warga.laporan.show', $report))
            ->assertSee('sudah diproses atau ditangani petugas')
            ->assertDontSee('Edit aduan')
            ->assertDontSee('Hapus aduan');

        $this->from(route('warga.laporan.show', $report))
            ->put(route('warga.laporan.update', $report), [
                'latitude' => '-6.2',
                'longitude' => '106.8',
                'rt' => '01',
                'rw' => '02',
                'jenis_tumpukan' => 'Sampah campuran',
                'deskripsi' => 'Tidak boleh diperbarui.',
                'urgensi' => 'sedang',
            ])
            ->assertRedirect(route('warga.laporan.show', $report))
            ->assertSessionHasErrors('report');

        $this->from(route('warga.laporan.show', $report))
            ->delete(route('warga.laporan.destroy', $report))
            ->assertRedirect(route('warga.laporan.show', $report))
            ->assertSessionHasErrors('report');

        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'in_process',
            'description' => 'Aduan yang sudah diproses.',
        ]);
    }

    public function test_warga_cannot_edit_or_delete_another_residents_report(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $anotherWarga = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $anotherWarga->id,
            'address' => 'Alamat privat',
            'description' => 'Aduan milik orang lain.',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.laporan.edit', $report))
            ->assertNotFound();

        $this->put(route('warga.laporan.update', $report), [])
            ->assertNotFound();

        $this->delete(route('warga.laporan.destroy', $report))
            ->assertNotFound();

        $this->assertDatabaseHas('waste_reports', ['id' => $report->id]);
    }

    public function test_warga_cannot_view_another_residents_report_details(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $anotherWarga = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $anotherWarga->id,
            'address' => 'Alamat privat',
            'description' => 'Informasi privat',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.laporan.show', $report))
            ->assertNotFound();
    }

    public function test_report_details_escape_user_provided_description(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'address' => 'Jalan Aman',
            'description' => '<script>alert("xss")</script>',
            'status' => 'reported',
        ]);

        $this->actingAs($warga)
            ->get(route('warga.laporan.show', $report))
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("xss")</script>', false);
    }

    public function test_officer_can_view_report_details_and_status_changes_are_recorded_in_timeline(): void
    {
        /** @var User $officer */
        $officer = User::factory()->create(['role' => 'petugas']);
        $warga = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $warga->id,
            'kode_laporan' => 'LPR-2026-882',
            'nama_pelapor' => $warga->name,
            'telepon_pelapor' => '081234567890',
            'alamat' => 'Jalan Kenanga 8',
            'rt' => '03',
            'rw' => '05',
            'jenis_tumpukan' => 'Sampah plastik',
            'deskripsi' => 'Sampah menutup saluran',
            'urgensi' => 'tinggi',
            'status' => 'reported',
        ]);

        $this->actingAs($officer)
            ->patch(route('petugas.laporan.update-status', $report->id), [
                'status' => 'in_process',
                'catatan_petugas' => 'Petugas mulai menangani laporan.',
            ])
            ->assertRedirect();

        $this->patch(route('petugas.laporan.update-status', $report->id), [
            'status' => 'completed',
            'catatan_petugas' => 'Sampah sudah diangkut.',
        ])->assertRedirect();

        $this->assertDatabaseHas('waste_report_status_histories', [
            'waste_report_id' => $report->id,
            'status' => 'reported',
        ]);
        $this->assertDatabaseHas('waste_report_status_histories', [
            'waste_report_id' => $report->id,
            'status' => 'in_process',
            'note' => 'Petugas mulai menangani laporan.',
        ]);
        $this->assertDatabaseHas('waste_report_status_histories', [
            'waste_report_id' => $report->id,
            'status' => 'completed',
            'note' => 'Sampah sudah diangkut.',
        ]);

        $this->get(route('petugas.laporan.show', $report))
            ->assertSee('LPR-2026-882')
            ->assertSee('Informasi pelapor')
            ->assertSee($warga->name)
            ->assertSee('Aduan dikirim')
            ->assertSee('Mulai diproses')
            ->assertSee('Selesai ditangani')
            ->assertSee('Sampah sudah diangkut.');
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
            ->assertViewHas('mapPoints', function ($mapPoints) use ($higherRiskReport, $lowerRiskReport): bool {
                return $mapPoints->count() === 2
                    && $mapPoints->contains(fn (array $point): bool => $point['id'] === $higherRiskReport->id
                        && $point['latitude'] === -6.3
                        && $point['longitude'] === 106.9)
                    && $mapPoints->contains(fn (array $point): bool => $point['id'] === $lowerRiskReport->id
                        && $point['latitude'] === -6.2
                        && $point['longitude'] === 106.8);
            });
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
            'latitude' => -6.2,
            'longitude' => 106.8,
            'is_near_river' => true,
            'is_dense_populated' => true,
            'history_burned' => true,
        ]);
        WasteReport::create([
            'user_id' => User::factory()->create()->id,
            'address' => 'Laporan tanpa lokasi',
            'description' => 'Tidak memiliki titik koordinat',
            'status' => 'reported',
            'rw' => '05',
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
            ->assertViewHas('riskZones', fn ($zones): bool => $zones->first()['reports_count'] === 1)
            ->assertViewHas('mapPoints', fn ($points): bool => $points->count() === 1
                && $points->first()['latitude'] === -6.2
                && $points->first()['longitude'] === 106.8)
            ->assertViewHas('riskSummary', fn (array $summary): bool => $summary['high_priority_reports'] === 1
                && $summary['active_reports'] === 2
                && $summary['mapped_reports'] === 1);
    }

    public function test_warga_can_submit_a_report_using_coordinates_selected_on_the_map(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);

        $response = $this->actingAs($warga)
            ->from(route('warga.dashboard', ['section' => 'create']))
            ->post(route('warga.laporan.store'), [
                'latitude' => '-6.2',
                'longitude' => '106.8',
                'rt' => '02',
                'rw' => '04',
                'patokan' => 'Dekat balai warga',
                'jenis_tumpukan' => 'Sampah domestik campuran',
                'deskripsi' => 'Tumpukan sampah di dekat saluran air.',
                'urgensi' => 'tinggi',
            ]);

        $response->assertRedirect(route('warga.dashboard', ['section' => 'create']))
            ->assertSessionHas('success');

        $report = WasteReport::query()->firstOrFail();

        $this->assertSame(-6.2, (float) $report->latitude);
        $this->assertSame(106.8, (float) $report->longitude);
        $this->assertSame('Titik peta (-6.2000000, 106.8000000)', $report->address);
        $this->assertSame('Dekat balai warga', $report->patokan);
        $this->assertSame('reported', $report->status);
    }

    public function test_warga_cannot_submit_a_report_with_out_of_range_map_coordinates(): void
    {
        /** @var User $warga */
        $warga = User::factory()->create(['role' => 'warga']);

        $response = $this->actingAs($warga)
            ->from(route('warga.dashboard', ['section' => 'create']))
            ->post(route('warga.laporan.store'), [
                'latitude' => '95',
                'longitude' => '106.8',
                'rt' => '02',
                'rw' => '04',
                'jenis_tumpukan' => 'Sampah domestik campuran',
                'deskripsi' => 'Koordinat yang tidak valid.',
                'urgensi' => 'sedang',
            ]);

        $response->assertRedirect(route('warga.dashboard', ['section' => 'create']))
            ->assertSessionHasErrors('latitude');
        $this->assertDatabaseCount('waste_reports', 0);
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
