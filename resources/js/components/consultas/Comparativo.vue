<template>
	<div ref="div">
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
                                    v-model="menud"
                                    :close-on-content-click="false"
                                    :nudge-top="40"
                                    transition="scale-transition"
                                    offset-x
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="computedFechaD"
                                        label="Fecha"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        data-vv-name="fechad"
                                        :error-messages="errors.collect('fechad')"
                                        data-vv-as="Fecha"
                                        v-on="on"
                                    ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="data.fechad"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menud = false">
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                             <v-col
                                cols="2"

                            >
                                <v-menu
                                    v-model="menuh"
                                    :close-on-content-click="false"
                                    :nudge-top="40"
                                    transition="scale-transition"
                                    offset-x
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="computedFechaH"
                                        label="Fecha"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        data-vv-name="fechah"
                                        :error-messages="errors.collect('fechah')"
                                        data-vv-as="Fecha"
                                        v-on="on"
                                    ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="data.fechah"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menuh = false">
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
                                                Tratamiento
                                            </th>
                                            <th class="text-center">
                                                Ej. Actual
                                            </th>
                                            <th class="text-center">
                                                Ej. Anterior
                                            </th>
                                            <th class="text-center">
                                                Dif. Abs.
                                            </th>
                                            <th class="text-center">
                                                %
                                            </th>
                                            <th class="text-center">
                                                Valores
                                            </th>
                                            <th class="text-center">
                                                Valores AA
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
                                            <td class="text-left">{{item.tratamiento}}</td>
                                            <td class="text-right">{{ getDecimal(item.p1,0) }}</td>
                                            <td class="text-right">{{ getDecimal(item.p2,0) }}</td>
                                            <td :class="clase(item.difabs)">{{ getDecimal(item.difabs,0) }}</td>
                                            <td :class="clase(item.difrel)">{{ getPorcentaje(item.difrel) }}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.i1) }}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.i2) }}</td>
                                            <td :class="clase(item.idifabs)">{{ getCurrencyFormat(item.idifabs) }}</td>
                                            <td :class="clase(item.idifrel)">{{ getPorcentaje(item.idifrel) }}</td>
                                        </tr>
                                    </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                        </v-row>
                         <v-row v-if="items.length > 0">
                            <v-col cols="4">
                                <v-simple-table dense>
                                    <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">
                                                Nuevos Actual
                                            </th>
                                            <th class="text-center">
                                                Nuevos A/A
                                            </th>
                                            <th class="text-center">
                                                Tratados Act.
                                            </th>
                                            <th class="text-center">
                                                Tratados A/A
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold">Pacientes</td>
                                            <td class="text-center">{{pacientes.act}}</td>
                                            <td class="text-center">{{pacientes.ant}}</td>
                                            <td class="text-center">{{pacientes.uni_act}}</td>
                                            <td class="text-center">{{pacientes.uni_ant}}</td>
                                        </tr>
                                    </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                            <v-col cols="3">
                                <v-simple-table dense>
                                    <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th>Facultativo</th>
                                            <th class="text-center">
                                                Sesiones
                                            </th>
                                            <th class="text-center">
                                                % Obj.
                                            </th>
                                            <th class="text-center">
                                                Importe
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in trafis"
                                            :key="item.alias"
                                        >
                                            <td class="font-weight-bold">{{item.alias}}</td>
                                            <td class="text-right">{{ getDecimal(item.sesiones,0) }}/{{item.objetivo}}</td>
                                            <td class="text-right">{{ objetivo(item) }}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.importe) }}</td>
                                        </tr>
                                    </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                            <v-col cols="2">
                                <v-simple-table dense>
                                    <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th>F. Pago</th>
                                            <th class="text-center">
                                                Importe
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in cobros"
                                            :key="item.nombre"
                                        >
                                            <td class="font-weight-bold">{{item.nombre}}</td>
                                            <td class="text-right">{{ getCurrencyFormat(item.importe) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">TOTAL</td>
                                            <td class="text-right">{{ getCurrencyFormat(total_cobrado) }}</td>
                                        </tr>
                                    </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                            <v-col cols="2">
                                <v-simple-table dense>
                                    <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th>Medio</th>
                                            <th class="text-center">
                                                Pacientes
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in medios"
                                            :key="item.nombre"
                                        >
                                            <td class="font-weight-bold">{{item.nombre}}</td>
                                            <td class="text-right">{{ getDecimal(item.pacientes,0) }}</td>
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
                titulo:"Comparativo",
                data: {
                    area_id:1,
                    fechad: new Date().toISOString().substr(0, 8)+"01",
                    fechah: new Date().toISOString().substr(0, 10),
                },
                menud: false,
                menuh: false,
                url: "/consultas/comparativo",
                loading: false,

                areas:[],
                items:[],
                trafis:[],
                cobros:[],
                pacientes:{},
                medios:[],
                total_cobrado: 0,
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
            computedFechaD() {
                moment.locale('es');
                return this.data.fechad ? moment(this.data.fechad).format('L') : '';
            },
            computedFechaH() {
                moment.locale('es');
                return this.data.fechah ? moment(this.data.fechah).format('L') : '';
            },
        },
    	methods:{
            goBack(){
                this.$router.go(-1);
            },
            clase(item){
                return item >= 0 ? 'text-right blue--text' : 'text-right red--text';
            },
            getDecimal(value, dec=2){
                return new Intl.NumberFormat("de-DE",{style: "decimal", minimumFractionDigits:dec}).format(parseFloat(value))
            },
            getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
            },
            getPorcentaje(value){
                return new Intl.NumberFormat("de-DE",{style: "decimal",minimumFractionDigits:2}).format(parseFloat(value))+"%";
            },
            objetivo(item){
                if (item.objetivo == 0 || item.objetivo == null) return '-';

                return Math.round(item.sesiones / item.objetivo * 100)+'%';
            },
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(this.url, this.data)
                                .then(res => {

                                    console.log(res);

                                    this.items = res.data.items;
                                    this.pacientes = res.data.pacientes;
                                    this.trafis = res.data.trafis;
                                    this.cobros = res.data.cobros;
                                    this.medios = res.data.pacientes_medio;

                                    this.total_cobrado = res.data.total_cobrado;

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
                    url: this.url+"/excel",
                    method: 'POST',
                    responseType: 'blob', // important
                    data:{  area_id: this.data.area_id,
                            fechad: this.data.fechad,
                            fechah: this.data.fechah
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
