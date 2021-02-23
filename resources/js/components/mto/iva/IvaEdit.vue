<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="iva.id"></menu-ope>
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
                                v-model="iva.nombre"
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
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="iva.importe"
                                v-validate="'required'"
                                class="inputNumber"
                                suffix="%"
                                :error-messages="errors.collect('importe')"
                                label="Valor"
                                data-vv-name="importe"
                                data-vv-as="importe"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="2"
                        >
                        </v-col>
                        <v-col cols="12" md="8">
                            <v-text-field
                                v-model="iva.leyenda"
                                label="Leyenda"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col
                            cols="12"
                            md="2"
                        >
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="iva.username"
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
                titulo:"Tipo IVA",
                iva: {
                    id:       0,
                    nombre:  "",
                    leyenda: "",
                    username:"",
                    updated_at:"",
                    created_at:"",
                },

        		status: false,
                loading: false,

                show: false,
                show_loading: true,
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/ivas/'+id+'/edit')
                    .then(res => {

                        this.iva = res.data.iva;
                        this.show = true;
                        this.show_loading = false;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'iva.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.iva.updated_at ? moment(this.iva.updated_at).format('D/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.iva.created_at ? moment(this.iva.created_at).format('D/MM/YYYY H:mm') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;
                    var url = "/mto/ivas/"+this.iva.id;
                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url,this.iva)
                                .then(response => {
                                    this.$toast.success(response.data.message);
                                    this.iva = response.data.iva;
                                    this.loading = false;
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
