<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="historia.id" :paciente_id="historia.paciente_id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>

                        <v-form>
                            <v-row>
                                <v-col
                                    cols="12"
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
                                            v-model="historia.fecha"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu1 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" md="2">
                                    <v-switch
                                        label="Diagnosticado"
                                        v-model="historia.diagnosticado"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="2"></v-col>
                                <v-col cols="12" md="2">
                                    <v-switch
                                        label="Informe Externo"
                                        v-model="historia.informe"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col
                                        v-if="historia.informe"
                                        cols="12"
                                        md="3"
                                    >
                                        <v-select
                                            v-model="historia.facultativo_id"
                                            v-validate="'numeric'"
                                            :error-messages="errors.collect('facultativo_id')"
                                            label="Firma fisioterapeuta"
                                            data-vv-name="facultativo_id"
                                            data-vv-as="facultativo_id"
                                            :items="facultativos"
                                        ></v-select>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        class="font-weight-medium"
                                        outlined
                                        v-model="historia.motivo"
                                        label="Motivo de la consulta"

                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        class="font-weight-medium"
                                        outlined
                                        label="Exploración"
                                        v-model="historia.exploracion"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        class="font-weight-medium"
                                        outlined
                                        label="Juicio"
                                        v-model="historia.juicio"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        class="font-weight-medium"
                                        outlined
                                        label="Tratamiento"
                                        v-model="historia.tratamiento"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col
                                    cols="12"
                                    offset-md="10"
                                    md="1"
                                >
                                    <v-btn @click="submit"  rounded  :loading="loading" text color="primary">
                                        Guardar
                                    </v-btn>
                                </v-col>
                            </v-row>


                        </v-form>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex';
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'loading': Loading,
		},
    	data () {
      		return {
                titulo:"Historia",
                historia: {
                    paciente_id: '',
                    fecha: new Date().toISOString().substr(0, 10),
                    motivo: '',
                    exploracion: '',
                    juicio: '',
                    tratamiento: '',
                    diagnosticado: false,
                    notas: '',
                    informe: false,
                    cie: null,
                    facultativo_id: null,
                },
                url: "/mto/historias",
                ruta: "historia",
                loading: false,
                menu1: false,
                show: false,
                show_loading: true,
                facultativos:[],
                menu1:false
      		}
        },
        mounted(){

            this.historia.paciente_id = this.$route.params.paciente_id;

            if (this.historia.paciente_id > 0)
                axios.get(this.url+'/create')
                    .then(res => {

                        this.facultativos = res.data.facultativos;
                        this.facultativos.push({value: null, text: '-'});

                        this.titulo = "Nueva historia"
                        this.show_loading = false;

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash'})
                    })
                    .finally(()=> {
                        this.show_loading = false;
                    });
        },
        computed: {
        ...mapGetters([
                'hasDelete',
                'isAdmin',
                'userName'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.historia.fecha ? moment(this.historia.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.historia.updated_at ? moment(this.historia.updated_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.historia.created_at ? moment(this.historia.created_at).format('DD/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{

            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                             axios.post(this.url, this.historia)
                                .then(res => {

                                    this.$router.push({ name: this.ruta+'.edit', params: { id: res.data.historia.id } })

                                })
                                .catch(err => {

                                    if (err.request.status == 422){ // fallo de validated.
                                        const msg_valid = err.response.data.errors;
                                        for (const prop in msg_valid) {
                                            // this.$toast.error(`${msg_valid[prop]}`);

                                            this.errors.add({
                                                field: prop,
                                                msg: `${msg_valid[prop]}`
                                            })
                                        }
                                    }else{
                                        this.$toast.error(err.response.data.message);
                                    }
                                })
                                .finally(()=> {

                                    this.loading = false;
                                    this.show_loading = false;
                                });
                            }
                        else{
                            this.loading = false;
                        }
                    });
                }

            },

    }
  }
</script>
<style scoped>

.inputNumber >>> input {
  text-align: center;
  -moz-appearance:textfield;
}


.v-form>.container>.layout>.flex {
    padding: 1px 0px 1px 0px;
}
.v-text-field {
    padding-top: 1px;
    margin-top: 1px;
}

</style>
