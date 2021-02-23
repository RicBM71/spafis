<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            color="white"
                            icon
                            @click="goCreate"
                        >
                            <v-icon color="primary">mdi-plus</v-icon>
                        </v-btn>
                    </template>
                    <span>Nuevo</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            color="white"
                            icon
                            @click="goHorario"
                        >
                            <v-icon color="primary">mdi-arrow-left</v-icon>
                        </v-btn>
                    </template>
                    <span>Horario</span>
                </v-tooltip>

            </v-card-title>
        </v-card>
        <v-card v-show="!loading">
            <v-form>
                <v-container>
                    <v-row>
                        <v-col cols="12" md="2">
                            <v-menu
                                v-model="menu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="computedDateFormat"
                                    label="Fecha"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="horario.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu = false">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.inim_1"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('inim_1')"
                                label="Lunes - Mañana"
                                data-vv-name="inim_1"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.finm_1"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('finm_1')"
                                label="Lunes - Mañana"
                                data-vv-name="finm_1"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.init_1"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('init_1')"
                                label="Lunes - Mañana"
                                data-vv-name="init_1"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.fint_1"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('fint_1')"
                                label="Lunes - Mañana"
                                data-vv-name="fint_1"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2"></v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.inim_2"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('inim_2')"
                                label="Martes - Mañana"
                                data-vv-name="inim_2"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.finm_2"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('finm_2')"
                                label="Martes - Mañana"
                                data-vv-name="finm_2"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.init_2"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('init_2')"
                                label="Martes - Mañana"
                                data-vv-name="init_2"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.fint_2"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('fint_2')"
                                label="Martes - Mañana"
                                data-vv-name="fint_2"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2"></v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.inim_3"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('inim_3')"
                                label="Miércoles - Mañana"
                                data-vv-name="inim_3"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.finm_3"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('finm_3')"
                                label="Miércoles - Mañana"
                                data-vv-name="finm_3"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.init_3"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('init_3')"
                                label="Miércoles - Mañana"
                                data-vv-name="init_3"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.fint_3"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('fint_3')"
                                label="Miércoles - Mañana"
                                data-vv-name="fint_3"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2"></v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.inim_4"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('inim_4')"
                                label="Jueves - Mañana"
                                data-vv-name="inim_4"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.finm_4"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('finm_4')"
                                label="Jueves - Mañana"
                                data-vv-name="finm_4"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.init_4"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('init_4')"
                                label="Jueves - Mañana"
                                data-vv-name="init_4"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.fint_4"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('fint_4')"
                                label="Jueves - Mañana"
                                data-vv-name="fint_4"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2"></v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.inim_5"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('inim_5')"
                                label="Viernes - Mañana"
                                data-vv-name="inim_5"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.finm_5"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('finm_5')"
                                label="Viernes - Mañana"
                                data-vv-name="finm_5"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.init_5"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('init_5')"
                                label="Viernes - Mañana"
                                data-vv-name="init_5"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.fint_5"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('fint_5')"
                                label="Viernes - Mañana"
                                data-vv-name="fint_5"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2"></v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="horario.username"
                                label="Usuario"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-btn @click="submit"  rounded  :loading="loading" small color="primary">
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
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'

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
                titulo:"Facultativo",
                horario: {},
                inim_1:"",
                loading: true,
                menu:false,
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/horarios/'+id+'/edit')
                    .then(res => {

                        this.horario = res.data.registro;
                        this.titulo = 'Horario '+res.data.registro.facultativo.nombre;

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'horario.index'})
                    })
                    .finally(()=>{
                       this.loading = false;
                });
        },
        computed: {
            computedDateFormat() {
                moment.locale('es');
                return this.horario.fecha ? moment(this.horario.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.horario.updated_at ? moment(this.horario.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.horario.created_at ? moment(this.horario.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            formatHora(h){

                moment.locale('es');
                return moment(h).format('H:mm');

            },
            submit() {

                if (this.loading === false){
                    this.loading = true;
                    var url = "/mto/horarios/"+this.horario.id;
                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.horario)
                                .then(res => {
                                    this.$toast.success(res.data.message);
                                    this.horario = res.data.registro;
                                    //this.$validator.reset();
                                    this.errors.clear()
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
                                })
                                .finally(()=>{
                                    this.loading = false;
                                });
                            }
                        else{
                            this.loading = false;
                        }
                    });
                }

            },
            goCreate(){
                this.$router.push({ name: 'horario.create'})
            },
            goHorario(){
                this.$router.push({ name: 'horario.index'})
            },
    }
  }
</script>
