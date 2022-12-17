<?php

namespace Tests\Feature;

use App\Helpers\Utils;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    use RefreshDatabase;


    protected function apiActingAs($user){

        return  Passport::actingAs($user);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_can_get_user_profile()
    {
       
        $user = User::factory()->create();

        $this->apiActingAs($user);

        $response = $this->get('/api/v1/users/profile');

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);   
    }

    public function test_cannot_get_user_profile_if_not_authenticated()
    {

       $response = $this->get('/api/v1/users/profile', ['HTTP_Accept' => 'application/json']);

       $response->assertStatus(401);

       $this->assertGuest($guard = null);
    }

    public function test_user_can_make_deposit(){

        $user = User::factory()->create();

        $this->apiActingAs($user);

        $response = $this->post('/api/v1/users/deposit', ['amount' => 10000]);

        $response->assertStatus(200);

        $this->assertDatabaseCount('transactions', 1);   
    }

     public function test_cannot_make_deposit_with_invalid_amount(){

        $user = User::factory()->create();

        $this->apiActingAs($user);

        $response = $this->post('/api/v1/users/deposit', ['amount' => '']);

        $response->assertStatus(422);

        $this->assertDatabaseCount('transactions', 0);   
    }

    public function test_user_can_make_withdrawal(){

        $user = User::factory()->create();

        $this->apiActingAs($user);

        $this->post('/api/v1/users/deposit', ['amount' => 10000]);

        $response = $this->post('/api/v1/users/withdrawal', ['amount' => 10000]);

        $response->assertStatus(200);   
    }

     public function test_cannot_make_withdrawal_with_invalid_amount(){
        
        $user = User::factory()->create();

        $this->apiActingAs($user);

        $response = $this->post('/api/v1/users/withdrawal', ['amount' => '']);

        $response->assertStatus(422);

        $this->assertDatabaseCount('transactions', 0);   
    }

}
