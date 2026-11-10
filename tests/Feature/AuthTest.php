<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Mengosongkan & menyiapkan database sementara setiap kali test dijalankan

    public function test_halaman_login_bisa_diakses()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_bisa_registrasi_dan_otomatis_login()
    {
        $response = $this->post('/register', [
            'name'                  => 'Budi Santoso',
            'email'                 => 'budi@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Memastikan dialihkan ke halaman transaksi
        $response->assertRedirect('/transactions');
        
        // Memastikan data user tersimpan di database
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
        ]);

        // Memastikan status user terautentikasi
        $this->assertAuthenticated();
    }

    public function test_user_bisa_login_dengan_kredensial_yang_benar()
    {
        $user = User::factory()->create([
            'email'    => 'budi@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'budi@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/transactions');
        $this->assertAuthenticatedAs($user);
    }
}