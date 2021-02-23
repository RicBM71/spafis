<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="tratamiento.id"></menu-ope>
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
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="tratamiento.nombre"
                                v-validate="'required'"
                                :error-messages="errors.collect('nombre')"
                                label="Nombre"
                                data-vv-name="nombre"
                                data-vv-as="nombre"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
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
                tratamiento: {
                    id:       0,
                    nombre: null,
                },

                titulo:   "Crear Tratamiento",
                tratamientos: [],

                loading: true,

      		}
        },
        mounted(){

            axios.get('/mto/tratamientos/create')
                .then(res => {
                    this.tratamientos = res.data.tratamientos;
                })
                .catch(err => {
                     if (err.response.status != 401){
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'tratamiento.index'});
                        }
                })
                .finally(()=>{
                     this.loading = false;
                });
        },
        computed: {
            ...mapGetters([
	    		'isAdmin'
    		]),
            computedFModFormat() {
                moment.locale('es');
                return this.tratamiento.updated_at ? moment(this.tratamiento.updated_at).format('D/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.tratamiento.created_at ? moment(this.tratamiento.created_at).format('D/MM/YYYY H:mm') : '';
            },

        },
    	methods:{
            submit() {
                if (this.loading === false){
                    this.loading = true;

                    var url = "/mto/tratamientos";

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(url, this.tratamiento)
                                .then(res => {

                                    this.$router.push({ name: 'tratamiento.edit', params: { id: res.data.registro.id } })
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
