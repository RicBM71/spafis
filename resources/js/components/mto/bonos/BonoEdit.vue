<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="bono.id"></menu-ope>
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
                                v-model="bono.nombre"
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
                            md="3"
                        >
                            <v-select
                                v-model="bono.tratamiento_id"
                                v-validate="'required'"
                                :error-messages="errors.collect('tratamiento_id')"
                                label="Tratamiento"
                                data-vv-name="tratamiento_id"
                                data-vv-as="tratamiento"
                                :items="tratamientos"
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                         <v-col
                            cols="12"
                            md="2"
                        >
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="bono.importe"
                                v-validate="'required'"
                                class="inputNumber"
                                suffix="€"
                                :error-messages="errors.collect('importe')"
                                label="Importe"
                                data-vv-name="importe"
                                data-vv-as="importe"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                         <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="bono.sesiones"
                                v-validate="'required'"
                                class="inputNumber"
                                :error-messages="errors.collect('sesiones')"
                                label="Sesiones"
                                data-vv-name="sesiones"
                                data-vv-as="sesiones"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                         <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="bono.caducidad"
                                v-validate="'required'"
                                class="inputNumber"
                                :error-messages="errors.collect('caducidad')"
                                label="Caducidad"
                                data-vv-name="caducidad"
                                data-vv-as="caducidad"
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
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="bono.username"
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
                titulo:"Bonos",
                bono: {},
                loading: true,
                tratamientos:[]
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/bonos/'+id+'/edit')
                    .then(res => {
                        this.bono = res.data.bono;
                        this.tratamientos = res.data.tratamientos;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'bono.index'})
                    })
                    .finally(()=>{
                       this.loading = false;
                });
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.bono.updated_at ? moment(this.bono.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.bono.created_at ? moment(this.bono.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;
                    var url = "/mto/bonos/"+this.bono.id;
                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.bono)
                                .then(res => {
                                    this.$toast.success(res.data.message);
                                    this.bono = res.data.registro;
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

    }
  }
</script>
<style scoped>

.inputNumber >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

</style>
