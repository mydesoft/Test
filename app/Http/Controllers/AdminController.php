<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\ActivityRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\WithdrawalResource;
use App\Imports\UsersImport;
use App\Jobs\UserBulkUpload;
use App\Models\User;
use App\Notifications\FundUserWallet;
use App\Notifications\InviteUser;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function updatePermission(ActivityRequest $request): JsonResponse
    {
    	$data = $request->validated();

    	$user = User::where('email', $data['email'])->first();

    	if (!$user) {
    		
    		return $this->notFound();
    	}

    	try {

    		$user->update(['user_type' => $data['role']]);
    		
    	} catch (QueryException $e) {

    		return $this->customError('not_allowed_role', 406,'Allowed roles are user or admin');
    	}


    	return $this->success();
    		
    }

    public function updateStatus(ActivityRequest $request): JsonResponse
    {
    	$data = $request->validated();

    	$user = User::where('email', $data['email'])->first();

    	if (!$user) {
    		
    		return $this->notFound();
    	}

    	try {

    		$user->update(['status' => $request->status]);
    		
    	} catch (QueryException $e) {

    		return $this->customError('not_allowed_role', 406,'Allowed status are active or suspended');
    	}


    	return $this->success();
    		
    }

    public function inviteUser(ActivityRequest $request): JsonResponse
    {
    	$data = $request->validated();

    	$invitationLink = url('/register');

    	Notification::route('mail', $data['email'])->notify(new InviteUser($invitationLink));

    	return $this->success();
    }

    public function bulkUploadUsers(Request $request): JsonResponse
    {
    	$request->validate(['user_emails' => 'required|file']);

    	Excel::import(new UsersImport, request()->file('user_emails'));

        return $this->success();
    }

    public function fundUserWallet(PaymentRequest $request, string $userId): JsonResponse
    {
        $data = $request->validated();

        $user = User::find($userId);

        if (!$user) {
          
          return $this->notFound();
        }

        $user->deposit(doubleval($data['amount']));

        $user->notify(new FundUserWallet($data['amount']));

        return $this->success();
    }
   
}
