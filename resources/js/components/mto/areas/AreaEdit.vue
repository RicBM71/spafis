<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="area.id"></menu-ope>
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
                                v-model="area.nombre"
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
                        <v-col
                            cols="12"
                            md="2"
                        >
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="area.hora1"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('hora1')"
                                label="Inicio"
                                data-vv-name="hora1"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="area.hora2"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('hora2')"
                                label="Fin"
                                data-vv-name="hora2"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="area.tarde"
                                v-validate="{ required: true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/ }"
                                :error-messages="errors.collect('tarde')"
                                label="Tarde"
                                data-vv-name="tarde"
                                data-vv-as="hora"
                                v-on:keyup.enter="submit"
                            ></v-text-field>
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
                                v-model="area.frecuencia"
                                v-validate="'required|numeric'"
                                :error-messages="errors.collect('frecuencia')"
                                label="Frecuencia"
                                data-vv-name="frecuencia"
                                data-vv-as="frecuencia"
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
                                v-model="area.username"
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
                titulo:"Areas",
                area: {},
                loading: true,
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/areas/'+id+'/edit')
                    .then(res => {
                        this.area = res.data.area;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'area.index'})
                    })
                    .finally(()=>{
                       this.loading = false;
                });
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.area.updated_at ? moment(this.area.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.area.created_at ? moment(this.area.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;
                    var url = "/mto/areas/"+this.area.id;
                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.area)
                                .then(res => {
                                    this.$toast.success(res.data.message);
                                    this.area = res.data.area;
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
