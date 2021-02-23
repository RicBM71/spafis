@component('mail::message', ['data' => $data])
# Documentación adjunta

Adjunto remitimos factura de servicios realizados.
{{--
@component('mail::button', ['url' => 'url'])
View Order
@endcomponent --}}

{{ $data['msg'] }}

Saludos!<br>
{{-- {{ config('app.name') }} --}}

@endcomponent
