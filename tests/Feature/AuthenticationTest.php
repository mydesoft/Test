<?php

namespace Tests\Feature;

use App\Helpers\Utils;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_a_user_can_register()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'user_type' => 'user',
            'is_verified' => false,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ];

        $response = $this->post('/api/v1/register', $data);

        $response->assertStatus(201);

        $this->assertDatabaseCount('users',1);
    }

     public function test_a_user_cannot_register_with_bad_form_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@test.com',
        ];

        $response = $this->post('/api/v1/register', $data);

        $response->assertStatus(422);

    }

    public function test_verify_user_otp()
    {
         $user = User::factory()->create();

         $token = Utils::generateOtp();

         DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $data = [
            'token' => $token,
        ];

        $response = $this->post('/api/v1/verify-otp', $data);

        $response->assertStatus(200);
    }


    public function test_a_user_can_login()
    {
        $this->withoutExceptionHandling();

        \Artisan::call('passport:install');

        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/login', $data);

        $response->assertStatus(200);

    }

   public function test_a_user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create();

        $data = [
            'email' =>  $user->email,
            'password' => 'wrong_password',
        ];

        $response = $this->post('/api/v1/login', $data);

        $response->assertStatus(401);

    }
}
