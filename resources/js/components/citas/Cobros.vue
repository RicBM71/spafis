<template>
    <div>
            <v-row justify="center">
                <v-dialog
                    v-model="dialog_cobro"
                    persistent
                    max-width="1000px"
                    transition="dialog-bottom-transition"
                    >
                <v-card>
                    <v-toolbar
                        dark
                        color="primary"
                    >
                        <v-toolbar-title>Cobros</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                            icon
                            dark
                            @click="cancelar"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                    <v-row>
                        <v-col cols="6">
                            <v-card class="pa-2">
                                <v-row>
                                    <v-col
                                        cols="10"
                                        offset-md="1"
                                    >
                                        <v-text-field
                                            hide-details="true"
                                            outlined
                                            dense
                                            :value="cita.paciente.nom_ape"
                                            label="Paciente"
                                            background-color="blue lighten-5"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        cols="10"
                                        offset="1"
                                    >
                                        <v-autocomplete
                                            v-model="recomendado_id"
                                            :items="recomendados"
                                            :loading="isLoading"
                                            :search-input.sync="search_recomendado"
                                            label="Sumar citas"
                                            placeholder="Indicar nom,ape"
                                            prepend-icon="mdi-database-search"
                                            append-outer-icon="mdi-account-plus-outline"
                                            clearable
                                            @click:clear="clearRec"
                                            @click:append-outer="addCitas"
                                        ></v-autocomplete>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        cols="12"
                                    >
                                        <v-simple-table dense>
                                            <template v-slot:default>
                                                <thead>
                                                    <tr>
                                                    <th class="text-left">
                                                        Fecha
                                                    </th>
                                                    <th class="text-left">
                                                        Tratamiento
                                                    </th>
                                                    <th class="text-right">
                                                        Importe
                                                    </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                    v-for="item in items"
                                                    :key="item.cita_id"
                                                    >
                                                    <td>{{ getDate(item.fecha) }}</td>
                                                    <td>{{ item.tratamiento }}</td>
                                                    <td class="text-right">{{ getCurrencyFormat(item.importe) }}</td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </v-simple-table>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                        <v-col cols="6">
                            <v-card class="pa-2">
                                <v-row justify="center">
                                    <v-col
                                        cols="12"
                                    >
                                        <v-btn-toggle
                                            outlined
                                            v-model="fpago_id"
                                            mandatory
                                            dense
                                            @change="setFpago"
                                            >
                                            <v-btn>
                                                <v-icon>mdi-cash-multiple</v-icon>Efectivo
                                            </v-btn>
                                            <v-btn>
                                                <v-icon>mdi-credit-card</v-icon> Tarjeta
                                            </v-btn>
                                            <v-btn>
                                                <v-icon>mdi-checkbook</v-icon> Talón
                                            </v-btn>
                                            <v-btn>
                                                <v-icon>mdi-transfer</v-icon> Transfer.
                                            </v-btn>
                                        </v-btn-toggle>
                                    </v-col>
                                </v-row>
                                <v-row justify="center">
                                    <v-col
                                        cols="12"
                                    >
                                        <v-btn-toggle
                                            x-small
                                            dense
                                            color="primary"
                                            v-model="billete"
                                            >
                                            <v-btn>
                                                10 €
                                            </v-btn>
                                            <v-btn>
                                                20 €
                                            </v-btn>
                                            <v-btn>
                                                50 €
                                            </v-btn>
                                            <v-btn>
                                                100 €
                                            </v-btn>
                                            <v-btn>
                                                200 €
                                            </v-btn>
                                            <v-btn>
                                                500 €
                                            </v-btn>
                                        </v-btn-toggle>
                                    </v-col>
                                </v-row>
                                <v-row class="pa-0">
                                    <v-col
                                        cols="4"
                                        offset-md="1"
                                    >
                                        <v-menu
                                            v-model="menu1"
                                            :close-on-content-click="false"
                                            :nudge-top="40"
                                            transition="scale-transition"
                                            offset-x
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on }">
                                            <v-text-field
                                                dense
                                                v-model="computedFecha"
                                                label="Fecha Cobro"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                data-vv-name="fecha"
                                                :error-messages="errors.collect('fecha')"
                                                data-vv-as="Fecha"
                                                v-on="on"
                                            ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="fecha"
                                                no-title
                                                locale="es"
                                                first-day-of-week=1
                                                @input="menu1 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col
                                        class="pa-0"
                                        cols="5"
                                        offset-md="1">
                                        <v-switch
                                            v-model="sw_todo"
                                            label="Todo lo pendiente">
                                        </v-switch>
                                    </v-col>
                                </v-row>
                                <v-row>
                                        <v-col
                                        cols="4"

                                    >
                                        <v-text-field
                                            outlined
                                            dense
                                            v-model="computedSaldo"
                                            label="Total"
                                            readonly
                                            class="inputPrice"
                                            type="number"
                                            background-color="blue lighten-5"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="4"
                                    >
                                        <v-text-field
                                            dense
                                            outlined
                                            v-model="importe"
                                            v-validate="'required|decimal:2|min:1'"
                                            :error-messages="errors.collect('importe')"
                                            label="Importe"
                                            data-vv-name="importe"
                                            data-vv-as="importe"
                                            class="inputPrice"
                                            type="number"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="4"
                                    >
                                        <v-text-field
                                            dense
                                            outlined
                                            v-model="cambio"
                                            label="Cambio"
                                            class="inputPrice"
                                            type="number"
                                            readonly
                                            background-color="green lighten-5"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        cols="2"
                                        offset-md="1"
                                        md="2"
                                    >
                                        <v-btn @click="goTicketTarjeta" outlined small block text rounded><v-icon color="blue daken-5">mdi-printer</v-icon> Tarjeta</v-btn>
                                    </v-col>
                                    <v-col
                                        cols="2"
                                        offset-md="1"
                                        md="2"
                                    >
                                        <v-btn @click="goTicketEfectivo" outlined small block text rounded><v-icon color="blue daken-5">mdi-printer</v-icon> Recibo</v-btn>
                                    </v-col>
                                    <v-col
                                        v-if="saldo!=0"
                                        offset-md="3"
                                        cols="3"
                                        md="3"
                                    >
                                        <v-btn @click="submit" :loading="loading" outlined small block rounded color="green lighten-1">Cobrar</v-btn>
                                </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </v-row>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';
