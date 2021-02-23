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
                        <v-col cols="10" md="1">
                            <v-text-field
                                v-model="festivo.username"
                                label="Usuario"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="10" md="2">
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="10" md="2">
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                            >
                            </v-text-field>
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
                titulo:"Editar",
                festivo: {},
                url: "/mto/festivos",
                ruta: "festivo",
                loading: false,
                menu1: false,

                show: false,
                show_loading: true,

      		}
        },
        mounted(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get(this.url+'/'+id+'/edit')
                    .then(res => {

                        this.festivo = res.data.festivo;

                        this.show = true;
                        this.show_loading = false;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
        },
        computed: {
        ...mapGetters([
                'isAdmin'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.festivo.fecha ? moment(this.festivo.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.festivo.updated_at ? moment(this.festivo.updated_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.festivo.created_at ? moment(this.festivo.created_at).format('DD/MM/YYYY H:mm') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                             axios.put(this.url+"/"+this.festivo.id, this.festivo)
                                .then(res => {

                                    this.$toast.success(res.data.message);
                                    this.festivo = res.data.festivo;
                                    this.loading = false;
                                    this.$validator.reset();
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
                                    this.loading = false;
                                })
                                .finally(()=> {
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
