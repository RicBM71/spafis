<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="pacbono.id" :paciente_id="pacbono.paciente_id" :pacbono="pacbono"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>

                        <v-form>
                            <v-row>
                                <v-col cols="12" md="2"></v-col>
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
                                            v-model="pacbono.fecha"
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
                                        <v-select
                                            v-model="pacbono.bono_id"
                                            v-validate="'required|numeric'"
                                            :error-messages="errors.collect('bono_id')"
                                            label="Tipo Bono"
                                            data-vv-name="bono_id"
                                            data-vv-as="tipo"
                                            :items="bonos"
                                        ></v-select>
                                </v-col>
                                <v-col cols="12" md="1"></v-col>

                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6"></v-col>
                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-btn @click="submit"  rounded  :loading="loading" small color="primary">
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
                pacbono: {
                    fecha:  new Date().toISOString().substr(0, 10),
                    paciente_id: 0,
                    bono_id: null,
                    bono: null
                },

                titulo:   "Nuevo Bono",
                bonos: [],
                menu1:false,

                loading: false,
                show_loading: false,

      		}
        },
        mounted(){

            this.pacbono.paciente_id = this.$route.params.id;

            axios.get('/mto/pacbonos/create')
                .then(res => {

                    this.bonos = res.data.bonos;
                })
                .catch(err => {
                     if (err.response.status != 401){
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'paciente.edit', params: { id: this.pacbono.paciente_id } })
                        }
                })
                .finally(()=>{
                     this.show_loading = false;
                });
        },
        computed: {
            ...mapGetters([
	    		'isAdmin'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.pacbono.fecha ? moment(this.pacbono.fecha).format('L') : '';
            },
        },
    	methods:{
            submit() {
                if (this.loading === false){
                    this.loading = true;

                    var url = "/mto/pacbonos";

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(url, this.pacbono)
                                .then(res => {

                                    this.$router.push({ name: 'pacbono.edit', params: { id: res.data.pacbono.id } })
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
