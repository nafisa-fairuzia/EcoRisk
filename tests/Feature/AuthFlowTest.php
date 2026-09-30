<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->post(route('logout'))->assertRedirect(route('login'));
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
