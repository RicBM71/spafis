<template>
	<div>
        <loading :show_loading="show_loading"></loading>
        <pendientes
            :dialog_pendientes.sync="dialog_pendientes"
            :reload.sync="reload"
            :factura="factura">
        </pendientes>
        <v-card v-if="show">
            <v-card-title color="indigo">
                <h2 color="indigo">
                        {{titulo}}
                </h2>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-show="computedDesFacturar"
                            v-on="on"
                            color="white"
                            icon
                            @click="goDesfacturar()"
                        >
                            <v-icon color="teal accent-4">lock</v-icon>
                        </v-btn>
                    </template>
                    <span>Desfacturar</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-show="factura.id>0  && isAdmin"
                            v-on="on"
                            color="white"
                            icon
                            @click="goPendientes"
                        >
                            <v-icon color="primary">mdi-calendar-clock</v-icon>
                        </v-btn>
                    </template>
                    <span>Citas pendientes</span>
                </v-tooltip>
                <menu-ope :factura.sync="factura" :reload.sync="reload"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form v-if="show">
                <v-container>
                    <v-row>
                        <v-col cols="12" md="3" offset-md="1">
                            <v-text-field
                                dense
                                v-model="factura.paciente.nom_ape"
                                label="Paciente"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                dense
                                class="centered-input"
                                v-model="factura.ser_fac"
                                label="Factura"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
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
                                    dense
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
                                    v-model="factura.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu1 = false">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                dense
                                v-model="factura.paciente.dni"
                                label="DNI"
                                disabled
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2" offset-md="1">
                            <v-text-field
                                dense
                                v-model="factura.cif"
                                v-validate="'required'"
                                :error-messages="errors.collect('cif')"
                                data-vv-name="cif"
                                data-vv-as="cif"
                                label="CIF"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-text-field
                                dense
                                v-model="factura.razon"
                                label="Paciente"
                                :error-messages="errors.collect('razon')"
                                data-vv-name="razon"
                                data-vv-as="razon"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-select
                                dense
                                v-model="factura.fpago_id"
                                :items="fpagos"
                                label="Forma de Pago"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-select
                                dense
                                v-model="factura.cuenta_id"
                                :items="cuentas"
                                label="IBAN"
                                :disabled="factura.fpago_id!=4"
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="4" offset-md="1">
                            <v-text-field
                                dense
                                v-model="factura.direccion"
                                label="Domicilio"
                                :error-messages="errors.collect('direccion')"
                                data-vv-name="direccion"
                                data-vv-as="direccion"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="1">
                            <v-text-field
                                dense
                                v-model="factura.cpostal"
                                v-validate="'required'"
                                :error-messages="errors.collect('cpostal')"
                                data-vv-name="cpostal"
                                data-vv-as="cpostal"
                                label="CP"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-text-field
                                dense
                                v-model="factura.poblacion"
                                v-validate="'required'"
                                :error-messages="errors.collect('poblacion')"
                                data-vv-name="poblacion"
                                data-vv-as="poblacion"
                                label="Población"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                dense
                                v-model="factura.provincia"
                                v-validate="'required'"
                                :error-messages="errors.collect('provincia')"
                                data-vv-name="provincia"
                                data-vv-as="provincia"
                                label="Provincia"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="7" offset-md="1">
                             <v-text-field
                                dense
                                v-model="factura.notas"
                                :error-messages="errors.collect('notas')"
                                v-validate="'max:191'"
                                data-vv-name="notas"
                                label="Observaciones"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="2">
                            <v-text-field
                                dense
                                v-model="computedFModFormat"
                                :label="factura.username"
                                readonly>
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="1">
                                <v-btn
                                    small
                                    @click="submit"  text rounded  :loading="enviando" block  color="primary">
                                    Guardar
                                </v-btn>

                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
            <v-container>
                <fact-lin
                    v-if="factura.id > 0"
                    :dialog_pendientes.sync="dialog_pendientes"
                    :factura="factura"
                ></fact-lin>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import Factlin from './Factlin'
import {mapGetters} from 'vuex';
import {mapActions} from "vuex";
import {mapState} from 'vuex'
import Pendientes from './Pendientes'
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'fact-lin': Factlin,
            'loading': Loading,
            'pendientes': Pendientes,
		},
    	data () {
      		return {
                titulo:"Factura",
                factura: {
                    id: 0,
                    factura: 0,
                },
                url: "/facturas/facturas",
                ruta: "factura",

                dialog_pendientes: false,
                enviando: false,
                resto: "",
                total_factura: 0,
                reload: 0,
                faclins:[],

                dialog_fac: false,
                dialog_lin_add: false,

                show: false,
                show_loading: true,
                menu1: false,

                fpagos:[],
                cuentas:[],
                empresas:[],
      		}
        },
        mounted(){

            var id = this.$route.params.id;

            if (id > 0)
                axios.get(this.url+'/'+id+'/edit')
                    .then(res => {
                        this.factura = res.data.factura;
                        this.fpagos = res.data.fpagos;
                        this.cuentas = res.data.cuentas;
                        this.reload++;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
                    .finally(() =>{
                        this.show = true;
                        this.show_loading = false;
                    });
        },
        computed: {
            ...mapGetters([
                'isAdmin',
            ]),
            computedFacturar(){

                if (this.factura.id == 0  || !this.hasAddVen) return false;

                if (!this.hasFactura) return false;

                if (this.factura.tipo_id == 3){ //REBU

                    if (this.factura.factura == null && this.factura.fase_id==11)
                        return true;

                    return false;
                }else{
                    if (this.factura.factura == null)
                        return true;

                    return false;
                }
            },
            computedDesFacturar(){

                if (!this.hasFactura || !this.hasAddVen) return false;

                //if (this.factura.factura > 0 && this.factura.fase_id ==11)
                if (this.factura.factura > 0)
                    return true;

                return false;
            },
            computedImporte(){
                return parseFloat(this.factura.importe).toLocaleString('de-DE',{ style: 'currency', currency: 'EUR' });
            },
            computedFecha() {
                moment.locale('es');
                return this.factura.fecha ? moment(this.factura.fecha).format('DD/MM/YYYY') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.factura.updated_at ? moment(this.factura.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.factura.created_at ? moment(this.factura.created_at).format('D/MM/YYYY H:mm') : '';
            }

        },
        watch: {
        },
    	methods:{
            ...mapActions([
            ]),
            goPendientes(){
                this.dialog_pendientes = true;
            },
            getMoneyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR"}).format(parseFloat(value))
            },
            submit() {
                this.$validator.validateAll().then((result) => {
                    if (result){
                        if (this.factura.fpago_id != 4)
                            this.factura.cuenta_id = null;

                        this.show_loading = true;
                        this.enviando = true;
                        axios.put(this.url+"/"+this.factura.id, this.factura)
                            .then(res => {
                                this.factura = res.data.factura;
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
                            })
                            .finally( () =>{
                                // always executed
                                this.enviando = false;
                                this.show_loading = false;
                            });
                        }
                });
            },

        }
  }
</script>
