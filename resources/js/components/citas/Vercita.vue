<template>

        <div v-if="cita.id > 0">

            <v-row >
                <v-col
                    class="pa-1"
                    cols="12"
                    md="3"
                >
                    <v-text-field
                        class="body-1"
                        hide-details="true"
                        outlined
                        dense
                        v-model="computedNomPac"
                        :label="computedLabelPaciente"
                        :background-color="cita.paciente.cumple != '' ? 'lime lighten-5' : 'blue lighten-5'"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="3"
                >
                    <v-text-field
                        class="body-1"
                        hide-details="true"
                        outlined
                        dense
                        v-model="computedCitaDateTime"
                        :label="computedLabelCita"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="1"
                >
                    <v-text-field
                        class="body-1"
                        hide-details="true"
                        outlined
                        dense
                        v-model="cita.facultativo.alias"
                        label="Facultativo"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="4"
                    md="1"
                >
                    <v-text-field
                        class="body-1"
                        hide-details="true"
                        outlined
                        dense
                        v-model="computedBono"
                        label="Bono"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="3"
                    md="1"
                >
                    <v-text-field
                        class="body-1 inputNumber"
                        hide-details="true"
                        outlined
                        dense
                        v-model="computedImporte"
                        label="Importe"
                        :background-color="colorImporte"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="5"
                    md="1"
                >
                    <v-text-field
                        class="body-1"
                        hide-details="true"
                        outlined
                        dense
                        v-model="cita.estado.nombre"
                        label="Estado"
                        :background-color="computedColorEstado"
                        readonly
                    >
                    </v-text-field>
                </v-col>

                <v-col
                    class="pa-1"
                    cols="3"
                    md="1"
                >
                    <v-text-field
                        class="body-1 inputNumber"
                        v-if="saldo!=0"
                        hide-details="true"
                        outlined
                        dense
                        v-model="computedSaldo"
                        label="Saldo"
                        :background-color="colorSaldo"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="1"
                    style="max-height:9px"
                >
                    <v-avatar size="96" v-if="cita.paciente.foto!=false">
                        <img class="img-fluid" :src="cita.paciente.foto">
                    </v-avatar>
                </v-col>


                <v-col
                    class="pa-1"
                    cols="12"
                    md="2"
                >
                    <v-text-field
                        class="body-1"
                        hide-details="true"
                        outlined
                        dense
                        v-model="cita.tratamiento.nombre"
                        label="Tratamiento"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        class="caption"
                        hide-details="true"
                        outlined
                        dense
                        v-model="computedNotasPaciente"
                        :label="computedLabelNotas"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="2"
                >
                    <v-text-field
                        class="caption"
                        v-if="isAdmin && cita.paciente.notas_adm != null"
                        hide-details="true"
                        outlined
                        dense
                        v-model="cita.paciente.notas_adm"
                        label="Admin"
                        background-color="red lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="3"
                >
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="primary"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            @click="goAddCita"
                            >
                                <v-icon>mdi-calendar-plus</v-icon>
                            </v-btn>
                        </template>
                        <span>Nueva Cita</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="primary"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            :disabled="!computedEditar"
                            @click="goEditCita"
                            >
                                <v-icon>mdi-calendar-edit</v-icon>
                            </v-btn>
                        </template>
                        <span>Editar Cita</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="green darken-3"
                                icon
                                v-bind="attrs"
                                v-on="on"
                                :disabled="!computedTratado"
                                @click="setTratado"
                            >
                                <v-icon>mdi-calendar-check</v-icon>
                            </v-btn>
                        </template>
                        <span>Tratado Cita</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="red darken-3"
                                icon
                                v-bind="attrs"
                                v-on="on"
                                @click="setAnulado"
                                :disabled="!computedAnular"
                            >
                                <v-icon>mdi-calendar-remove</v-icon>
                            </v-btn>
                        </template>
                        <span>Anular Cita</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="primary"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            @click="goRecordar"
                            >
                                <v-icon>mdi-printer</v-icon>
                            </v-btn>
                        </template>
                        <span>Recordar Cita</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="primary"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            @click="goUltimas"
                            >
                                <v-icon>mdi-calendar-multiselect</v-icon>
                            </v-btn>
                        </template>
                        <span>10 últimas citas</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="yellow darken-1"
                                icon
                                v-bind="attrs"
                                v-on="on"
                                :disabled="!computedCobrar"
                                @click="cobrar"
                            >
                                <v-icon>mdi-currency-eur</v-icon>
                            </v-btn>
                        </template>
                        <span>Cobrar Cita</span>
                    </v-tooltip>
                    <v-tooltip top v-if="isRoot">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="yellow darken-1"
                                icon
                                v-bind="attrs"
                                v-on="on"
                                :disabled="!computedCobrar"
                                @click="cobrarDirecto"
                            >
                                <v-icon>mdi-cash-multiple</v-icon>
                            </v-btn>
                        </template>
                        <span>Cobrar todo en efectivo</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :disabled="!isSupervisor"
                                color="orange darken-1"
                                icon
                                v-bind="attrs"
                                v-on="on"
                                @click="anularCobro"
                            >
                                <v-icon>mdi-currency-eur-off</v-icon>
                            </v-btn>
                        </template>
                        <span>Cancelar cobros</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="blue darken-4"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            :disabled="!computedCitasBono"
                            @click="goCitasBono"
                            >
                                <v-icon>mdi-alpha-b-circle-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Citas bono</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            :disabled="!computedWhatsApp"
                            @click="goWhatsapp"
                            color="green"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            >
                                <v-icon>mdi-whatsapp</v-icon>
                            </v-btn>
                        </template>
                        <span>Enviar Whatsapp</span>
                    </v-tooltip>
                </v-col>

                <v-col
                    class="pa-1"
                    cols="12"
                    md="2"
                >
                    <v-textarea
                        class="caption"
                        hide-details="true"
                        rows=2
                        outlined
                        dense
                        v-model="computedNotas"
                        label="Notas"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-textarea>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="6"
                >
                    <v-textarea
                        class="caption"
                        v-if="hasClinic"
                        hide-details="true"
                        rows=2
                        dense
                        outlined
                        :label="computedLabelHistoria"
                        background-color="blue lighten-5"
                        :value="computedHistoria"
                        readonly
                        >
                    </v-textarea>
                </v-col>
                <v-col
                    class="pa-1"
                    cols="12"
                    md="4"
                >
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            elevation="2"
                            x-small
                            v-bind="attrs"
                            v-on="on"
                            :disabled="computedReasignar"
                            @click="goReasignar"
                            >
                                Reasignar
                            </v-btn>
                        </template>
                        <span>Reasignar paciente</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            elevation="2"
                            x-small
                            v-bind="attrs"
                            v-on="on"
                            @click="tpv"
                            >
                                TPV
                            </v-btn>
                        </template>
                        <span>TPV productos</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :disabled="!isSupervisor"
                                elevation="2"
                                x-small
                                v-bind="attrs"
                                v-on="on"
                                @click="goJustificante"
                            >
                                Justificante
                            </v-btn>
                        </template>
                        <span>Generar Justificante</span>
                    </v-tooltip>
                    <v-tooltip top v-if="cita.envio_sms != null">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :disabled="!isAdmin"
                                :loading="loading_sms"
                                elevation="2"
                                x-small
                                color="red--text darken-4"
                                v-bind="attrs"
                                v-on="on"
                                @click="goCancelarSMS"
                            >
                                SMS
                            </v-btn>
                        </template>
                        <span>Cancelar SMS</span>
                    </v-tooltip>
                    <v-tooltip top v-if="cita.envio_sms == null && cita.paciente.notificar == 'S'">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :disabled="!isAdmin"
                                :loading="loading_sms"
                                elevation="2"
                                x-small
                                color="green--text darken-4"
                                v-bind="attrs"
                                v-on="on"
                                @click="goEnviarSMS"
                            >
                                SMS
                            </v-btn>
                        </template>
                        <span>Enviar SMS</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                :disabled="!isAdmin"
                                elevation="2"
                                x-small
                                v-bind="attrs"
                                v-on="on"
                            >
                                Web
                            </v-btn>
                        </template>
                        <span>Validar Cita web</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                elevation="2"
                                x-small
                                v-bind="attrs"
                                @click="goPaciente"
                                v-on="on"
                                :color="computedBtnPac"
                            >
                                Paciente <span v-if="noleidos > 0">({{noleidos}}) sin leer</span>
                            </v-btn>
                        </template>
                        <span>{{computedLabelTelPaciente}}</span>
                    </v-tooltip>
                    <v-tooltip top v-if="isRoot">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                text
                                icon
                                v-bind="attrs"
                                v-on="on"
                                @click="goHistorico"
                            >
                                <v-icon>mdi-paperclip</v-icon>
                            </v-btn>
                        </template>
                        <span>Histórico citas</span>
                    </v-tooltip>
                    <v-tooltip top v-if="isRoot && cita.estado_id == 4">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="red darken-3"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            @click="destroyReg"
                            >
                                <v-icon>mdi-trash-can-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Eliminar Cita</span>
                    </v-tooltip>
                </v-col>
            </v-row>
        </div>


