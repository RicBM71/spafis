<template>
	<div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="mutua.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-row>
                        <v-col
                            cols="12"
                            md="5"
                        >
                            <v-text-field
                                v-model="mutua.nombre"
                                v-validate="'required'"
                                :error-messages="errors.collect('nombre')"
                                label="Nombre"
                                data-vv-name="nombre"
                                data-vv-as="nombre"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                         <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="mutua.cif"
                                v-validate="'max:10'"
                                :error-messages="errors.collect('cif')"
                                label="Cif"
                                data-vv-name="cif"
                                data-vv-as="cif"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-btn @click="submit" small rounded  :loading="loading" block color="primary">Guardar</v-btn>
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
                titulo:"Sociedad",
                mutua: {
                    nombre: '',
                    cif: null
                },
                url: "/mto/mutuas",
                ruta: "mutua",
                loading: false,

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

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(this.url, this.mutua)
                                .then(response => {

                                    this.$toast.success(response.data.message);

                                    this.loading = false;
                                    this.$router.push({ name: this.ruta+'.edit', params: { id: response.data.mutua.id } })
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
