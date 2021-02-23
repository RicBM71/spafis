<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Recibo</title>

        <style>
            .tickets{
                font-family: Arial;
                font-size: 13px;
                width: 7cm;
                padding: 0px;

            }
            .tickets p{
                margin: 0;
                padding: 1px;
            }
            .tkpie{
                font-family: Arial;
                font-size: 8px;
                font-weight: bold;
            }

            .negrita{
                font-weight: bold;
            }
            .centrar{
                text-align: center;
            }
            .justificar{
                text-align: justify;
            }
        </style>
    </head>
    <body>
        <div id="recibo" class="tickets">
            <table border="0" align="center">
                <tr><td colspan="2" align="center" class="negrita">{{ $empresa->nombre}}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" class="negrita">RECIBO: <span id="comercio"></span></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td>Fecha: <span id="fecha">{{ getFecha($fecha)}}</span></td></tr>
                <tr><td colspan="2" align="left">{{ $paciente->nom_ape}}</td></tr>
                <tr><td colspan="2"></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" align="left">TRATAMIENTOS DE FISIOTERAPIA</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" class="negrita" align="center" id="importe">Importe: {{getCurrency($total)}}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" align="center" class="negrita">Tf. {{ getTelefonoFijo($empresa->telefono1)}} - WhatsApp {{ getTelefonoFijo($empresa->telefono2)}}</td></tr>
            </table>
        </div>
    </body>
</html>
