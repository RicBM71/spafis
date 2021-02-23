<template>
    <div>
            <p>{{importe}}</p>
        <div>
            <form id="FRMTPVPC" name="FRMTPVPC" action="#" >
			<table class="table">

				<tr>
					<td><textarea id="TXTRESULT" class="form-control" name="TXTRESULT" style="height: 135px"></textarea></td>
				</tr>
				<tr>
					<td align="center">
						<input id="BTNACEPTAR" onclick="EnviarPeticion()" name="BTNACEPTAR" class="btn btn-success" type="button" value="Aceptar" />
		 			</td>
	 			</tr>
	 			<tr><td>
				 	<input id="CMBTIPO" maxlength="11" name="CMBTIPO" type="hidden" value="PAGO"/>
			        <input id="TXTPEDIDOBASE" maxlength="11" name="TXTPEDIDOBASE" type="hidden" />
			        <input id="TXTRTS" maxlength="24" name="TXTRTS" type="hidden" />
			        <input id="TXTIMPORTE" maxlength="12" name="TXTIMPORTE" type="hidden" value="<?php echo $importe;?>" />
					<input id="TXTFACTURA" maxlength="800" name="TXTFACTURA" type="hidden" />
				    <input id="CMBPUERTO" name="CMBPUERTO" type="hidden" value="<?php echo $this->tpv->puerto;?>"/>
			    	<input id="CMBVERWS" name="CMBVERWS" type="hidden"  value="<?php echo $this->tpv->version;?>" />
			    	<input id="TXTCOMERCIO" maxlength="9" name="TXTCOMERCIO" type="hidden" value="<?php echo $this->tpv->comercio;?>" />
			    	<input id="TXTTERMINAL" type="hidden" maxlength="2" value="<?php echo $this->tpv->terminal;?>" />
			    	<input id="TXTCLAVE" maxlength="20" name="TXTCLAVE" style="width: 220px" type="hidden" value="<?php echo $this->tpv->firma;?>" />
			    </td></tr>
	    	</table>
		</form>

		<object codebase="https://tpvpc.sermepa.es/TPV_PC/ActiveX/AxTPVpcPinPadWS30.CAB#Version=2,1,2,6" height="0" width="0" classid="CLSID:F2F95F96-1EE9-45FD-9F74-6DF23B7A02EE" id="TPVpcPinPad"></object>
		<!-- Descargar e instalar librera desde http://sas-d.sermepa.es/TPV_PC/implantado.html -->
		<object height="0" width="0" classid="CLSID:DB09CE0A-6E1B-4107-A465-1DBA1C2DDA66" id="TpvpcImplantado" ref="TpvpcImplantado"></object>
	</div>

    </div>
</template>
<script>
export default {
    props:['importe'],
    data: () => ({
        iniTpvpcImplantado: 0,

    }),
    mounted(){
        this.inicializarTpv();
        alert('montado')
    },
    methods:{
         RealizarOperacion(importe, tipoOper, factura)
            {
                var valRet = "TpvpcImplantado no esta inicializado";
                var codRet = 0;
                if (this.iniTpvpcImplantado != 0) {
                    codRet = TpvpcImplantado.OperPinPad(importe, factura, tipoOper, valRet);
                    if (codRet != 0) {
                        valRet = codRet;
                    } else {
                        valRet = TpvpcImplantado.ResultOper;
                    }
                }

                return valRet;
            },
        RealizarComunicacionContable(pedido, rts, importe, tipoOper, factura)
            {
                var valRet = "TpvpcImplantado no esta inicializado. ";
                var codRet = 0;

                if (this.iniTpvpcImplantado != 0) {
                    codRet = TpvpcImplantado.OperComContable(pedido, rts, importe, factura, tipoOper, valRet);
                    if (codRet != 0)
                        valRet = codRet;
                    else
                        valRet = TpvpcImplantado.ResultOper;
                }

                return valRet;
            },


        RealizarOperManual(tarjeta, caducidad, cvc2, importe, tipoOper, factura)
            {
                var valRet = "TpvpcImplantado no esta inicializado. ";
                var codRet = 0;

                if (this.iniTpvpcImplantado != 0) {
                    codRet = TpvpcImplantado.OperManualExt(tarjeta, caducidad, cvc2, importe, factura, tipoOper, valRet);
                    if (codRet != 0)
                        valRet = codRet;
                    else
                        valRet = TpvpcImplantado.ResultOper; /* Obligatorio desde Internet Explorer recuperar el valor*/
                }

                return valRet;
            },

        ParaTpvpcImplantado()
            {
                if (this.iniTpvpcImplantado != 0) {
                    TpvpcImplantado.ParaTpvpcLatente();
                    this.iniTpvpcImplantado = 0;
                    document.forms.FRMTPVPC.CMBPUERTO.disabled = false;
                    document.forms.FRMTPVPC.CMBVERWS.disabled = false;
                }
            },

        inicializarTpv()
            {
                var resultado = 0;
                //Sólo es necesario llamar  la función, la primera vez que se inicie la página o aplicación.
                if (this.iniTpvpcImplantado == 0) {
                    var puerto = document.forms.FRMTPVPC.CMBPUERTO.value;
                    var verws = document.forms.FRMTPVPC.CMBVERWS.value;
                resultado = this.$refs.TpvpcImplantado.IniTpvpcLatente(document.forms.FRMTPVPC.TXTCOMERCIO.value,
                                                                document.forms.FRMTPVPC.TXTTERMINAL.value,
                                                                document.forms.FRMTPVPC.TXTCLAVE.value,
                                                                puerto,
                                                                verws);
                if (resultado == 0) {
                        this.iniTpvpcImplantado = 1;
                        document.forms.FRMTPVPC.CMBPUERTO.disabled = true;
                        document.forms.FRMTPVPC.CMBVERWS.disabled = true;
                        //window.alert("TpvpcImplantado Iniciado");
                } else {
                        window.alert("Error al Iniciar [" + resultado + "]");
                }
                }
                return resultado;
            },

        TrataPeticion()
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
    }
}
</script>
<style scoped>

</style>
