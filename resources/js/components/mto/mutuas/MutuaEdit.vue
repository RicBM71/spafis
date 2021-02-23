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
                            md="2"
                        >
                            <v-text-field
                                v-model="mutua.nombre"
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
                            md="2"
                        >
                            <v-text-field
                                v-model="mutua.cif"
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
                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="mutua.direccion"
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
                                v-model="mutua.cpostal"
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
                                v-model="mutua.poblacion"
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
                                v-model="mutua.provincia"
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

                    </v-row>
                    <v-row>
                         <v-col
                            cols="12"
                            md="2"
                        >
                            <v-text-field
                                v-model="mutua.contacto"
                                v-validate="'max:50'"
                                :error-messages="errors.collect('contacto')"
                                label="Contacto"
                                data-vv-name="contacto"
                                data-vv-as="contacto"
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
                                v-model="mutua.telefonom"
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
                                v-model="mutua.telefono1"
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
                                v-model="mutua.telefono2"
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
                            md="3"
                        >
                            <v-text-field
                                v-model="mutua.email"
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

                        <v-col cols="12" md="2">
                            <v-text-field
                                v-model="mutua.username"
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
                titulo:"Editar",
                mutua: {},
                url: "/mto/mutuas",
                ruta: "mutua",
                loading: false,
                menu1: false,

                show: false,
                show_loading: true,

      		}
        },
        mounted(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get(this.url+'/'+id+'/edit')
                    .then(res => {

                        this.mutua = res.data.mutua;

                        this.show = true;
                        this.show_loading = false;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
        },
        computed: {
        ...mapGetters([
                'isAdmin'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.mutua.fecha ? moment(this.mutua.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.mutua.updated_at ? moment(this.mutua.updated_at).format('DD/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.mutua.created_at ? moment(this.mutua.created_at).format('DD/MM/YYYY H:mm') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                             axios.put(this.url+"/"+this.mutua.id, this.mutua)
                                .then(res => {

                                    this.$toast.success(res.data.message);
                                    this.mutua = res.data.mutua;
                                    this.loading = false;
                                    this.$validator.reset();
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
                                })
                                .finally(()=> {
                                    this.show_loading = false;
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