</template>
<script>
import {mapGetters} from 'vuex';
import {mapActions} from "vuex";
import moment from 'moment'
export default {
    props:['cita','historia','saldo','bono','event',
            'action_cita','reload_id','show_loading',
            'dialog_ultimas','dialog_bono','numero_bono',
            'whatsapp','paciente_id', 'ultima','noleidos','dialog_tpv'],
    components: {

    },
    data () {
        return {
            font:'caption',
            verHistoria: false,
            edit: false,
            loading_sms: false,
            hoy: new Date().toISOString().substr(0, 10),
        }
    },
    mounted(){

    },
    computed: {
        ...mapGetters([
            'isRoot',
            'isAdmin',
            'isSupervisor',
            'hasContact',
            'hasClinic',
            'hasCobros'
        ]),
        computedWhatsApp(){
            if (this.whatsapp == false || !this.isAdmin)
                return false;

            return true;
        },
        computedNomPac(){

            return this.cita.paciente.nom_ape+" ("+this.cita.paciente.edad+") "+this.cita.paciente.cumple;
        },
        computedLabelHistoria(){
            if (this.historia == null) return 'Historia';

            return 'Historia: '+this.historia.username+' '+this.formatDateTime(this.historia.updated_at);
        },
        computedBtnPac(){

            return this.noleidos > 0 ? 'orange' : 'gray';
        },
        computedLabelPaciente(){
            const medio = this.cita.paciente.medio_id != null ? ' ('+this.cita.paciente.medio.nombre+')' : '';

            return (this.isSupervisor) ? 'Paciente: '+this.cita.paciente.username+' '+this.formatDateTime(this.cita.paciente.updated_at)+medio : 'Paciente';
        },
        computedLabelNotas(){
            const sms = this.cita.envio_sms != null ? ' SMS On: '+this.formatDateTime(this.cita.envio_sms) : this.cita.paciente.notificar == 'S'? 'SMS On' : 'SMS Off';
            return (this.isSupervisor) ? 'Notas: '+sms : 'Notas Paciente';
        },
        computedLabelCita(){
            return (this.isSupervisor) ? 'Cita: '+this.cita.username+' '+this.formatDateTime(this.cita.updated_at) : 'Cita';
        },
        computedLabelTelPaciente(){
            return this.hasContact ? this.formatPhoneNumber(this.cita.paciente.telefonom)+" - "+this.formatPhoneNumber(this.cita.paciente.telefono1) : 'Paciente';
        },
        computedReasignar(){
            return (this.cita.estado_id > 1 || this.paciente_id == null);
        },
        computedEditar(){
            return this.cita.estado_id == 1 ? true : false;
        },
        computedTratado(){
            if (this.cita.fecha > this.hoy) return false;

            return this.cita.estado_id == 1 ? true : false;
        },
        computedAnular(){
            if (!this.isAdmin) return false;
            return this.cita.estado_id == 3 ? false : true;
        },
        computedCobrar(){
            return (this.hasCobros && this.saldo > 0);
        },
        computedCitasBono(){
            return this.cita.bono > 0;
        },
        computedColorEstado(){

            if (this.cita.estado_id == 4)
                return 'grey lighten-5';
            if (this.cita.estado_id == 3 || this.cita.importe == 0)
                return 'green lighten-5';
            if (this.cita.estado_id == 2)
                return 'orange lighten-5';
            if (this.cita.estado_id == 1)
                return 'blue lighten-5';
        },
        colorImporte(){
            return 'blue lighten-5';
        },
        colorSaldo(){
            return (this.cita.importe > 0 ) ? 'red lighten-5' : 'blue lighten-5';
        },
        computedHistoria(){
            if (this.historia == null) return null;

            var mc = '';
            var ex = '';
            var ju = '';
            var tr = '';

            if (this.historia.motivo != null)
                mc = 'MC: '+this.historia.motivo;
            if (this.historia.exploracion != null)
                ex = ' EX: '+this.historia.exploracion;
            if (this.historia.juicio != null)
                ju = ' JU: '+this.historia.juicio
            if (this.historia.tratamiento != null)
                tr = ' TR: '+this.historia.tratamiento

            return mc + ex + ju + tr;
        },
        computedNotas(){
            return this.cita.notas;
        },
        computedNotasPaciente(){

            if (this.cita.paciente.notas1 == null)
                this.cita.paciente.notas1 = '';

            if (this.cita.paciente.notas2 == null)
                this.cita.paciente.notas2 = '';
            else
                this.cita.paciente.notas2 = ' Tm: ' + this.cita.paciente.notas2;

            return this.cita.paciente.notas1 + this.cita.paciente.notas2;
        },
        computedMedio(){
            return (this.cita.paciente.medio_id != null) ? this.cita.paciente.medio.nombre : '-';
        },
        computedSMS(){
            moment.locale('es');
            return (this.cita.envio_sms != null) ? moment(this.cita.envio_sms).format('D/MM/YYYY H:mm:ss') : 'n/d';
        },
        computedUsername(){
            moment.locale('es');
            return  this.cita.username + " " + moment(this.cita.updated_at).format('D/MM/YYYY H:mm:ss')
        },
        computedCitaDateTime(){
            if (this.cita.hora == null) return 'n/d';
            moment.locale('es');
            const start = this.cita.fecha + " " + this.cita.hora;

            return  moment(start).format('dddd, D [de] MMMM [de] YYYY [-] HH:mm') + " " + this.ultima;
        },
        computedImporte(){
            return this.getCurrencyFormat(this.cita.importe);
        },
        computedSaldo(){
            return this.getCurrencyFormat(this.saldo);
        },
        computedBono(){

            if (this.bono == null || this.cita.fecha > this.hoy) return null;
            if (this.bono.numero_bono > 0)
                return  this.bono.numero_bono+" ("+this.bono.resto + "/" + this.bono.sesiones + ") +" + (this.bono.sesiones - this.bono.resto);
        }
    },

    methods:{
         ...mapActions([
            'setCita',
        ]),
        formatDate(f){
            if (f == null) return '';
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        formatDateTime(f){
            if (f == null) return '';
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY H:mm:ss');
        },
        formatPhoneNumber(ph) {

            if (ph == null) return '';

            var match = ph.match(/^(\d{3})(\d{2})(\d{2})(\d{2})$/)

            if (match) {
                return '(' + match[1] + ' ' + match[2] + ' ' + match[3] + ' ' + match[4] + ')';
            }
            return '';
        },
        getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        goAddCita(){
            this.$emit('update:show_loading', true);
            this.$emit('update:action_cita', 'A');
        },
        goEditCita(){

            this.goCancelarSMS();

            this.$emit('update:show_loading', true);
            this.$emit('update:action_cita', 'E');
        },
        // change(id) {

        //     this.$emit('update:paciente_id', id);

        // },
        goPaciente(){
            this.$router.push({ name: 'paciente.edit', params: { id: this.cita.paciente_id } })
        },
        goUltimas(){
            this.$emit('update:numero_bono', null);
            this.$emit('update:dialog_ultimas', true);
        },
        goCitasBono(){
            this.$emit('update:numero_bono', this.cita.bono);
            this.$emit('update:dialog_bono', true);
        },
        goRecordar(){
            window.open('/citas/recordar/'+this.cita.paciente_id,'Recordar','toolbar=no,resizable=no, width=500, height=500, scrollbar=no, status=no');
        },
        goReasignar(){
            var url = "/citas/reasignar";

            axios.put(url, {id: this.cita.id, paciente_id: this.paciente_id})
                .then(res => {
                    //this.$emit('update:cita', res.data.cita);
                    var id = this.reload_id + 1;
                    this.$emit('update:paciente_id', null);
                    this.$emit('update:reload_id', id);
                })
                .catch(err => {
                    console.log(err);
                });


        },
        setTratado(){

            this.$emit('update:show_loading', true);

            var estado_id = 2;

            var url = "/citas/"+this.cita.id+"/estado";
            axios.put(url, {estado_id: estado_id})
                .then(res => {
                    this.$emit('update:cita', res.data.cita);
                    var id = this.reload_id + 1;
                    this.$emit('update:reload_id', id);
                })
                .catch(err => {
                    console.log(err);
                });

        },
        setAnulado(){

            if (this.cita.estado_id <= 2)
                var estado_id = 4;
            else if(this.cita.estado_id == 4)
                var estado_id = 1;
            else var estado_id = false;

            if (estado_id != false){
                var url = "/citas/"+this.cita.id+"/estado";
                axios.put(url, {estado_id: estado_id})
                    .then(res => {
                        var id = this.reload_id + 1;
                        this.$emit('update:reload_id', id);
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }

        },
        destroyReg () {

            if (confirm("Borrar registro?")){

                axios.post('/citas/citas/'+this.cita.id,{_method: 'delete'})
                    .then(response => {

                        this.setCita({fecha: this.ultima.fecha,
                                      facultativo_id: this.ultima.facultativo_id,
                                      type: this.ultima.type,
                                      id: null});


                        var id = this.reload_id + 1;
                        this.$emit('update:reload_id', id);
                })
                .catch(err => {
                        console.log(err);
                });
            }


        },
        tpv(){
            this.$emit('update:dialog_tpv', true);
        },
        cobrar(){
            this.$emit('update:action_cita', 'C');
        },
        cobrarDirecto(){
            this.$emit('update:show_loading', true);
            axios.post('/citas/cobro/directo', {
                paciente_id: this.cita.paciente_id,
                importe: this.saldo
            })
            .then(res => {

                this.$emit('update:reload_id', this.reload_id + 1);

            })
            .catch(err => {
                this.$emit('update:show_loading', false);
                this.$toast.error(err.response.data.message);
            })
            .finally(()=>{

            });

        },
        anularCobro(){
            this.$emit('update:action_cita', 'CC');
        },
        goWhatsapp(){
            window.open(this.whatsapp,'Enviar WhatsApp','toolbar=no,resizable=no, width=400, height=500, scrollbar=no, status=no');

        },
        goEnviarSMS(){

            this.loading_sms = true;
            axios.get('/tools/sms/'+this.cita.id+'/sendone')
                .then(res => {
                    this.loading_sms = false;
                    var id = this.reload_id + 1;
                    this.$emit('update:reload_id', id);
                })
                .catch(err => {
                    console.log(err);
                });

        },
        goCancelarSMS(){
            this.loading_sms = true;
            axios.get('/tools/sms/'+this.cita.id+'/cancel')
                .then(res => {
                    this.loading_sms = false;
                    var id = this.reload_id + 1;
                    this.$emit('update:reload_id', id);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        goHistorico(){
            this.$router.push({ name: 'hcita.show', params: { id: this.cita.id } })
        },
        goJustificante(){
            this.$router.push({ name: 'justificante.show', params: { id: this.cita.id } })
        }

    }
}
</script>
<style scoped>

.inputNumber >>> input {
  text-align: center;
  -moz-appearance:textfield;
}
</style>
