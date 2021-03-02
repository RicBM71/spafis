@component('mail::message', ['data' => $data])
# Documentación adjunta

Hola, {{mb_convert_case($data['factura']['paciente']['nombre'],MB_CASE_TITLE,"UTF-8")}} adjunto remitimos factura de servicios realizados. Aprovechamos la ocasión para agradecerte la confianza depositada en nostros.
Quedamos a tu disposición para aquello que consideres oportuno.
{{--
@component('mail::button', ['url' => 'url'])
View Order
@endcomponent --}}

{{ $data['msg'] }}

Muchas gracias y recibe un cordial saludo.<br>
{{-- {{ config('app.name') }} --}}

@endcomponent
