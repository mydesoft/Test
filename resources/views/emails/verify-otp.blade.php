<x-mail::message>
Hi {{$user->name}}, 

Please use the token below to verify your account

<x-mail::panel >
	Token : <b>{{$otp}}</b>
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