import moment from 'moment'
export default {
    props:['cita','action_cita','reload_id','show_loading','dialog_cobro'],
    components: {
    },
    data () {
        return {

            saldo: 0,

            fpago_id: 0,
            billete: null,
            billetes:[10,20,50,100,200,500],

            loading: false,
            menu1:false,
            sw_todo: false,
            pacientes_id: [],
            autorizacion:null,
            fecha: new Date().toISOString().substr(0, 10),
            importe: 0,

            cambio:0,
            items:[],

            search_recomendado: null,
            recomendado_id: null,
            recomendados:[],
            isLoading: false,
            show_tabla: true,
            ticket_id: null,
            pasarela: false,
            check_bbdd: null,
            tpv_id: 2,

        }
    },
    mounted(){

        this.pacientes_id.push(this.cita.paciente_id);

        this.loadPendientes();
    },

    computed: {
         ...mapGetters([
            'userId',
            'isAdmin'
        ]),
        computedSaldo(){
            return this.saldo;
        },
        computedFecha() {
            moment.locale('es');
            return this.fecha ? moment(this.fecha).format('L') : '';
        },
        computedLabel(){
            return 'Cobrar';
        }

    },
    watch: {
        dialog_cobro: function () {
            if (this.dialog_cobro){
                this.loadPendientes();
            }
        },
        billete: function () {
            this.importe = this.billetes[this.billete];
        },
        importe: function () {
            this.cambio = (this.importe - this.saldo);
            if (this.cambio < 0)
                this.cambio = 0;
        },
        sw_todo: function () {
            this.loadPendientes();
        },
        search_recomendado (val) {

            if (this.search_recomendado == null || this.search_recomendado.length <= 4) return;

            // Items have already been requested
            if (this.isLoading) return;

            this.isLoading = true;


            axios.post('/tools/helpcli', {search: this.search_recomendado})
                .then(res => {

                    if (res.data.length > 0){
                        this.recomendados = res.data;
                        this.recomendado_id = this.recomendados[0].value;
                    }
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(() => (
                    this.isLoading = false
                ));


            },
    },
    methods:{
        clearRec(){
            this.recomendado_id = null;
            this.recomendados = [];
        },
        getDecimalFormat(value){
            return value;
                return new Intl.NumberFormat("de-DE",{style: "decimal",minimumFractionDigits:2}).format(parseFloat(value))
        },
        getDate(f) {
            moment.locale('es');
            return moment(f).format('D/M/YYYY');
        },
        setFpago(){
            if (this.pasarela && this.fpago_id == 1 && this.saldo != 0){
                this.importe = this.saldo;
                this.check_bbdd = setInterval(this.myTimer, 3000);
                this.lanzarPasarela();
            }
        },
        myTimer(){

            axios.put('/citas/tpvmov/update', {
                        'paciente_id': this.cita.paciente_id,
                        'importe': this.importe
                    }
                    )
                .then(res => {

                    if (res.status == 200){
                        clearInterval(this.check_bbdd);
                        this.submit();
                    }
                })
                .catch(err => {
                    console.log(err);
                    if (err.request.status == 411){
                        clearInterval(this.check_bbdd);
                        this.$toast.error(err.response.data.message);
                    }
                });
        },
        loadPendientes(){

            this.loading = true;
            axios.post('/citas/cobro/load', {
                    fecha : this.fecha,
                    paciente_id: this.cita.paciente_id,
                    pacientes_id : this.pacientes_id,
                    mutua_id: null,
                    todo : this.sw_todo
                })
                .then(res => {
                    this.items = res.data.citas;
                    this.saldo = res.data.saldo;
                    this.importe = this.saldo;
                    this.pasarela = res.data.tpv;
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(()=>{
                    this.loading = false;
                });


        },
        submit(){

                this.loading = true;

                axios.post('/citas/cobro/cobrar', {
                        fecha : this.fecha,
                        paciente_id: this.cita.paciente_id,
                        pacientes_id : this.pacientes_id,
                        mutua_id: null,
                        todo : this.sw_todo,
                        importe: this.importe - this.cambio,
                        fpago_id: this.fpago_id + 1,
                        autorizacion: this.autorizacion
                    })
                    .then(res => {
                        this.items = res.data.citas;
                        this.saldo = res.data.saldo;
                        this.importe = this.saldo;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                    })
                    .finally(()=>{
                        this.loading = false;
                    });

        },
        lanzarPasarela(){
            this.show_tabla = false;
            window.open('/tpvpc/1/'+this.tpv_id+'/'+this.importe+'/'+this.cita.paciente_id,'Pasarela de Pago','toolbar=no,resizable=no, width=600, height=500, scrollbar=no, status=no');

        },
        goTicketTarjeta(){
            window.open('/citas/ticket/'+this.cita.paciente_id+'/'+this.fecha,'Ticket TPV','toolbar=no,resizable=no, width=400, height=500, scrollbar=no, status=no');
        },
        goTicketEfectivo(){
            window.open('/citas/recibo/'+this.cita.paciente_id+'/'+this.fecha,'Recibo','toolbar=no,resizable=no, width=400, height=500, scrollbar=no, status=no');
        },
        getCurrencyFormat(value){
            return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        cancelar(){
            clearInterval(this.check_bbdd);
            var id = this.reload_id + 1;

            this.$emit('update:reload_id', id);
            this.$emit('update:dialog_cobro', false);
            this.$emit('update:action_cita', 'V');
        },
        addCitas(){

            if (this.recomendado_id != null)
                this.pacientes_id.push(this.recomendado_id);

            this.loadPendientes();
            this.recomendado_id = null;
        }

    }
}
</script>



<style scoped>

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
