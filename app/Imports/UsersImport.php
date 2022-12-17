<?php

namespace App\Imports;

use App\Notifications\InviteUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow,  WithChunkReading, ShouldQueue
{
   
    public function model(array $row)
	{
	    $emails =  ['email' => $row['email']];

	    $invitationLink = url('/register');

	    foreach ($emails as $email) {
	    	
	    	Notification::route('mail', $email)->notify(new InviteUser($invitationLink));
	    }  
	}

	 public function chunkSize(): int
    {
        return 500;
    }
	    
}
