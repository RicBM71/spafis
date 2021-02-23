<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Pasarela</title>

        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
        <script  src="https://code.jquery.com/jquery-1.12.4.min.js"  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="  crossorigin="anonymous"></script>

        <style>
            body {
                font-family: 'Roboto';
                padding: 0px;
                margin: 0px;
            }
            .respuesta{

                align-content: center;
                width: 470px;
                height: 240px;
            }
            h1{
                font-size: 1.4em;
                color: rgb(7, 7, 90);
                text-align: center
            }
            p{
                text-align: center
            }
            .btn{
                border-radius: 0;
                padding: 10px;
                width: 120px;
                cursor: pointer;
                color: white;
                background-color:#4ec776;

            }
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

        <script>
            var iniTpvpcImplantado = 0;
            var token = $('meta[name="csrf-token"]').attr('content');

            $(document).ready(function(){
               // alert("llega");
               IniTpvpcImplantado();
              //EnviarPeticion();
            });


            function RealizarOperacion(importe, tipoOper, factura)
            {
                var valRet = "TpvpcImplantado no esta inicializado";
                var codRet = 0;
                if (iniTpvpcImplantado != 0) {
                    codRet = TpvpcImplantado.OperPinPad(importe, factura, tipoOper, valRet);
                    if (codRet != 0) {
                        valRet = codRet;
                    } else {
                        valRet = TpvpcImplantado.ResultOper;
                    }
                }

                return valRet;
            }


            function RealizarComunicacionContable(pedido, rts, importe, tipoOper, factura)
            {
                var valRet = "TpvpcImplantado no esta inicializado. ";
                var codRet = 0;

                if (iniTpvpcImplantado != 0) {
                    codRet = TpvpcImplantado.OperComContable(pedido, rts, importe, factura, tipoOper, valRet);
                    if (codRet != 0)
                        valRet = codRet;
                    else
                        valRet = TpvpcImplantado.ResultOper;
                }

                return valRet;
            }


            function RealizarOperManual(tarjeta, caducidad, cvc2, importe, tipoOper, factura)
            {
                var valRet = "TpvpcImplantado no esta inicializado. ";
                var codRet = 0;

                if (iniTpvpcImplantado != 0) {
                    codRet = TpvpcImplantado.OperManualExt(tarjeta, caducidad, cvc2, importe, factura, tipoOper, valRet);
                    if (codRet != 0)
                        valRet = codRet;
                    else
                        valRet = TpvpcImplantado.ResultOper; /* Obligatorio desde Internet Explorer recuperar el valor*/
                }

                return valRet;
            }

            function ParaTpvpcImplantado()
            {
                if (iniTpvpcImplantado != 0) {
                    TpvpcImplantado.ParaTpvpcLatente();
                    iniTpvpcImplantado = 0;
                    document.forms.FRMTPVPC.CMBPUERTO.disabled = false;
                    document.forms.FRMTPVPC.CMBVERWS.disabled = false;
                }
            }

            function IniTpvpcImplantado()
            {

                var resultado = 0;
                //Sólo es necesario llamar  la función, la primera vez que se inicie la página o aplicación.

                if (iniTpvpcImplantado == 0) {
                    var puerto = document.forms.FRMTPVPC.CMBPUERTO.value;
                    var verws = document.forms.FRMTPVPC.CMBVERWS.value;

                resultado = TpvpcImplantado.IniTpvpcLatente(document.forms.FRMTPVPC.TXTCOMERCIO.value,
                                                                document.forms.FRMTPVPC.TXTTERMINAL.value,
                                                                document.forms.FRMTPVPC.TXTCLAVE.value,
                                                                puerto,
                                                                verws);
                if (resultado == 0) {

                        iniTpvpcImplantado = 1;
                        document.forms.FRMTPVPC.CMBPUERTO.disabled = true;
                        document.forms.FRMTPVPC.CMBVERWS.disabled = true;

                        //window.alert("TpvpcImplantado Iniciado");
                } else {
                        window.alert("Error al Iniciar [" + resultado + "]");
                }
                }
                return resultado;
            }



            function TrataPeticion()
            {

                var resultado = "";
                var tipoOper = "";

                tipoOper = document.forms.FRMTPVPC.CMBTIPO.value;

                document.forms.FRMTPVPC.TXTRESULT.value = "Procesando Petición " + tipoOper;

                if (tipoOper == "PAGO") {
                    resultado = RealizarOperacion(document.forms.FRMTPVPC.TXTIMPORTE.value,
                                                            "PAGO",
                                                            document.forms.FRMTPVPC.TXTFACTURA.value);
                } else if (tipoOper == "PAGO_MANUAL") {
                    resultado = RealizarOperManual(document.forms.FRMTPVPC.TXTTARJETA.value,
                                                document.forms.FRMTPVPC.TXTCADTARJ.value,
                                                document.forms.FRMTPVPC.TXTCVC2.value,
                                                document.forms.FRMTPVPC.TXTIMPORTE.value,
                                                "PAGO",
                                                document.forms.FRMTPVPC.TXTFACTURA.value);
                } else if (tipoOper == "PREAUTORIZACION") {
                    resultado = RealizarOperacion(document.forms.FRMTPVPC.TXTIMPORTE.value,
                                                            "PREAUTORIZACION",
                                                            document.forms.FRMTPVPC.TXTFACTURA.value);
                } else if (tipoOper == "PREAUTORIZACION_MANUAL") {
                    resultado = RealizarOperManual(document.forms.FRMTPVPC.TXTTARJETA.value,
                                                document.forms.FRMTPVPC.TXTCADTARJ.value,
                                                document.forms.FRMTPVPC.TXTCVC2.value,
                                                document.forms.FRMTPVPC.TXTIMPORTE.value,
                                                "PREAUTORIZACION",
                                                document.forms.FRMTPVPC.TXTFACTURA.value);
                } else if (tipoOper == "DEVOLUCION") {
                    resultado = RealizarComunicacionContable(document.forms.FRMTPVPC.TXTPEDIDOBASE.value,
                                                        document.forms.FRMTPVPC.TXTRTS.value,
                                                        document.forms.FRMTPVPC.TXTIMPORTE.value,
                                                        "DEVOLUCION",
                                                        document.forms.FRMTPVPC.TXTFACTURA.value);
                } else if (tipoOper == "CONFIRMACION") {
                    resultado = RealizarComunicacionContable(document.forms.FRMTPVPC.TXTPEDIDOBASE.value ,
                                                    document.forms.FRMTPVPC.TXTRTS.value,
                                                    document.forms.FRMTPVPC.TXTIMPORTE.value,
                                                    "CONFIRMACION",
                                                    document.forms.FRMTPVPC.TXTFACTURA.value);
                } else if (tipoOper == "FINCOMUNICACION") {
                    ParaTpvpcImplantado();
                } else {
                    resultado = "Operacion No Definida";
                }

                if (isNaN(resultado)) {
                    while (resultado.indexOf("><") != -1){
                        resultado =  resultado.replace("><", ">\n<");
                    }
                }
                document.forms.FRMTPVPC.TXTRESULT.value = "Resultado Operacion :\n" + resultado;

                return resultado;

            }
            function EnviarPeticion(){

                //alert("EnviarPeticion");

                $("#BTNACEPTAR").hide();


                var xml = TrataPeticion();

                // var xml='<Operaciones version="6.0">'+
                //         '<resultadoOperacion>'+
                //         '<tipoPago>PAGO</tipoPago>'+
                //         '<importe>%s</importe>'+
                //         '<moneda>978</moneda>'+
                //         '<tarjetaClienteRecibo>************4014</tarjetaClienteRecibo>'+
                //         '<tarjetaComercioRecibo>************4014</tarjetaComercioRecibo>'+
                //         '<marcaTarjeta>1</marcaTarjeta>'+
                //         '<caducidad>0000</caducidad>'+
                //         '<comercio>025256579</comercio>'+
                //         '<terminal>1</terminal>'+
                //         '<pedido>10511</pedido>'+
                //         '<tipoTasaAplicada>CRED</tipoTasaAplicada>'+
                //         '<identificadorRTS>070014201117110907251001</identificadorRTS>'+
                //         '<fechaOperacion>2020-11-17 11:09:07.0</fechaOperacion>'+
                //         '<estado>F</estado>'+
                //         '<resultado>Autorizada</resultado>'+
                //         '<codigoRespuesta>639266</codigoRespuesta>'+
                //         '<firma>EFB0BFE7F267D1AA9BB1492C51EF9D6B67D59E3B</firma>'+
                //         '<operacionemv>true</operacionemv>'+
                //         '<resverificacion>0000000000</resverificacion>'+
                //         '<conttrans>000002</conttrans>'+
                //         '<sectarjeta>00</sectarjeta>'+
                //         '<idapp>A0000000031010</idapp>'+
                //         '<operContactLess>TRUE</operContactLess>'+
                //         '<autenticadoPorPin>TRUE</autenticadoPorPin>'+
                //         '<Literales>'+
                //         '<autenticadoPorPin>OPERACION AUT MOVIL. FIRMA NO NECESARIA.</autenticadoPorPin>'+
                //         '</Literales>'+
                //         '</resultadoOperacion>'+
                //         '</Operaciones>';


                var imp = $("#TXTIMPORTE").val();
                xml = xml.replace('%s', imp);

                const xmlDoc = $.parseXML( xml );

                $xml = $( xmlDoc );
                $resultado = $xml.find("resultado");

                var res_auth = [];

                $xml.find('autenticadoPorPin').each(function (index) {

                    res_auth[index] = $( this ).text() ;
                });

                if (res_auth[0] == 'TRUE')
                    $("#auth").append(res_auth[1]);


                if ($resultado.text() == "Autorizada")
                    store(xml);
                else
                    $("#BTNACEPTAR").show();


            }

            function store(xml){
                var url = '/tpvpc/store';

                var data = {
                    xml: xml,
                    empresa_id: $("#empresa_id").val(),
                    paciente_id: $("#paciente_id").val(),
                    tpv_id: $("#tpv_id").val(),
                };



                $.ajax({
                    headers: {
                    'X-CSRF-Token': token
                    },
                    type: "POST",
                    data: data,
                    url: url,
                    async: true,
                    error: function( objAJAXRequest, strError ){
                        alert(objAJAXRequest.status);
                        alert("Error store MovTPV "+strError.textStatus );
                    },
                    success: function(res){

                        $("#comercio").append(res.comercio);
                        $("#titular").append(res.titular_tarjeta);
                        $("#tarjeta").append(res.tarjeta);
                        $("#caducidad").append(res.caducidad);

                        $("#codigo_respuesta").append(res.codigo_respuesta);
                        $("#pedido").append(res.pedido);

                        $("#fecha").append(res.fecha);
                        $("#hora").append(res.hora);
                        $("#importe").append(res.importe);

                        $("#telefono").append(res.telefono);

                        $("#pasarela").fadeOut();
                        $("#recibo").fadeIn();

                        setTimeout(function(){window.print();}, 2000);

                    }
                });
            }
        </script>
    </head>
    <body>
        <div id="pasarela">
            <h1>PASARELA TPV-PC</h1>


                <form id="FRMTPVPC" name="FRMTPVPC" action="#" >
                    <p><textarea class="respuesta" id="TXTRESULT" class="form-control" name="TXTRESULT"></textarea></p>
                    <br/>
                    <p><input id="BTNACEPTAR" onclick="EnviarPeticion()" name="BTNACEPTAR" class="btn" type="button" value="Aceptar" /></p>

                             <input id="CMBTIPO" maxlength="11" name="CMBTIPO" type="hidden" value="PAGO"/>
                            <input id="TXTPEDIDOBASE" maxlength="11" name="TXTPEDIDOBASE" type="hidden" />
                            <input id="TXTRTS" maxlength="24" name="TXTRTS" type="hidden" />
                            <input id="TXTIMPORTE" maxlength="12" name="TXTIMPORTE" type="hidden" value="<?php echo $importe;?>" />
                            <input id="TXTFACTURA" maxlength="800" name="TXTFACTURA" type="hidden" />
                            <input id="CMBPUERTO" name="CMBPUERTO" type="hidden" value="<?php echo $tpv->puerto;?>"/>
                            <input id="CMBVERWS" name="CMBVERWS" type="hidden"  value="<?php echo $tpv->version;?>" />
                            <input id="TXTCOMERCIO" maxlength="9" name="TXTCOMERCIO" type="hidden" value="<?php echo $tpv->comercio;?>" />
                            <input id="TXTTERMINAL" type="hidden" maxlength="2" value="<?php echo $tpv->terminal;?>" />
                            <input id="TXTCLAVE" maxlength="20" name="TXTCLAVE"  type="hidden" value="<?php echo $tpv->firma;?>" />

                </form>

                {{-- <object codebase="https://tpvpc.sermepa.es/TPV_PC/ActiveX/AxTPVpcPinPadWS30.CAB#Version=2,1,2,6" height="0" width="0" classid="CLSID:F2F95F96-1EE9-45FD-9F74-6DF23B7A02EE" id="TPVpcPinPad"></object> --}}
                <!-- Descargar e instalar librera desde http://sas-d.sermepa.es/TPV_PC/implantado.html -->
                <object height="0" width="0" classid="CLSID:DB09CE0A-6E1B-4107-A465-1DBA1C2DDA66" id="TpvpcImplantado"></object>


                <form id="datos">
                    <input id="paciente_id" type="hidden" value="<?php echo $paciente_id;?>" />
                    <input id="tpv_id" type="hidden" value="<?php echo $tpv->id;?>" />
                    <input id="empresa_id" type="hidden" value="<?php echo $empresa_id;?>" />
                </form>
        </div>
        <div id="recibo" class="tickets" style="display:none">
            <table border="0" align="center">
                <tr><td colspan="2" align="center" class="negrita">{{ $tpv->nombre_comercio}}</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td align="center" colspan="2">Comercio: <span id="comercio"></span></td></tr>
                <tr><td colspan="2" align="left" id="titular_tarjeta"></td></tr>
                <tr><td colspan="2"></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" align="left">{{ $tpv->nombre}}</td></tr>
                <tr><td id="tarjeta"></td><td>Cad: <span id="caducidad"></span></td></tr>
                <tr><td>Aut: <span id="codigo_respuesta"></span></td><td>Op: <span id="pedido"></span></td></tr>
                <tr><td>Fecha: <span id="fecha"></span></td><td>Hora: <span id="hora"></span></td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" class="negrita" align="center" id="importe"></td></tr>
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
                <tr><td id="auth" style="font-size: 9px;"></td></tr>
                <tr><td align="center">¡Gracias por su visita!</td></tr>
                <tr><td align="center">Tfn: <span id="telefono"></span></td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </div>
    </body>
</html>
