<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <v-tooltip bottom v-if="items.length > 0">
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            color="white"
                            icon
                            @click="goExcel"
                        >
                            <v-icon color="green darken-4">mdi-file-excel</v-icon>
                        </v-btn>
                    </template>
                    <span>Exportar a Excel</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            color="white"
                            icon
                            @click="goBack"
                        >
                            <v-icon color="primary">mdi-arrow-left</v-icon>
                        </v-btn>
                    </template>
                    <span>Lista</span>
                </v-tooltip>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-card-text>
                        <v-row>
                            <v-spacer></v-spacer>
                            <v-col cols="12" md="2">
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
                                        v-model="data.fecha"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu1 = false">
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" md="1" offset-md="1">
                                <div class="text-xs-center">
                                    <v-btn @click="submit"  rounded  :loading="loading"  text color="primary">
                                        enviar
                                    </v-btn>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-simple-table dense>
                                    <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th class="text-left">
                                                Mes
                                            </th>
                                            <th class="text-center">
                                                Ejercicio {{ejercicios[0]}}
                                            </th>
                                            <th class="text-center">
                                                Ejercicio {{ejercicios[1]}}
                                            </th>
                                            <th class="text-center">
                                                Dif. Abs.
                                            </th>
                                            <th class="text-center">
                                                %
                                            </th>
                                            <th class="text-center">
                                                Ejercicio {{ejercicios[2]}}
                                            </th>
                                            <th class="text-center">
                                                Dif. Abs.
                                            </th>
                                            <th class="text-center">
                                                %
                                            </th>
                                            <th class="text-center">
                                                Ejercicio {{ejercicios[3]}}
                                            </th>
                                            <th class="text-center">
                                                Dif. Abs.
                                            </th>
                                            <th class="text-center">
                                                %
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in items"
                                            :key="item.mes"
                                        >
                                            <td class="text-left">{{item.mes}}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.eje1) }}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.eje2) }}</td>
                                            <td :class="clase(item.dif1_2)">{{ getCurrencyFormat(item.dif1_2) }}</td>
                                            <td :class="clase(item.por1_2)">{{ getPorcentaje(item.por1_2) }}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.eje3) }}</td>
                                            <td :class="clase(item.dif1_3)">{{ getCurrencyFormat(item.dif1_3) }}</td>
                                            <td :class="clase(item.por1_3)">{{ getPorcentaje(item.por1_3) }}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.eje4) }}</td>
                                            <td :class="clase(item.dif1_4)">{{ getCurrencyFormat(item.dif1_4) }}</td>
                                            <td :class="clase(item.por1_4)">{{ getPorcentaje(item.por1_4) }}</td>
                                        </tr>
                                    </tbody>
                                    </template>
                                </v-simple-table>
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
import {mapGetters} from 'vuex';
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'loading': Loading,
		},
    	data () {
      		return {
                titulo:"Cobros x Mes",
                data: {
                    area_id:1,
                    fecha: new Date().toISOString().substr(0, 10),
                },
                menu1: false,
                url: "/consultas/cobrosmes",
                loading: false,

                areas:[],
                items:[],
                ejercicios:[],

        items:[],
  		}
        },
        mounted(){
            axios.get(this.url)
                .then(res => {

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
                        this.$router.push({ name: 'dash' })
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
        },
    	methods:{
            goBack(){
                this.$router.go(-1);
            },
            clase(item){
                return item >= 0 ? 'text-right blue--text' : 'text-right red--text';
            },
            getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
            },
            getPorcentaje(value){
                return new Intl.NumberFormat("de-DE",{style: "decimal",minimumFractionDigits:2}).format(parseFloat(value))+"%";
            },
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(this.url, this.data)
                                .then(res => {

                                    this.ejercicios = res.data.ejercicios;
                                    this.items = res.data.items;

                                    this.loading = false;
                                    //this.$router.push({ name: this.ruta+'.edit', params: { id: response.data.paciente.id } })
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
            goExcel(){

                this.show_loading = true;

                axios({
                    url: "/consultas/cobrosmes/excel",
                    method: 'POST',
                    responseType: 'blob', // important
                    data:{  area_id: this.data.area_id,
                            fecha: this.data.fecha
                         }
                    })
                .then(response => {

                    let blob = new Blob([response.data])
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)

                    link.download = "Inf."+new Date().getFullYear()+(new Date().getMonth()+1)+(new Date().getDate())+'.xlsx';

                    document.body.appendChild(link);
                    link.click()
                    document.body.removeChild(link);

                    this.$toast.success('Download Ok! '+link.download);

                    this.show_loading = false;

                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.show_loading = false;
                });

            }


    }
  }
</script>
