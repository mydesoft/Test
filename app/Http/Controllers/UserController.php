<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\UserResource;
use App\Notifications\FundWithdrawal;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public function getProfile(): JsonResponse
    {
    	$user = Auth::user();

    	return $this->success(new UserResource($user));
    }

    public function depositFund(PaymentRequest $request): JsonResponse
    {
    	$data = $request->validated();

    	$user = Auth::user();

    	$user->deposit(doubleval($data['amount']));

    	return $this->success();
    }

    public function withdrawFund(PaymentRequest $request): JsonResponse
    {
    	$data = $request->validated();

    	$user = Auth::user();

    	try {

    		$withdrawal = $user->withdraw(doubleval($data['amount']));

    	} catch (InsufficientFunds $e) {
    		
    		return $this->customError('insufficient_funds', 406, $e->getMessage());
    	}

        Notification::route('mail', config('mail.admin_email'))->notify(new FundWithdrawal($user, $data['amount']));

 		return $this->success();

    }
}
