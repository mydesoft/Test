<?php

namespace Tests\Feature;

use App\Helpers\Utils;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Laravel\Passport\Passport;

class AdminTest extends TestCase
{
    use RefreshDatabase;


    protected function apiActingAs($user=null){

        return  Passport::actingAs($user ?? User::factory()->create(['user_type' => 'admin']));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_admin_can_update_user_role()
    {
        $this->apiActingAs();
       
        $user = User::factory()->create();

        $data = ['email' => $user->email, 'role' => 'admin'];

        $response = $this->post('/api/v1/admin/update-permission', $data);

        $response->assertStatus(200);
   
    }

    public function  test_admin_cannot_update_user_role_if_user_does_not_exist()
    {

        $this->apiActingAs();
       
        $user = User::factory()->create();

        $data = ['email' => 'kem@test.com', 'role' => 'admin'];

        $response = $this->post('/api/v1/admin/update-permission', $data);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('users', [
             'email' => 'kem@test.com',
        ]);
    }

    public function test_admin_can_update_user_status(){

        $this->apiActingAs();
       
        $user = User::factory()->create();

        $data = ['email' => $user->email];

        $response = $this->post('/api/v1/admin/update-status?status=suspended', $data);

        $response->assertStatus(200);   
    }

     public function test_admin_cannot_update_user_status_if_user_does_not_exist(){

        $this->apiActingAs();
       
        $user = User::factory()->create();

        $data = ['email' => 'kem@test.com'];

        $response = $this->post('/api/v1/admin/update-status?status=suspended', $data);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('users', [
             'email' => 'kem@test.com',
        ]);  
    }

    public function test_admin_can_invite_user(){

        $this->apiActingAs();

       $response = $this->post('/api/v1/admin/invite-user', ['email' => 'kem@user.com']);

        $response->assertStatus(200);   
    }

     public function test_admin_cannot_invite_user_with_bad_form_data(){

        $this->apiActingAs();

        $response = $this->post('/api/v1/admin/invite-user', ['email' => '']);

        $response->assertStatus(422);  
    }

    public function test_admin_can_fund_user_wallet()
    {
        $this->apiActingAs();
       
        $user = User::factory()->create();

        $data = ['amount' => 1000];

        $response = $this->post('/api/v1/admin/fund-user-wallet/'.$user->id, $data);

        $response->assertStatus(200);
   
    }

     public function test_admin_cannot_fund_user_wallet_if_user_does_not_exist()
    {
        $this->apiActingAs();
       
        $user = User::factory()->create();

        $data = ['amount' => 1000];

        $userId = 100;

        $response = $this->post('/api/v1/admin/fund-user-wallet/'.$userId, $data);

        $this->assertDatabaseMissing('users', ['id' => 100]);
   
    } 

}
