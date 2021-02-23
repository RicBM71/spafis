<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Ticket</title>

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
                <tr><td colspan="2" align="center" class="negrita">{{ $tpv->nombre_comercio}}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td align="center" colspan="2">Comercio: <span id="comercio">{{ $recibo->comercio}}</span></td></tr>
                <tr><td colspan="2" align="left" id="titular_tarjeta">{{ $recibo->titular_tarjeta}}</td></tr>
                <tr><td colspan="2"></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" align="left">{{ $tpv->nombre}}</td></tr>
                <tr><td id="tarjeta">{{$recibo->tarjeta}}</td><td>Cad: <span id="caducidad">{{$recibo->caducidad}}</span></td></tr>
                <tr><td>Aut: <span id="codigo_respuesta">{{ $recibo->codigo_respuesta}}</span></td><td>Op: <span id="pedido">{{ $recibo->pedido}}</span></td></tr>
                <tr><td>Fecha: <span id="fecha">{{ getFecha($recibo->fecha_operacion)}}</span></td><td>Hora: <span id="hora">{{ getHora($recibo->fecha_operacion)}}</span></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" class="negrita" align="center" id="importe">Importe: {{getCurrency($recibo->importe)}}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
            </table>
            <table border="0" align="center" id="firma" style="display:none">
                <tr><td>______________________________</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>______________________________</td></tr>
            </table>
            <table border="0" align="center">
                <tr><td id="auth" style="font-size: 9px;">{{$recibo->autenticado}}</td></tr>
                <tr><td align="center">¡Gracias por su visita!</td></tr>
                <tr><td align="center">Tfn: <span id="telefono">{{$telefono1}}</span></td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </div>
    </body>
</html>
