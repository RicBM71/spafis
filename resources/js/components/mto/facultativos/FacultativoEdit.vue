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
                            <v-icon color="primary">mdi-alarm</v-icon>
                        </v-btn>
                    </template>
                    <span>Horario</span>
                </v-tooltip>
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
                            <v-text-field
                                v-model="facultativo.apellidos"
                                v-validate="'required'"
                                :error-messages="errors.collect('apellidos')"
                                label="Apellidos"
                                data-vv-name="apellidos"
                                data-vv-as="apellidos"
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
                                v-model="facultativo.colegiado"
                                v-validate="'numeric'"
                                :error-messages="errors.collect('colegiado')"
                                label="Colegiado"
                                data-vv-name="colegiado"
                                data-vv-as="colegiado"
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
                                v-model="facultativo.cif"
                                v-validate="'alpha_num'"
                                :error-messages="errors.collect('cif')"
                                label="Cif"
                                data-vv-name="cif"
                                data-vv-as="cif"
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
                                v-model="facultativo.numero_ss"
                                v-validate="'alpha_dash'"
                                :error-messages="errors.collect('numero_ss')"
                                label="Número SS"
                                data-vv-name="numero_ss"
                                data-vv-as="numero_ss"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-select
                                v-model="facultativo.categoria_id"
                                v-validate="'required'"
                                :error-messages="errors.collect('categoria_id')"
                                label="Categoría"
                                data-vv-name="categoria_id"
                                data-vv-as="categoria"
                                :items="categorias"
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.direccion"
                                v-validate="'max:50'"
                                :error-messages="errors.collect('direccion')"
                                label="Dirección"
                                data-vv-name="direccion"
                                data-vv-as="direccion"
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
                                v-model="facultativo.cpostal"
                                v-validate="'max:5'"
                                :error-messages="errors.collect('cpostal')"
                                label="CP"
                                data-vv-name="cpostal"
                                data-vv-as="cpostal"
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
                                v-model="facultativo.poblacion"
                                v-validate="'max:50'"
                                :error-messages="errors.collect('poblacion')"
                                label="Poblacion"
                                data-vv-name="poblacion"
                                data-vv-as="poblacion"
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
                                v-model="facultativo.provincia"
                                v-validate="'max:50'"
                                :error-messages="errors.collect('provincia')"
                                label="Provincia"
                                data-vv-name="provincia"
                                data-vv-as="provincia"
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
                                v-model="facultativo.email"
                                v-validate="'email'"
                                :error-messages="errors.collect('email')"
                                label="email"
                                data-vv-name="email"
                                data-vv-as="email"
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
                            <v-text-field
                                v-model="facultativo.alias"
                                v-validate="'required'"
                                :error-messages="errors.collect('alias')"
                                label="Alias"
                                data-vv-name="alias"
                                data-vv-as="alias"
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
                                v-model="computedFechaNac"
                                mask="##/##/####"
                                clearable
                                @click:clear="clearFechaNac"
                                :error-messages="errors.collect('fecha_nacimiento')"
                                label="F. Nacimiento"
                                data-vv-name="fecha_nac"
                                v-on:keyup.enter="submit"                                    >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.telefonom"
                                v-validate="'numeric|max:10'"
                                :error-messages="errors.collect('telefonom')"
                                label="Móvil"
                                data-vv-name="telefonom"
                                data-vv-as="telefonom"
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
                                v-model="facultativo.telefono1"
                                v-validate="'numeric|max:10'"
                                :error-messages="errors.collect('telefono1')"
                                label="Teléfono"
                                data-vv-name="telefono1"
                                data-vv-as="telefono1"
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.telefono2"
                                v-validate="'numeric|max:10'"
                                :error-messages="errors.collect('telefono2')"
                                label="Teléfono 2"
                                data-vv-name="telefono2"
                                data-vv-as="telefono2"
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-col>
                         <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.orden"
                                v-validate="'numeric'"
                                :error-messages="errors.collect('orden')"
                                label="Orden"
                                data-vv-name="orden"
                                data-vv-as="orden"
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-col>
                         <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.grupo_id"
                                v-validate="'numeric'"
                                :error-messages="errors.collect('grupo_id')"
                                label="grupo_id"
                                data-vv-name="grupo_id"
                                data-vv-as="grupo_id"
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="facultativo.fecha_baja"
                                :error-messages="errors.collect('fecha_baja')"
                                label="Fecha Baja"
                                data-vv-name="fecha_baja"
                                data-vv-as="fecha_baja"
                                v-on:keyup.enter="submit"
                                readonly
                            >
                            </v-text-field>
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="1"
                        >
                            <v-text-field
                                v-model="facultativo.objetivo"
                                v-validate="'numeric'"
                                :error-messages="errors.collect('objetivo')"
                                label="Objetivo"
                                hint="Nº sesiones mes"
                                data-vv-name="objetivo"
                                data-vv-as="objetivo"
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="facultativo.iban"
                                :error-messages="errors.collect('iban')"
                                label="IBAN"
                                counter=30
                                data-vv-name="iban"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>

                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="facultativo.username"
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
                    <v-row>
                     <v-col cols="12" md="2">
                             <!-- <v-color-picker
                                class="ma-2"
                                hide-canvas
                                v-model="facultativo.color"
                                ></v-color-picker> -->

                                <v-color-picker
                                v-model="facultativo.color"
                                class="ma-2"
                                dot-size="30"
                                canvas-height="100"
                                ></v-color-picker>

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
                facultativo: {},
                loading: true,
                categorias:[]
      		}
        },
        beforeMount(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/mto/facultativos/'+id+'/edit')
                    .then(res => {
                        this.facultativo = res.data.registro;
                        this.categorias = res.data.categorias;
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
            computedFechaNac: {
                // getter
                get: function () {
                  moment.locale('es');
                    return this.facultativo.fecha_nacimiento ? moment(this.facultativo.fecha_nacimiento).format('DD/MM/YYYY') : '';
                },
                // setter
                set: function (newValue) {

                    if ( newValue != null && newValue.length == 8 ){
                            var f = newValue.substring(4,8)+"-"+
                                    newValue.substring(2,4)+"-"+
                                    newValue.substring(0,2);

                        this.facultativo.fecha_nacimiento = f;
                    }
                }
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
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;
                    var url = "/mto/facultativos/"+this.facultativo.id;
                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.facultativo)
                                .then(res => {
                                    this.$toast.success(res.data.message);
                                    this.facultativo = res.data.registro;
                                    //this.$validator.reset();
                                    this.errors.clear()
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
            goHorario(){
                this.$router.push({ name: 'horario.index'})
            },
            clearFechaNac(){
                this.facultativo.fecha_nacimiento = null;

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
