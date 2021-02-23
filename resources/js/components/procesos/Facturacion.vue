<template>
	<div>
            <loading :show_loading="loading"></loading>
            <v-card>
                <v-card-title color="indigo">
                    <h2 color="indigo">{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <menu-ope></menu-ope>
                </v-card-title>
            </v-card>
            <v-card>
                <v-form>
                    <v-container>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" md="2" offset-md="4">
                                    <v-select
                                        v-model="data.area_id"
                                        v-validate="'required'"
                                        data-vv-name="area_id"
                                        data-vv-as="area"
                                        :error-messages="errors.collect('area_id')"
                                        :items="areas"
                                        label="Area"
                                        required
                                        ></v-select>
                                </v-col>
                                <v-col
                                    cols="2"

                                >
                                    <v-menu
                                        v-model="menu1"
                                        :close-on-content-click="false"
                                        :nudge-top="40"
                                        transition="scale-transition"
                                        offset-x
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFecha"
                                            label="Fecha Factura"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            data-vv-name="fecha"
                                            :error-messages="errors.collect('fecha')"
                                            data-vv-as="Fecha"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="data.fecha"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu1 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="2" offset-md="4">
                                    <v-select
                                        v-model="data.fpago_id"
                                        v-validate="'required'"
                                        data-vv-name="fpago_id"
                                        data-vv-as="forma de pago"
                                        :error-messages="errors.collect('fpago_id')"
                                        :items="fpagos"
                                        label="F. Pago"
                                        required
                                        ></v-select>
                                </v-col>
                                <v-col
                                    cols="2"

                                >
                                    <v-menu
                                        v-model="menu_d"
                                        :close-on-content-click="false"
                                        :nudge-top="40"
                                        transition="scale-transition"
                                        offset-x
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFechaD"
                                            label="Desde"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            data-vv-name="fecha_d"
                                            :error-messages="errors.collect('fecha_d')"
                                            data-vv-as="fecha"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="data.fecha_d"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu_d = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col
                                    cols="2"

                                >
                                    <v-menu
                                        v-model="menu_h"
                                        :close-on-content-click="false"
                                        :nudge-top="40"
                                        transition="scale-transition"
                                        offset-x
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFechaH"
                                            label="Hasta"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            data-vv-name="fecha_h"
                                            :error-messages="errors.collect('fecha_h')"
                                            data-vv-as="fecha"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="data.fecha_h"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu_h = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="2" offset-md="4">
                                    <v-select
                                        v-model="data.accion"
                                        v-validate="'required'"
                                        data-vv-name="accion"
                                        data-vv-as="accion"
                                        :error-messages="errors.collect('accion')"
                                        :items="acciones"
                                        label="Acción"
                                        required
                                        ></v-select>
                                </v-col>
                                <v-col cols="12" md="1" offset-md="1">
                                    <div class="text-xs-center">
                                        <v-btn @click="submit"  rounded  :loading="loading"  text color="primary">
                                            Procesar
                                        </v-btn>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-text>
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
            'loading': Loading,
		},
    	data () {
      		return {
                titulo:"Facturación Automática",
                data: {
                    area_id:1,
                    fpago_id: 2,
                    fecha: new Date().toISOString().substr(0, 10),
                    fecha_d: new Date().toISOString().substr(0, 4)+"-01-01",
                    fecha_h: new Date().toISOString().substr(0, 10),
                    accion: 'F'
                },
                menu1: false,
                menu_d: false,
                menu_h: false,
                url: "/facturas/facturacion",
                loading: false,

                areas:[],
                fpagos:[],
                acciones:[
                    {value: 'F', text: 'Facturar'},
                    {value: 'D', text: 'Desfacturar'},
                ]


      		}
        },
        mounted(){

            if (!this.isAdmin){
                this.$toast.error('No dispone de permisos!');
                this.$router.push({ name: 'dash' })
                return;
            }

            axios.get(this.url)
                .then(res => {

                    this.fpagos = res.data.fpagos;
                    this.areas = res.data.areas;

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
        },
        computed: {
            ...mapGetters([
                'isAdmin',
            ]),
            computedFecha() {
                moment.locale('es');
                return this.data.fecha ? moment(this.data.fecha).format('L') : '';
            },
            computedFechaD() {
                moment.locale('es');
                return this.data.fecha_d ? moment(this.data.fecha_d).format('L') : '';
            },
            computedFechaH() {
                moment.locale('es');
                return this.data.fecha_h ? moment(this.data.fecha_h).format('L') : '';
            }
        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(this.url, this.data)
                                .then(res => {

                                    console.log(res);

                                    this.$toast.success(res.data);

                                    this.loading = false;
                                    //this.$router.push({ name: this.ruta+'.edit', params: { id: res.data.paciente.id } })
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
