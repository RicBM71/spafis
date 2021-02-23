<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="facultativo.id"></menu-ope>
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
                            <v-text-field
                                v-model="facultativo.nombre"
                                label="Nombre"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.apellidos"
                                label="Apellidos"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.colegiado"
                                label="Colegiado"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="facultativo.cif"
                                label="Cif"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="facultativo.numero_ss"
                                label="Número SS"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="facultativo.categoria.nombre"
                                label="Categoría"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.direccion"
                                label="Dirección"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.cpostal"
                                label="CP"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.poblacion"
                                label="Poblacion"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="facultativo.provincia"
                                label="Provincia"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.email"
                                label="email"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                         <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="facultativo.alias"
                                label="Alias"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="computedFechaNac"
                                label="F. Nacimiento"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.telefonom"
                                label="Móvil"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.telefono1"
                                label="Teléfono"
                                disabled
                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.telefono2"
                                label="Teléfono 2"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                         <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.orden"
                                label="Orden"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="computedFechaBaja"
                                label="Fecha Baja"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-color-picker
                                hide-canvas
                                hide-inputs
                                v-model="facultativo.color"
                                flat
                            ></v-color-picker>
                        </v-col>
                    </v-row>
                    <v-row>

                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.iban"
                                label="IBAN"
                                disabled
                            >
                            </v-text-field>
                        </v-col>

                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="facultativo.username"
                                label="Usuario"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                disabled
                            >
                            </v-text-field>
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
                titulo:"Facultativo",
                facultativo: { categoria:{nombre: ""}},
                loading: true,
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/facultativos/'+id)
                    .then(res => {
                        this.facultativo = res.data.registro;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'facultativo.index'})
                    })
                    .finally(()=>{
                       this.loading = false;
                });
        },
        computed: {
            computedFechaNac() {
                moment.locale('es');
                return this.facultativo.fecha_nacimiento ? moment(this.facultativo.fecha_nacimiento).format('D/MM/YYYY') : '';
            },
            computedFechaBaja() {
                moment.locale('es');
                return this.facultativo.fecha_baja ? moment(this.facultativo.fecha_baja).format('D/MM/YYYY') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.facultativo.updated_at ? moment(this.facultativo.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.facultativo.created_at ? moment(this.facultativo.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{}
  }
</script>
<style scoped>

.inputNumber >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

</style>
