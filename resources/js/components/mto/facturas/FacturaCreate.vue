<template>
	<div>

        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :factura="factura"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>

                    <v-row>
                        <v-col
                            cols="10"
                            md="2">
                        </v-col>
                        <v-col
                            cols="10"
                            md="2"
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
                                    v-model="factura.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu1 = false">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-autocomplete
                                v-model="factura.paciente_id"
                                :items="pacientes"
                                :loading="isLoading"
                                :search-input.sync="search"
                                label="Paciente"
                                placeholder="Indicar nom,ape"
                                prepend-icon="mdi-database-search"
                                clearable
                                @click:clear="clearRec"
                            ></v-autocomplete>
                        </v-col>

                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-btn @click="submit"  text  rounded  :loading="loading" color="primary">
                                Guardar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>

            </v-form>

        </v-card>
	</div>
</template>
<script>
import {mapGetters} from 'vuex';
import moment from 'moment'
import MenuOpe from './MenuOpe'
import Loading from '@/components/shared/Loading'

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'loading': Loading
		},
    	data () {
      		return {

                titulo:"Crear Factura",
                menu1: false,
                isLoading: false,

                factura: {
                    id: 0,
                    empresa_id: 0,
                    cliente_id: 0,
                    factura: "",
                    fecha: new Date().toISOString().substr(0, 10),
                },
                url: "/facturas/facturas",
                ruta: "factura",

        		status: false,
                loading: false,
                show_loading: true,
                pacientes:[],
                search:'',


      		}
        },
        mounted(){


        },
        created: function () {
            this.debouncedGetAnswer = _.debounce(this.getPacientes, 1000)
        },
        computed: {
            ...mapGetters([
                    'isAdmin'
                ]),
            computedFecha() {
                moment.locale('es');
                return this.factura.fecha ? moment(this.factura.fecha).format('L') : '';
            },
        },
        watch: {
            search: function (newQuestion, oldQuestion) {
              this.debouncedGetAnswer()
            },

        },
    	methods:{
            getPacientes(val) {

                if (this.search == null || this.search.length <= 4) return;

                // Items have already been requested
                if (this.isLoading) return;

                this.isLoading = true;


                 axios.post('/tools/helpcli', {search: this.search})
                    .then(res => {
                        //console.log(res.data);
                        if (res.data.length > 0){
                            this.pacientes = res.data;
                            this.factura.paciente_id = this.pacientes[0].value;

                        }

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
                    .finally(() => (
                        this.isLoading = false
                    ));


            },
            clearRec(){
                this.factura.paciente_id = null;
                this.pacientes = [];
            },
            submit(){

                this.loading = true;
                this.$validator.validateAll().then((result) => {
                    if (result){
                            axios.post(this.url, this.factura)
                            .then(response => {

                              //  this.$toast.success(response.data.message);
                              //  console.log(response);
                                this.loading = false;
                                this.$router.push({ name: this.ruta+'.edit', params: { id: response.data.factura.id } })
                            })
                            .catch(err => {
                                this.fase_valid = 1;
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
        }
  }
</script>
