<template>
    <v-row justify="center">
        <v-dialog
        v-model="dialog_cancel_cobro"
        persistent
        max-width="600"
        >
        <v-card>
            <v-card-title class="headline">
                Cancelar Cobro {{ cita.paciente.nom_ape}}
            </v-card-title>
            <v-card-text>
                <br/>
                <v-row>
                    <v-col
                        cols="12"
                        md="1"
                    >
                    </v-col>
                    <v-col
                        cols="12"
                        md="4"
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
                        cols="12"
                        md="5"
                    >
                        <v-select
                            v-model="fpago_id"
                            label="Forma de Pago"
                            :items="fpagos"
                        ></v-select>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="blue darken-1"
                text
                outlined
                rounded

                @click="cancelar"
            >
                Cerrar
            </v-btn>
            <v-btn
                :loading="loading"
                color="blue darken-1"
                outlined
                rounded
                
                text
                @click="submit"
            >
                Enviar
            </v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
    </v-row>


</template>
<script>
import moment from 'moment'
export default {
    props:['cita','saldo','action_cita','reload_id','show_loading','dialog_cancel_cobro'],
    components: {
    },
    data () {
        return {

            fpago_id: 1,

            loading: true,
            menu1:false,
            fpagos:[],

            fecha: new Date().toISOString().substr(0, 10),

        }
    },
    mounted(){

        axios.get('/citas/cobros')
            .then(res => {
                this.fpagos = res.data.fpagos;
            })
            .catch(err =>{
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
            .finally(()=> {
                this.loading=false;
            });

    },

    computed: {
        computedFecha() {
            moment.locale('es');
            return this.fecha ? moment(this.fecha).format('L') : '';
        },
    },
    watch: {
        dialog_cancel_cobro: function () {

        },
    },
    methods:{

        loadPendientes(){
            this.loading = true;
            axios.post('/citas/cobro/load', {
                    fecha : this.fecha,
                    paciente_id: this.paciente_id,
                    pacientes_id : this.pacientes_id,
                    mutua_id: null,
                    todo : this.sw_todo
                })
                .then(res => {
                    this.items = res.data.citas;
                    this.saldo = res.data.saldo;
                    this.importe = this.saldo;
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

            axios.post('/citas/cobro/cancelar', {
                    fecha : this.fecha,
                    paciente_id: this.cita.paciente_id,
                    mutua_id: null,
                    fpago_id: this.fpago_id,
                    cita_id: this.cita.id
                })
                .then(res => {

                    this.$emit('update:dialog_cancel_cobro', false);
                    const id = this.reload_id + 1;
                    this.$emit('update:action_cita', 'V');
                    this.$emit('update:reload_id', id);
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.warning(err.response.data.message);
                })
                .finally(()=>{
                    this.loading = false;
                });


        },
        cancelar(){

            this.$emit('update:dialog_cancel_cobro', false);
            this.$emit('update:action_cita', 'V');
        }

    }
}
</script>

