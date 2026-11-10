<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_tidak_bisa_mengakses_halaman_transaksi()
    {
        // Pengunjung belum login mencoba mengakses /transactions
        $response = $this->get('/transactions');

        // Wajib ditendang ke halaman /login oleh middleware auth
        $response->assertRedirect('/login');
    }

    public function test_user_terautentikasi_bisa_membuat_transaksi()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Makanan', 'type' => 'expense']);

        $response = $this->actingAs($user)->post('/transactions', [
            'category_id' => $category->id,
            'amount'      => 25000,
            'date'        => '2026-11-09',
            'description' => 'Beli Nasi Goreng',
        ]);

        $response->assertRedirect('/transactions');

        // Memastikan data transaksi tersimpan di database dengan user_id yang tepat
        $this->assertDatabaseHas('transactions', [
            'user_id'     => $user->id,
            'amount'      => 25000,
            'description' => 'Beli Nasi Goreng',
        ]);
    }

    public function test_user_tidak_bisa_mengedit_transaksi_milik_user_lain()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::create(['name' => 'Gaji', 'type' => 'income']);

        // Membuat transaksi milik User A
        $transactionA = Transaction::create([
            'user_id'     => $userA->id,
            'category_id' => $category->id,
            'amount'      => 1000000,
            'date'        => '2026-11-09',
            'description' => 'Gaji User A',
        ]);

        // User B mencoba mengakses halaman edit milik User A
        $response = $this->actingAs($userB)->get("/transactions/{$transactionA->id}/edit");

        // Memastikan respon membalikkan HTTP Status 403 Forbidden
        $response->assertStatus(403);
    }
}   