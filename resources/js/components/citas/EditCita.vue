<template>
    <div v-if="cita.id > 0">
        <div v-if="cita.id > 0">
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
                        v-model="cita.paciente.nom_ape"
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
                            prepend-icon="mdi-calendar"
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
                    cols="6"
                    sm="1"
                    class="pa-1 mt-2"
                >
                    <v-menu
                        ref="menu"
                        v-model="menu_piker_hora"
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
                            label="Hora - Tune On"
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
                            format="24hr"
                            v-if="menu_piker_hora"
                            v-model="cita.hora"
                            full-width
                            @click:minute="$refs.menu.save(cita.hora)"
                        ></v-time-picker>
                    </v-menu>
                </v-col>
                <v-col
                    v-else
                    cols="6"
                    sm="1"
                    class="pa-1 mt-2"
                >
                    <v-select
                        dense
                        outlined
                        v-model="cita.hora"
                        :error-messages="errors.collect('hora')"
                        label="Hora"
                        data-vv-name="hora"
                        data-vv-as="hora"
                        :items="horas"
                    ></v-select>

                </v-col>
                <v-col
                    cols="6"
                    md="1"
                    class="pa-1 mt-2"
                >
                    <v-btn v-if="cita.tune" x-small @click="cita.tune=!cita.tune">Tune On</v-btn>
                    <v-btn v-else x-small @click="cita.tune=!cita.tune">Tune Off</v-btn>
                </v-col>
                <v-col
                    cols="6"
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
                        @change="loadHoras"
                    ></v-select>
                </v-col>
                <v-col
                    cols="6"
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
                <v-col cols="12" md="2"></v-col>
                <v-col
                    cols="12"
                    md="1"
                    class="pa-1"
                    style="max-height:9px"
                >
                    <v-avatar size="96" v-if="cita.paciente.foto!=false">
                        <img class="img-fluid" :src="cita.paciente.foto">
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
                        label="Observaciones"
                        v-on:keyup.enter="submit"
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
                >
                    <v-btn @click="cancelar" text small rounded  color="primary">Cancelar</v-btn>
                    <v-btn @click="submit" text small rounded  :loading="loading" color="primary">Guardar</v-btn>
                </v-col>

            </v-row>

        </div>

    </div>
</template>
<script>
import moment from 'moment'
import {mapGetters} from 'vuex';
export default {
    props:['cita', 'action_cita', 'show_loading', 'reload_id'],
    data () {
        return {
            loading: false,
            isLoading: true,
            bono:{},
            tratamientos:[],
            facultativos:[],
            mutuas:[],
            horas:[],
            menu1: false,
            menu_piker_hora: false,
        }
    },
    mounted(){

    },
    watch: {

        action_cita: function () {

            if (this.action_cita == 'E'){

                this.isLoading = true;

                axios.get('/citas/citas/'+this.cita.id+'/edit')
                    .then(res => {

                        this.$emit('update:cita', res.data.cita);

                        this.tratamientos = res.data.tratamientos;
                        this.facultativos = res.data.facultativos;
                        this.horas = res.data.horas;
                        this.mutuas = res.data.mutuas;

                        this.mutuas.push({value: null, text: '-'});

                    })
                    .catch(err =>{
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash' })
                    })
                    .finally(()=> {
                        this.isLoading = false;
                        this.$emit('update:show_loading', false);
                    });

            }
        },
        reload_id: function () {
           // this.loadHoras();
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
            this.$emit('update:action_cita', 'V');
        },
        calcularPVP(){
            this.loading = true;
            axios.post('/tools/valorar', {
                    tratamiento_id : this.cita.tratamiento_id,
                    paciente_id : this.cita.paciente_id,
                    facultativo_id : this.cita.facultativo_id,
                    fecha : this.cita.fecha,
                    hora  : this.cita.hora
            })
                .then(res => {
                    this.cita.importe = res.data.precios.importe;
                    this.cita.bono = res.data.precios.bono;
                    this.cita.iva = res.data.precios.iva;
                    this.cita.importe_ponderado = res.data.precios.importe_ponderado;
                    this.horas = res.data.horas;
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
            axios.post('/tools/citas/huecos', {
                    paciente_id : this.cita.paciente_id,
                    tratamiento_id : this.cita.tratamiento_id,
                    fecha : this.cita.fecha,
                    facultativo_id : this.cita.facultativo_id,
                    hora : this.cita.hora
            })
                .then(res => {

                    this.cita.importe = res.data.precios.importe;
                    this.cita.bono = res.data.precios.bono;
                    this.cita.iva = res.data.precios.iva;
                    this.cita.importe_ponderado = res.data.precios.importe_ponderado;
                    this.horas = res.data.horas;
                    var idx = this.horas.map(x => x.value).indexOf(this.cita.hora);
                    if (idx < 0 && this.horas.length > 0)
                        this.cita.hora = this.horas[0].value;

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
                this.loading = this.isLoading = true;

                var url = "/citas/citas/"+this.cita.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.cita)
                            .then(res => {

                                // this.$toast.success(res.data.message);

                                // this.cita = res.data.cita;

                                this.$validator.errors.clear();

                                this.loading = false;

                                var id = this.reload_id + 1;
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
                                this.loading = this.isLoading = false;
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
