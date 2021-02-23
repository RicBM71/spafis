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
                        <v-col
                            cols="12"
                            md="2"
                        >
                        </v-col>
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
                    </v-row>
                    <v-row>
                        <v-spacer></v-spacer>
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
    import {mapGetters} from 'vuex';
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
                horario: {
                    id:       0,
                    fecha: new Date().toISOString().substr(0, 10),
                },

                titulo: "Nuevo",
                menu: false,
                loading: false,

      		}
        },
        mounted(){


        },
        computed: {
            ...mapGetters([
	    		'isAdmin'
            ]),
            computedDateFormat() {
                moment.locale('es');
                return this.horario.fecha ? moment(this.horario.fecha).format('L') : '';
            },

        },
    	methods:{
            goHorario(){
                this.$router.push({ name: 'horario.index'})
            },
            submit() {
                if (this.loading === false){
                    this.loading = true;

                    var url = "/mto/horarios";

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(url, this.horario)
                                .then(res => {

                                    this.$router.push({ name: 'horario.edit', params: { id: res.data.registro.id } })
                                })
                                .catch(err => {
                                        const msg_valid = err.response.data.errors;
                                        for (const prop in msg_valid) {
                                            this.errors.add({
                                                field: prop,
                                                msg: `${msg_valid[prop]}`
                                            })
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
    }
  }
</script>
