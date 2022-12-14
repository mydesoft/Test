<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Helpers\Utils;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\VerifyUserOtpRequest;
use App\Mail\VerifyOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request): JsonResponse
    {
    	$data = $request->validated();

    	$data['user_type'] = UserType::USER->value;

    	$data['password'] = Hash::make($data['password']);

    	$user = User::create($data);

    	$otp = Utils::generateOtp();

    	$this->saveOtp($user->email, $otp);

    	Mail::to($user->email)->send(new VerifyOtp($user, $otp));

    	return $this->created($user);
    }

     public function verifyUserOtp(VerifyUserOtpRequest $request): JsonResponse
    {
        $userToken = DB::table('password_resets')->where('token', $request->token)->first();

        if (! $userToken) {

            return $this->customError('token_not_exist', 404, 'Invalid token');
        }

        if (! Utils::isValidOTP($request->token)) {

            return $this->customError('expired_token', 406, 'Token has already expired');
        }

        $user = User::where('email', $userToken->email)->update(['is_verified' => true]);

       	return $this->success();

    }

    public function login(LoginRequest $request): JsonResponse
    {
    	$data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user) {

            return $this->customError('user_not_exist', 404, 'This user does not exist on the platform!');
        }

        if (! Hash::check($data['password'], $user->password)) {

            return $this->customError('password_mismatch', 401, 'Password mismatch');
        }

        if (! $user->is_verified) {

            return $this->customError('user_not_verified', 403, 'User has not been verified');
        }
      
        $token = $user->createToken($user->name)->accessToken;

        Auth::login($user);

        return $this->success(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return $this->success();
    }

    protected function saveOtp(string $email, string|int $otp): bool
    {
       // invalidate all existing token for user if it already exists
        $user = DB::table('password_resets')->where('email', $email)->delete();

        return DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $otp,
            'created_at' => now(),
        ]);
    }
}
