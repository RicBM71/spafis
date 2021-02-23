<template>
	<div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="bloqueo.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>

                    <v-row>
                        <v-col
                            cols="2"
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
                                    v-model="bloqueo.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu1 = false">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="6"
                            md="2"
                        >
                            <v-select
                                v-model="bloqueo.facultativo_id"
                                v-validate="'required'"
                                :error-messages="errors.collect('facultativo_id')"
                                label="Facultativo"
                                data-vv-name="facultativo_id"
                                data-vv-as="facultativo"
                                :items="facultativos"
                            ></v-select>
                        </v-col>

                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-btn @click="submit" text  rounded  :loading="loading" color="primary">
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
import {mapGetters} from 'vuex';
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
                titulo:"Bloqueos",
                bloqueo: {
                    fecha: new Date().toISOString().substr(0, 10),
                },
                url: "/mto/bloqueos",
                ruta: "bloqueo",
                loading: false,
                menu1: false,
                facultativos:[],

                show_loading: true,

      		}
        },
        mounted(){
            this.show_loading = false;
            axios.get(this.url+'/create')
                    .then(res => {

                        this.facultativos = res.data.facultativos;

                    })
                    .catch(err =>{
                        this.$toast.error(err.response.data.message);
                    })
                    .finally(()=> {
                    });
        },
        computed: {
            ...mapGetters([
                'isAdmin',
                'isSupervisor',
            ]),
            computedFecha() {
                moment.locale('es');
                return this.bloqueo.fecha ? moment(this.bloqueo.fecha).format('L') : '';
            },
        },
    	methods:{

            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(this.url, this.bloqueo)
                                .then(response => {

                                   // this.$toast.success(response.data.message);

                                    this.loading = false;
                                    this.$router.push({ name: this.ruta+'.edit', params: { id: response.data.reg.id } })
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


    }
  }
</script>
