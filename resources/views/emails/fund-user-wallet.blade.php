<x-mail::message>
Hi {{$name}}

We would like to inform you that a credit action occured in your wallet. <br>

Please find the details below.

<x-mail::panel>
	Awmount : <b>N{{number_format($amount)}}</b>
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
