<template>
        <div>
            <v-row>
                <v-col
                    cols="12"
                    md="3"
                    class="pa-1 mt-2"
                >
                    <v-text-field
                        hide-details="true"
                        outlined
                        dense
                        v-model="paciente.nom_ape"
                        label="Paciente"
                        background-color="blue lighten-5"
                        readonly
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1 mt-2"
                >
                    <v-menu
                        v-model="menu1"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                        <v-text-field
                            dense
                            outlined
                            v-model="computedFecha"
                            label="Fecha"
                            readonly
                            data-vv-name="fecha"
                            :error-messages="errors.collect('fecha')"
                            data-vv-as="Fecha"
                            v-on="on"
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="cita.fecha"
                            no-title
                            locale="es"
                            first-day-of-week=1
                            @change="calcularPVP"
                            @input="menu1 = false">
                        </v-date-picker>
                    </v-menu>
                </v-col>
                <v-col
                    v-if="cita.tune"
                    cols="12"
                    sm="1"
                    class="pa-1 mt-2"
                >
                    <v-menu
                        ref="menu"
                        v-model="menu_picker_hora"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="cita.hora"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            dense
                            outlined
                            v-model="cita.hora"
                            label="Tune On"
                            prepend-icon="mdi-clock-time-four-outline"
                            :error-messages="errors.collect('hora')"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                        </template>
                        <v-time-picker
                            min="09:00"
                            max="20:00"
                            v-if="menu_picker_hora"
                            v-model="cita.hora"
                            full-width
                            @click:minute="$refs.menu.save(cita.hora)"
                        ></v-time-picker>
                    </v-menu>
                </v-col>
                <v-col
                    v-else
                    cols="12"
                    sm="1"
                    class="pa-1 mt-2"
                >
                    <v-select
                        dense
                        outlined
                        v-model="cita.hora"
                        v-validate="'required'"
                        :error-messages="errors.collect('hora')"
                        label="Hora"
                        data-vv-name="hora"
                        data-vv-as="hora"
                        :items="horas"
                    ></v-select>

                </v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1 mt-2"
                >
                    <v-btn v-if="cita.tune" x-small @click="cita.tune=!cita.tune">Tune On</v-btn>
                    <v-btn v-else x-small @click="cita.tune=!cita.tune">Tune Off</v-btn>
                </v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1 mt-2"
                >
                    <v-select
                        dense
                        outlined
                        v-model="cita.facultativo_id"
                        :error-messages="errors.collect('facultativo_id')"
                        label="Facultativo"
                        data-vv-name="facultativo_id"
                        data-vv-as="facultativo_id"
                        :items="facultativos"
                        @change="huecos"
                    ></v-select>
                </v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1 mt-2"
                >
                    <v-text-field
                        dense
                        outlined
                        v-model="cita.bono"
                        append-outer-icon="mdi-gesture-tap"
                        v-validate="'numeric'"
                        :error-messages="errors.collect('bono')"
                        label="Bono"
                        data-vv-name="bono"
                        data-vv-as="bono"
                        readonly
                        @click:append-outer="goAnticipo"
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1 mt-2"
                >
                    <v-text-field
                        dense
                        outlined
                        v-model="cita.importe"
                        v-validate="'required|decimal:2'"
                        :error-messages="errors.collect('importe')"
                        label="Importe"
                        data-vv-name="importe"
                        data-vv-as="importe"
                        :readonly="!computedEditImporte"
                        v-on:keyup.enter="submit"
                    >
                    </v-text-field>
                </v-col>
                <v-col cols="2" md="2"></v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1"
                    style="max-height:9px"
                >
                    <v-avatar size="96" v-if="paciente.foto!=false">
                        <img class="img-fluid" :src="paciente.foto">
                    </v-avatar>
                </v-col>

                <v-col
                    cols="12"
                    md="3"
                    class="pa-1"
                >
                    <v-select
                        dense
                        outlined
                        v-model="cita.tratamiento_id"
                        :error-messages="errors.collect('tratamiento_id')"
                        label="Tratamiento"
                        data-vv-name="tratamiento_id"
                        data-vv-as="tratamiento_id"
                        :items="tratamientos"
                        @change="calcularPVP"
                    ></v-select>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                    class="pa-1"
                >
                    <v-text-field
                        dense
                        outlined
                        v-model="cita.notas"
                        v-on:keyup.enter="submit"
                        label="Observaciones"
                    >
                    </v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                    class="pa-1"
                >
                    <v-select
                        dense
                        outlined
                        v-model="cita.mutua_id"
                        :error-messages="errors.collect('mutua_id')"
                        label="Sociedad"
                        data-vv-name="mutua_id"
                        data-vv-as="mutua_id"
                        :items="mutuas"
                    ></v-select>
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                    class="pa-1"
                >
                    <v-btn @click="cancelar" text small rounded  color="primary">Cancelar</v-btn>
                    <v-btn @click="submit" text small rounded  :loading="loading" color="primary">Guardar</v-btn>
                </v-col>

            </v-row>

        </div>
