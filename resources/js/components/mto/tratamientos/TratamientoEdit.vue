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
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="tratamiento.nombre_reducido"
                                v-validate="'required'"
                                :error-messages="errors.collect('nombre_reducido')"
                                label="Abreviatura"
                                data-vv-name="nombre_reducido"
                                data-vv-as="abreviatura"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="tratamiento.nombre_web"
                                v-validate="'required'"
                                :error-messages="errors.collect('nombre_web')"
                                label="Nombre web"
                                data-vv-name="nombre_web"
                                data-vv-as="web"
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
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-select
                                v-model="tratamiento.iva_id"
                                v-validate="'numeric'"
                                :error-messages="errors.collect('iva_id')"
                                label="IVA"
                                data-vv-name="iva_id"
                                data-vv-as="iva_id"
                                :items="ivas"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="tratamiento.importe"
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
                                v-model="tratamiento.importe_reducido"
                                v-validate="'required'"
                                class="inputNumber"
                                suffix="€"
                                :error-messages="errors.collect('importe_reducido')"
                                label="Importe Reducido"
                                data-vv-name="importe_reducido"
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
                                v-model="tratamiento.precio_coste"
                                v-validate="'required'"
                                class="inputNumber"
                                suffix="€"
                                :error-messages="errors.collect('precio_coste')"
                                label="P. Coste"
                                data-vv-name="precio_coste"
                                data-vv-as="precio coste"
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
                        ></v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="tratamiento.duracion_manual"
                                v-validate="'required'"
                                class="inputNumber"
                                :error-messages="errors.collect('duracion_manual')"
                                label="Duración manual"
                                data-vv-name="duracion_manual"
                                data-vv-as="duración manual"
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
                                v-model="tratamiento.duracion_aparatos"
                                v-validate="'required'"
                                class="inputNumber"
                                :error-messages="errors.collect('duracion_aparatos')"
                                label="Duración aparatos"
                                data-vv-name="duracion_aparatos"
                                data-vv-as="duración aparatos"
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
                                v-model="tratamiento.edad"
                                v-validate="'required'"
                                class="inputNumber"
                                :error-messages="errors.collect('edad')"
                                label="Edad"
                                data-vv-name="edad"
                                data-vv-as="edad"
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
                            <v-switch
                                label="Venta TPV"
                                v-model="tratamiento.tpv"
                                color="primary">
                            ></v-switch>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-switch
                                label="Inventario"
                                v-model="tratamiento.inventario"
                                color="primary">
                            ></v-switch>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-switch
                                label="Activo"
                                v-model="tratamiento.activo"
                                color="primary">
                            ></v-switch>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-switch
                                label="Admite bono"
                                v-model="tratamiento.bono"
                                color="primary">
                            ></v-switch>
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
                                v-model="tratamiento.username"
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
                titulo:"Tratamientos",
                tratamiento: {},
                loading: true,
                tratamientos:[],
                ivas:[]
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/tratamientos/'+id+'/edit')
                    .then(res => {
                        this.tratamiento = res.data.tratamiento;
                        this.tratamientos = res.data.tratamientos;
                        this.ivas = res.data.ivas;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'tratamiento.index'})
                    })
                    .finally(()=>{
                       this.loading = false;
                });
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.tratamiento.updated_at ? moment(this.tratamiento.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.tratamiento.created_at ? moment(this.tratamiento.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;
                    var url = "/mto/tratamientos/"+this.tratamiento.id;
                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.tratamiento)
                                .then(res => {
                                    this.$toast.success(res.data.message);
                                    this.tratamiento = res.data.registro;
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
