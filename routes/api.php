<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function(){

	 Route::any('/', function () {
        return response()->json([
            'mesage' => 'Welcome to Zojatech Backend Test',
            'apiVersion' => 'v1.0.0',
        ]);
    });


		Route::group(['prefix' => 'v1'], function(){

			Route::controller(AuthController::class)->group(function(){
				Route::post('/register', 'register');
				Route::post('/verify-otp', 'verifyUserOtp');
				Route::post('/resend-otp', 'resendVerificationOtp');
				Route::post('/login', 'login');
			}); 

			Route::group(['middleware' => ['auth:api']], function(){

				Route::post('/logout', [AuthController::class, 'logout']);
				
				//User Routes
				Route::group(['middleware' => ['user']], function(){

					Route::controller(UserController::class)->prefix('users')->group(function(){
						Route::get('/profile', 'getProfile');
						Route::post('/deposit', 'depositFund');
						Route::post('/withdrawal', 'withdrawFund');
					});
				});

				//Admin Routes
				Route::group(['middleware' => ['admin']], function(){

					Route::controller(AdminController::class)->prefix('admin')->group(function(){
						Route::post('/update-permission', 'updatePermission');
						Route::post('/update-status', 'updateStatus');
						Route::post('/invite-user', 'inviteUser');
						Route::post('/bulk-upload-users', 'bulkUploadUsers');
						Route::post('/fund-user-wallet/{user}', 'fundUserWallet');
					});
				});
			});

	});
});