</template>
<script>
import moment from 'moment'
import {mapGetters} from 'vuex';
export default {
    props:['cita', 'action_cita', 'reload_id','show_loading'],
    data () {
        return {
            loading: false,
            bono:{},
            tratamientos:[],
            facultativos:[],
            mutuas:[],
            horas:[],
            menu1: false,

            paciente:{
                nom_ape: null
            },
            menu_picker_hora: false,

        }
    },
    mounted(){

        this.loading = true;
        console.log(this.cita);

        axios.post('/citas/add',this.cita)
            .then(res => {

                console.log(res);

                this.tratamientos = res.data.tratamientos;
                this.facultativos = res.data.facultativos;
                this.horas = res.data.horas;
                this.mutuas = res.data.mutuas;

                this.mutuas.push({value: null, text: '-'});

                // this.cita.paciente_id = this.cita.paciente_id;
                // this.cita.fecha       = this.cita.fecha;
                this.paciente         = res.data.paciente;

                if (res.data.ult_cita != false){
                    this.cita.tratamiento_id = res.data.ult_cita.tratamiento_id;

                    this.cita.facultativo_id = res.data.ult_cita.facultativo_id;
                    var idx = this.facultativos.map(x => x.value).indexOf(res.data.ult_cita.facultativo_id);
                    if (idx < 0)
                        this.cita.facultativo_id = this.facultativos[0].value;

                    var idx = this.horas.map(x => x.value).indexOf(res.data.ult_cita.hora);
                    if (idx > 0)
                        this.cita.hora = res.data.ult_cita.hora;
                    else
                        this.cita.hora = this.horas[0].value;
                }

                this.calcularPVP();

            })
            .catch(err =>{
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
            .finally(()=> {
                this.loading = false;
                this.$emit('update:show_loading', false);
            });

    },
    watch: {
        reload_id: function () {
            this.calcularPVP();
        },
        action_cita: function () {

            if (this.action_cita == 'A'){


            }

        }
    },

    computed: {
        ...mapGetters([
                'hasPrecios'
            ]),
        computedEditImporte(){
            return this.hasPrecios;
        },
        computedFecha() {
            moment.locale('es');
            return this.cita.fecha ? moment(this.cita.fecha).format('dd DD/MMM') : '';
        },
        computedHora() {
            return this.cita.hora.substr(0, 5);
        },

    },
    methods:{
        getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        cancelar(){
            //this.$emit('update:cita.paciente_id', null);
            this.$emit('update:action_cita', 'V');
        },
        huecos(){

            axios.post('/tools/citas/huecos', {
                    facultativo_id : this.cita.facultativo_id,
                    hora : false,
                    fecha : this.cita.fecha
            })
                .then(res => {
                    this.horas = res.data.horas;
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(()=>{
                    this.loading = false;
                });

        },
        calcularPVP(){

            this.loading = true;
            axios.post('/tools/valorar', {
                    tratamiento_id : this.cita.tratamiento_id,
                    paciente_id : this.cita.paciente_id,
                    facultativo_id : this.cita.facultativo_id,
                    fecha : this.cita.fecha,
                    hora  : false
            })
                .then(res => {
                    this.cita.importe = res.data.precios.importe;
                    this.cita.bono = res.data.precios.bono;
                    this.cita.iva = res.data.precios.iva;
                    this.cita.importe_ponderado = res.data.precios.importe_ponderado;
                    this.horas = res.data.horas;
                    console.log(this.horas);
                    if (this.horas.length > 0){
                        this.hora = this.horas[0].value;
                    }
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(()=>{
                    this.loading = false;
                });


        },
        loadHoras(){

            this.loading = true;
            axios.post('/tools/horas', {
                    fecha : this.cita.fecha,
                    facultativo_id : this.cita.facultativo_id,
                    hora : false
            })
                .then(res => {
                    this.horas = res.data.horas;
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(()=>{
                    this.loading = false;
                });


        },
        submit() {
            if (this.loading === false){
                this.loading = true;

                var url = "/citas/citas";

                this.$validator.validateAll().then((result) => {
                    if (result){

                        console.log(this.cita);

                        axios.post(url, this.cita)
                            .then(res => {

                                this.$validator.errors.clear();

                                this.loading = false;

                                var id = this.reload_id + 1;
                                this.$emit('update:cita', res.data.cita);
                                this.$emit('update:action_cita', 'V');
                                this.$emit('update:reload_id', id);
                            })
                            .catch(err => {

                                if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
                                        this.errors.add({
                                            field: prop,
                                            msg: `${msg_valid[prop]}`
                                        })
                                    }
                                }else{
                                    this.$toast.error(err.response.data.message);
                                }
                                this.loading = false;
                            });
                        }
                    else{
                        this.loading = false;
                    }
                });
            }
        },
        goAnticipo(){

            axios.post('/tools/citas/bono/anticipo', {
                'paciente_id': this.cita.paciente_id,
                'tratamiento_id': this.cita.tratamiento_id
            })
                .then(res => {

                    if (res.data != false){
                        this.cita.bono = res.data.bono;
                        this.cita.importe = res.data.importe;
                        this.cita.importe_ponderado = res.data.importe_ponderado;
                        this.cita.iva = res.data.iva;
                    }

                })
                .catch(err => {

                    if (err.request.status == 422){ // fallo de validated.
                        const msg_valid = err.response.data.errors;
                        for (const prop in msg_valid) {
                            this.errors.add({
                                field: prop,
                                msg: `${msg_valid[prop]}`
                            })
                        }
                    }else{
                        this.$toast.error(err.response.data.message);
                    }
                    this.loading = this.isLoading = false;
                });

        }
    }
}
</script>
