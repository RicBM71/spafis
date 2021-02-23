<template>
	<div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="festivo.id"></menu-ope>
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
                                    v-model="festivo.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu1 = false">
                                </v-date-picker>
                            </v-menu>
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
                titulo:"Festivo",
                festivo: {
                },
                url: "/mto/festivos",
                ruta: "festivo",
                loading: false,
                menu1: false,

                show_loading: true,

      		}
        },
        mounted(){
            this.show_loading = false;
        },
        computed: {
            ...mapGetters([
                'isAdmin',
            ]),
            computedFecha() {
                moment.locale('es');
                return this.festivo.fecha ? moment(this.festivo.fecha).format('L') : '';
            },
        },
    	methods:{

            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(this.url, this.festivo)
                                .then(response => {

                                    this.$toast.success(response.data.message);

                                    this.loading = false;
                                   // this.$router.push({ name: this.ruta+'.edit', params: { id: response.data.festivo.id } })
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
