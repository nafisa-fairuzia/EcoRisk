<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteReport;
use App\Models\WasteSorting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_warga_can_log_in_without_demo_accounts(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('admin@kelurahan.go.id')
            ->assertDontSee('password123')
            ->assertDontSee('petugas@kebersihan.id')
            ->assertDontSee('budi@warga.id');

        $registration = [
            'name' => 'Warga Uji',
            'email' => 'warga@example.test',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'alamat' => 'Jalan Contoh 10',
            'rt' => '07',
            'rw' => '03',
            'telepon' => '081234567890',
        ];

        $this->post(route('register'), $registration)
            ->assertRedirect(route('warga.dashboard'));

        $user = User::where('email', $registration['email'])->firstOrFail();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $registration['name'],
            'email' => $registration['email'],
            'alamat' => $registration['alamat'],
            'role' => 'warga',
            'zona_wilayah' => 'RT 07 / RW 03',
            'status' => 'Aktif',
        ]);
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();

        $this->post(route('login'), [
            'email' => $registration['email'],
            'password' => $registration['password'],
        ])->assertRedirect(route('warga.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $this->from(route('login'))
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'status' => 'Nonaktif',
        ]);

        $this->from(route('login'))
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_open_the_user_management_page(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $managedUser = User::factory()->create([
            'name' => 'Akun untuk dikelola',
            'role' => 'mitra_daur_ulang',
            'zona_wilayah' => 'RW 04',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Kelola Pengguna')
            ->assertSee(route('admin.users.create'), false)
            ->assertSee(route('admin.users.edit', $managedUser), false)
            ->assertSee('Tambah akun')
            ->assertSee($managedUser->email)
            ->assertSee('Hapus')
            ->assertSee('Nonaktifkan')
            ->assertDontSee('name="telepon"', false);
    }

    public function test_admin_menus_open_distinct_pages(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dampak lingkungan')
            ->assertSee('Estimasi emisi dicegah');

        $this->get(route('admin.heatmap'))
            ->assertOk()
            ->assertSee('Heatmap Risiko Wilayah');

        $this->get(route('admin.impact'))
            ->assertRedirect(route('admin.dashboard').'#impact');

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Tambah akun');

        $this->get(route('admin.users.create'))
            ->assertOk()
            ->assertSee('Tambah akun operasional')
            ->assertSee('Koordinator Induk Seluruh Wilayah')
            ->assertDontSee('name="telepon"', false);
    }

    public function test_admin_dashboard_renders_channel_proportions_in_a_donut_chart(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $reporter = User::factory()->create(['role' => 'warga']);
        $maggotReport = WasteReport::create([
            'user_id' => $reporter->id,
            'address' => 'Lokasi Maggot',
            'description' => 'Penyaluran untuk maggot.',
            'status' => 'completed',
        ]);
        $compostReport = WasteReport::create([
            'user_id' => $reporter->id,
            'address' => 'Lokasi Kompos',
            'description' => 'Penyaluran untuk kompos.',
            'status' => 'completed',
        ]);
        WasteSorting::create([
            'waste_report_id' => $maggotReport->id,
            'organic_weight_kg' => 10,
            'channel_type' => 'maggot_bsf',
        ]);
        WasteSorting::create([
            'waste_report_id' => $compostReport->id,
            'organic_weight_kg' => 30,
            'channel_type' => 'compost_community',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('data-channel-chart', false)
            ->assertSee('stroke-dasharray="25 75"', false)
            ->assertSee('stroke-dashoffset="-25"', false)
            ->assertSee('40.0')
            ->assertSee('Maggot BSF')
            ->assertSee('Kompos komunitas');
    }

    public function test_admin_can_toggle_another_users_status(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $managedUser = User::factory()->create(['status' => 'Aktif']);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle', $managedUser->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $managedUser->id,
            'status' => 'Nonaktif',
        ]);
    }

    public function test_admin_cannot_toggle_their_own_status(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'Aktif']);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle', $admin->id))
            ->assertRedirect()
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'status' => 'Aktif',
        ]);
    }

    public function test_admin_can_edit_another_user_and_change_their_password(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $managedUser = User::factory()->create(['role' => 'warga']);

        $this->actingAs($admin)
            ->get(route('admin.users.edit', $managedUser))
            ->assertOk()
            ->assertSee('Edit Pengguna')
            ->assertSee($managedUser->email);

        $this->put(route('admin.users.update', $managedUser), [
            'name' => 'Petugas Baru',
            'email' => 'petugas.baru@example.test',
            'role' => 'petugas',
            'zona_wilayah' => 'Zona Barat',
            'alamat' => 'Jalan Melati 5',
            'telepon' => '081234567890',
            'status' => 'Aktif',
            'password' => 'updated-password-123',
            'password_confirmation' => 'updated-password-123',
        ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $managedUser->refresh();

        $this->assertSame('Petugas Baru', $managedUser->name);
        $this->assertSame('petugas.baru@example.test', $managedUser->email);
        $this->assertSame('petugas', $managedUser->role);
        $this->assertSame('Zona Barat', $managedUser->zona_wilayah);
        $this->assertSame('Jalan Melati 5', $managedUser->alamat);
        $this->assertSame('081234567890', $managedUser->telepon);
        $this->assertTrue(Hash::check('updated-password-123', $managedUser->password));
    }

    public function test_admin_cannot_change_their_own_role_or_status_from_user_edit(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'Aktif']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'warga',
                'status' => 'Nonaktif',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['role', 'status']);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
            'status' => 'Aktif',
        ]);
    }

    public function test_admin_can_delete_another_user_and_their_reports(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $managedUser = User::factory()->create(['role' => 'warga']);
        $report = WasteReport::create([
            'user_id' => $managedUser->id,
            'address' => 'Lokasi yang dilaporkan',
            'description' => 'Laporan milik akun yang akan dihapus.',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $managedUser))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $managedUser->id]);
        $this->assertDatabaseMissing('waste_reports', ['id' => $report->id]);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect()
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_non_admin_cannot_edit_or_delete_managed_users(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $managedUser = User::factory()->create();

        $this->actingAs($warga)
            ->get(route('admin.users.edit', $managedUser))
            ->assertForbidden();

        $this->delete(route('admin.users.destroy', $managedUser))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $managedUser->id]);
    }

    public function test_admin_can_create_a_petugas_assigned_to_a_zone(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Petugas Zona Utara',
                'email' => 'petugas.utara@example.test',
                'role' => 'petugas',
                'zona_wilayah' => 'Zona Utara / RW 04',
                'password' => 'secure-password-123',
                'password_confirmation' => 'secure-password-123',
            ]);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Petugas Zona Utara',
            'email' => 'petugas.utara@example.test',
            'role' => 'petugas',
            'zona_wilayah' => 'Zona Utara / RW 04',
            'status' => 'Aktif',
        ]);

        $petugas = User::where('email', 'petugas.utara@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('secure-password-123', $petugas->password));
    }

    public function test_admin_zone_is_assigned_automatically_when_creating_an_administrator(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Administrator Cadangan',
                'email' => 'admin.cadangan@example.test',
                'role' => 'admin',
                'zona_wilayah' => ['nilai', 'tidak', 'valid'],
                'telepon' => '081234567890',
                'password' => 'secure-password-123',
                'password_confirmation' => 'secure-password-123',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'admin.cadangan@example.test',
            'role' => 'admin',
            'zona_wilayah' => 'Koordinator Induk Seluruh Wilayah',
            'telepon' => null,
        ]);
    }

    public function test_admin_must_assign_a_zone_when_creating_a_petugas(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Petugas Tanpa Zona',
                'email' => 'petugas.tanpazona@example.test',
                'role' => 'petugas',
                'password' => 'secure-password-123',
                'password_confirmation' => 'secure-password-123',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('zona_wilayah');

        $this->assertDatabaseMissing('users', [
            'email' => 'petugas.tanpazona@example.test',
        ]);
    }

    public function test_admin_cannot_create_an_account_with_an_existing_email(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $existingUser = User::factory()->create(['email' => 'existing@example.test']);

        $this->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Akun Duplikat',
                'email' => $existingUser->email,
                'role' => 'admin',
                'password' => 'secure-password-123',
                'password_confirmation' => 'secure-password-123',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('email');

        $this->assertSame(2, User::count());
    }

    public function test_non_admin_cannot_create_an_operational_account(): void
    {
        /** @var User $petugas */
        $petugas = User::factory()->create(['role' => 'petugas']);

        $this->actingAs($petugas)
            ->post(route('admin.users.store'), [
                'name' => 'Akun Tidak Sah',
                'email' => 'tidak.sah@example.test',
                'role' => 'admin',
                'password' => 'secure-password-123',
                'password_confirmation' => 'secure-password-123',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'email' => 'tidak.sah@example.test',
        ]);
    }

    public function test_all_account_roles_can_update_their_profiles_without_changing_their_permissions(): void
    {
        foreach (['admin', 'petugas', 'warga', 'mitra_daur_ulang'] as $role) {
            $user = User::factory()->create([
                'role' => $role,
                'alamat' => $role === 'warga' ? 'Alamat lama warga' : null,
                'zona_wilayah' => match ($role) {
                    'petugas' => 'Zona Selatan',
                    'warga' => 'RT 07 / RW 03',
                    default => null,
                },
            ]);

            $profilePage = $this->actingAs($user)
                ->get(route('profile.edit'));

            $profilePage
                ->assertOk()
                ->assertSee('Profil Saya');

            if ($role === 'warga') {
                $profilePage
                    ->assertSee('name="rt"', false)
                    ->assertSee('value="07"', false)
                    ->assertSee('name="rw"', false)
                    ->assertSee('value="03"', false)
                    ->assertSee('name="alamat"', false)
                    ->assertSee('value="Alamat lama warga"', false);
            }

            $profileData = [
                'name' => 'Nama Diperbarui '.$role,
                'email' => $user->email,
                'telepon' => '081234567890',
                'role' => 'admin',
                'zona_wilayah' => 'Zona Diubah',
            ];

            if ($role === 'warga') {
                $profileData['rt'] = '11';
                $profileData['rw'] = '02';
                $profileData['alamat'] = 'Jalan warga baru nomor 12';
            } else {
                $profileData['alamat'] = 'Alamat baru '.$role;
            }

            $this->put(route('profile.update'), $profileData)
                ->assertRedirect(route('profile.edit'))
                ->assertSessionHas('success');

            $user->refresh();
            $this->assertSame('Nama Diperbarui '.$role, $user->name);
            $this->assertSame('081234567890', $user->telepon);
            $this->assertSame(
                $role === 'warga' ? 'Jalan warga baru nomor 12' : 'Alamat baru '.$role,
                $user->alamat,
            );
            $this->assertSame($role, $user->role);
            $this->assertSame(match ($role) {
                'petugas' => 'Zona Selatan',
                'warga' => 'RT 11 / RW 02',
                default => null,
            }, $user->zona_wilayah);
        }
    }

    public function test_warga_profile_requires_rt_and_rw_before_saving_changes(): void
    {
        $user = User::factory()->create([
            'role' => 'warga',
            'alamat' => 'Alamat rumah',
            'zona_wilayah' => 'RT 07 / RW 03',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'email' => $user->email,
                'alamat' => 'Alamat rumah',
                'rt' => '',
                'rw' => '',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['rt', 'rw']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'alamat' => 'Alamat rumah',
            'zona_wilayah' => 'RT 07 / RW 03',
        ]);
    }

    public function test_warga_profile_requires_address_before_saving_changes(): void
    {
        $user = User::factory()->create([
            'role' => 'warga',
            'alamat' => 'Alamat rumah',
            'zona_wilayah' => 'RT 07 / RW 03',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'rt' => '07',
                'rw' => '03',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('alamat');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'alamat' => 'Alamat rumah',
            'zona_wilayah' => 'RT 07 / RW 03',
        ]);
    }

    public function test_profile_password_change_requires_current_password_and_confirmation(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'warga']);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'replacement-password',
                'password_confirmation' => 'different-password',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['current_password', 'password']);

        $this->assertFalse(Hash::check('replacement-password', $user->fresh()->password));

        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'password',
            'password' => 'replacement-password',
            'password_confirmation' => 'replacement-password',
        ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('replacement-password', $user->fresh()->password));
    }

    public function test_profile_photo_can_be_uploaded_and_replaces_the_previous_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/old-avatar.jpg', 'old photo');
        /** @var User $user */
        $user = User::factory()->create([
            'role' => 'warga',
            'profile_photo_path' => 'profile-photos/old-avatar.jpg',
        ]);

        $response = $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('data-password-toggle', false)
            ->assertSee('data-profile-photo-input', false)
            ->assertSee('data-profile-photo-preview', false)
            ->assertSee(Storage::disk('public')->url($user->profile_photo_path), false)
            ->assertSee('data-user-avatar-fallback', false)
            ->assertSee('Foto baru disimpan setelah Anda menekan tombol Simpan Perubahan.');

        $this->assertSame(2, substr_count($response->getContent(), 'data-user-avatar-image'));

        $this->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->image('avatar.png'),
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertStringStartsWith('profile-photos/', $user->profile_photo_path);
        Storage::disk('public')->assertMissing('profile-photos/old-avatar.jpg');
        Storage::disk('public')->assertExists($user->profile_photo_path);

        $this->get(route('profile.edit'))
            ->assertSee('src="'.Storage::disk('public')->url($user->profile_photo_path).'"', false);
    }

    public function test_profile_rejects_an_unsupported_photo_without_replacing_the_current_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/current.jpg', 'existing photo');
        /** @var User $user */
        $user = User::factory()->create([
            'role' => 'warga',
            'profile_photo_path' => 'profile-photos/current.jpg',
        ]);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->create('avatar.txt', 50, 'text/plain'),
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('foto_profil');

        $this->assertSame('profile-photos/current.jpg', $user->fresh()->profile_photo_path);
        Storage::disk('public')->assertExists('profile-photos/current.jpg');
    }

    public function test_login_and_registration_forms_have_password_visibility_controls(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-password-toggle', false)
            ->assertSee('Tampilkan kata sandi');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('data-password-toggle', false)
            ->assertSee('Tampilkan kata sandi');
    }

    public function test_landing_reopens_login_modal_after_failed_login(): void
    {
        $user = User::factory()->create();

        $this->from(route('home'))
            ->post(route('login'), [
                '_auth_form' => 'login',
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('home'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('"authModal": true', false)
            ->assertSee('"authTab": "login"', false)
            ->assertSee('Email atau kata sandi tidak cocok.');
    }

    public function test_landing_reopens_registration_modal_after_validation_error(): void
    {
        $this->from(route('home'))
            ->post(route('register'), [
                '_auth_form' => 'register',
                'name' => 'Warga Baru',
                'email' => 'warga@example.test',
                'password' => 'short',
                'password_confirmation' => 'different',
                'alamat' => 'Jalan Contoh 10',
                'rt' => '07',
                'rw' => '03',
            ])
            ->assertRedirect(route('home'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('"authModal": true', false)
            ->assertSee('"authTab": "register"', false)
            ->assertSee('mx-5 mt-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs', false);
    }
}
