<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>

            </v-card-title>
        </v-card>
        <v-card>
            <v-tabs fixed-tabs>
                <v-tab>
                        Parámetros
                </v-tab>
                <v-tab>
                        Imágnes Inicio
                </v-tab>
                    <v-tab-item>
                        <v-form>
                            <v-container>
                                <v-row>
                                    <v-flex sm2>
                                        <v-text-field
                                            v-model="parametro.lim_efe"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('lim_efe')"
                                            label="Límite Efectivo"
                                            data-vv-name="lim_efe"
                                            data-vv-as="lim_efe"
                                            required
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex sm2>
                                        <v-text-field
                                            v-model="parametro.lim_efe_nores"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('lim_efe_nores')"
                                            label="Límite No Res."
                                            data-vv-name="lim_efe_nores"
                                            data-vv-as="lim_efe_nores"
                                            required
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-flex>

                                    <v-flex sm2>
                                        <v-text-field
                                            v-model="parametro.carpeta_docs"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('carpeta_docs')"
                                            label="Carpeta Documentos"
                                            data-vv-name="carpeta_docs"
                                            data-vv-as="carpeta"
                                            required
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-flex>

                                </v-row>
                                <v-row>
                                    <v-flex sm12>
                                        <v-text-field
                                            v-model="parametro.pie_rebu1"
                                            :error-messages="errors.collect('pie_rebu1')"
                                            label="Pie Rebu"
                                            data-vv-name="pie_rebu1"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-flex>
                                </v-row>
                                <v-row>
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
                                            v-model="parametro.fecha"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu1 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                </v-row>
                                <v-row>
                                    <v-flex sm2>
                                        <v-text-field
                                            v-model="parametro.username"
                                            label="Usuario"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex sm2>
                                        <v-text-field
                                            v-model="computedFModFormat"
                                            label="Modificado"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex sm2>
                                        <v-text-field
                                            v-model="computedFCreFormat"
                                            label="Creado"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex sm5>
                                    </v-flex>
                                    <v-flex sm2>
                                        <div class="text-xs-center">
                                                    <v-btn @click="submit"  rounded  :loading="loading"  color="primary">
                                            Guardar
                                            </v-btn>
                                        </div>
                                    </v-flex>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-tab-item>
                    <v-tab-item>
                        <v-container>
                            <v-layout row wrap>
                                <v-flex sm2></v-flex>
                                <v-flex sm3 v-if="parametro.img1==null">
                                    <vue-dropzone
                                            ref="myVueDropzone"
                                            id="dropzone"
                                            :options="dropzoneOptions"
                                            v-on:vdropzone-success="uploadLogo"
                                    ></vue-dropzone>
                                </v-flex>
                                <v-flex sm3 v-else>
                                    <v-img class="img-fluid" :src="parametro.img1"></v-img>
                                    <v-btn @click="borraLogo" flat round><v-icon color="red darken-4">mdi-delete</v-icon> Eliminar Imagen Principal</v-btn>
                                </v-flex>
                                <v-flex sm1></v-flex>
                                <v-flex sm3 v-if="parametro.img2==null">
                                    <vue-dropzone
                                            ref="myVueDropzone2"
                                            id="dropzone2"
                                            :options="dropzoneOptions2"
                                            v-on:vdropzone-success="uploadFondo"
                                    ></vue-dropzone>
                                </v-flex>
                                <v-flex sm3 v-else>
                                    <v-img class="img-fluid" :src="parametro.img2"></v-img>
                                    <v-btn @click="borraFondo" flat round><v-icon color="red darken-4">mdi-delete</v-icon> Eliminar Imagen Sección</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tab-item>
                </v-tabs>

            </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import vue2Dropzone from 'vue2-dropzone'
import {mapGetters} from 'vuex';
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'vueDropzone': vue2Dropzone,
		},
    	data () {
      		return {
                titulo:"Parámetros",
                parametro: {
                    id: 0,
                },

        		status: false,
                loading: false,

                show: false,
                menu: false,
                menu1: false,
                dropzoneOptions: {
                    url: '/admin/parametros/main',
                    paramName: 'imagen',
                    acceptedFiles: 'image/*',
                    thumbnailWidth: 150,
                    maxFiles: 1,
                    maxFilesize: 2,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra la imagen PRINCIPAL aquí'
                },
                dropzoneOptions2: {
                    url: '/admin/parametros/section',
                    paramName: 'imagen',
                    acceptedFiles: 'image/*',
                    thumbnailWidth: 150,
                    maxFiles: 1,
                    maxFilesize: 2,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra la imagen SECCION aquí'
                }

      		}
        },
        mounted(){
                axios.get('/admin/parametros')
                    .then(res => {

                        this.parametro = res.data;

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash'})
                    })
        },
        computed: {
            ...mapGetters([
                'isRoot'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.parametro.fecha ? moment(this.parametro.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.parametro.updated_at ? moment(this.parametro.updated_at).format('D/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.parametro.created_at ? moment(this.parametro.created_at).format('D/MM/YYYY H:mm') : '';
            }

        },
    	methods:{

            uploadLogo(file, response){
                this.parametro = response.parametro;
            },
            uploadFondo(file, response){
                this.parametro = response.parametro;
            },
            borraLogo(){
                axios.put('/admin/parametros/main/delete')
                    .then(response => {
                        this.parametro = response.data.parametro;
                        this.loading = false;
                    })
            },
            borraFondo(){
                axios.put('/admin/parametros/section/delete')
                    .then(response => {
                        this.parametro = response.data.parametro;
                        this.loading = false;
                    })
            },
            submit() {
                if (this.loading === false){

                    this.loading = true;

                    var url = "/admin/parametros/"+this.parametro.id;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.parametro)
                                .then(response => {
                                    this.$toast.success(response.data.message);
                                    this.parametro = response.data.parametro;
                                    this.loading = false;
                                })
                                .catch(err => {

                                    if (err.request.status == 422){ // fallo de validated.
                                        const msg_valid = err.response.data.errors;
                                        for (const prop in msg_valid) {
                                            // this.$toast.error(`${msg_valid[prop]}`);

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
<style scoped>

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}

.v-form>.container>.layout>.flex {
    padding: 1px 8px 1px 8px;
}
.v-text-field {
    padding-top: 6px;
    margin-top: 2px;
}
</style>
