<template>
	<div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="cuenta.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-row>
                        <v-col
                            cols="12"
                            md="3"
                            offset-md="3"
                        >
                            <v-text-field
                                v-model="cuenta.nombre"
                                v-validate="'required'"
                                :error-messages="errors.collect('nombre')"
                                label="Nombre"
                                data-vv-name="nombre"
                                data-vv-as="nombre"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="1">
                            <v-btn @click="submit" rounded text :loading="loading" color="primary">
                                Guardar</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
	</div>
</template>
<script>
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
                titulo:"Cuenta-IBAN",
                cuenta: {
                    id:       0,
                    nombre:  "",
                    activa: true,
                },
        		status: false,
                loading: false,

                show: false,
                show_loading: true
      		}
        },
        mounted(){
            this.show_loading = false;
        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    var url = "/admin/cuentas";

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(url,this.cuenta)
                                .then(response => {

                                    this.$toast.success(response.data.message);

                                    this.loading = false;
                                    this.$router.push({ name: 'cuenta.edit', params: { id: response.data.cuenta.id } })
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
