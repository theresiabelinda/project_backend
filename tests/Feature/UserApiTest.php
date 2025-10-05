<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

// Trait ini memastikan database di-refresh (dikosongkan dan dimigrasikan) untuk setiap test.
// Ini membantu mengatasi masalah ketidaksesuaian skema seperti 'updated_at'.
class UserApiTest extends TestCase
{
    use RefreshDatabase;

    // Menguji Register (POST /api/register)
    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'username' => 'test_user_reg', // Mengubah ke nama yang lebih unik
            'email' => 'register@test.com',
            'password' => 'test',
            'password_confirmation' => 'test',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'username', 'email', 'token']
            ]);

        // KOREKSI: Memastikan username yang dimasukkan di request adalah yang dicari di DB
        $this->assertDatabaseHas('users', ['username' => 'test_user_reg']);
    }

    // Menguji Login (POST /api/login)
    public function test_user_can_login_with_correct_credentials()
    {
        // Setup: Buat user yang sudah ada
        $user = User::factory()->create([
            'username' => 'test',
            'password' => Hash::make('test'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'test',
            'password' => 'test',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'token']
            ]);
    }

    // Menguji GET User Pribadi (GET /api/users/{id})
    public function test_authenticated_user_can_get_their_own_data()
    {
        // 1. Setup User & Token
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => ['id' => $user->id]
            ]);
    }

    // Menguji PUT/Update User Pribadi (PUT /api/users/{id})
    public function test_authenticated_user_can_update_their_own_data()
    {
        // 1. Setup User & Token
        $user = User::factory()->create(['username' => 'oldusername']);
        $token = $user->createToken('test-token')->plainTextToken;

        // 2. Lakukan permintaan PUT dengan Token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/users/' . $user->id, [
                'name' => 'Updated Name',
                'username' => 'newusername'
            ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['username' => 'newusername', 'name' => 'Updated Name']]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'newusername']);
    }

    // Menguji DELETE User Pribadi (DELETE /api/users/{id})
    public function test_authenticated_user_can_delete_their_account()
    {
        // 1. Setup User & Token
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // 2. Lakukan permintaan DELETE dengan Token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    // Menguji Akses Ilegal (Otorisasi Gagal)
    public function test_unauthorized_user_cannot_access_other_users_data()
    {
        // Setup User A (pengakses) dan User B (target)
        $userA = User::factory()->create();
        $userB = User::factory()->create(); // ID target

        $tokenA = $userA->createToken('test-token')->plainTextToken;

        // Coba GET data User B menggunakan token User A
        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/users/' . $userB->id); // Target ID user B

        // Diharapkan mendapatkan status 403 Forbidden
        $response->assertStatus(403)
            ->assertJson(['message' => 'Akses Ditolak. Anda tidak berhak mengubah data ini.']);
    }
}
