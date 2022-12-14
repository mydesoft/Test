<?php

use App\Http\Controllers\AuthController;
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

	Route::group(['middleware' => ['auth:api']], function(){

		Route::group(['prefix' => 'v1'], function(){

			Route::controller(AuthController::class)->group(function(){

			});
		}); 

	});
});
