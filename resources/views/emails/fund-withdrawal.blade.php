<x-mail::message>
Hi Admin,

{{$name}} withdraw sone funds from his wallet. <br>

Kindly attend to the request.

<x-mail::panel>
Amount : <b>N {{number_format($amount)}}</b>
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
