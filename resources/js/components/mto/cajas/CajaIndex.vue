
<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container v-if="registros">
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                color="white"
                                icon
                                @click="filtro = !filtro"
                            >
                                <v-icon color="primary">mdi-filter</v-icon>
                            </v-btn>
                        </template>
                        <span>Filtros</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-show="items.length > 0 && isGestor"
                                v-on="on"
                                color="white"
                                icon
                                @click="goExcel()"
                            >
                                <v-avatar size="32px">
                                    <img class="img-fluid" src="/assets/excel.png">
                                </v-avatar>
                            </v-btn>
                        </template>
                        <span>Exportar a Excel</span>
                    </v-tooltip>

                    <menu-ope></menu-ope>
                </v-card-title>
            </v-card>
            <v-card v-show="filtro">
                <filtro-caja :filtro.sync="filtro"
                     :reg.sync="items"
                     :saldo.sync="saldo"
                     :fecha_saldo.sync="fecha_saldo"
                ></filtro-caja>
            </v-card>
            <v-card>
                <v-card-title>
                    <v-col
                        cols="12"
                        md="2"

                    >
                        Debe: {{getCurrencyFormat(total_debe)}}
                    </v-col>
                    <v-col
                        cols="12"
                        md="2"

                    >
                        Haber: {{getCurrencyFormat(total_haber)}}
                    </v-col>
                    <v-col
                        cols="12"
                        md="2"

                    >
                        Saldo: {{getCurrencyFormat(saldo)}}
                    </v-col>
                    <v-spacer></v-spacer>
                    <v-col
                        cols="12"
                        md="5"
                    >
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Buscar"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-col>
                </v-card-title>
                <v-container>
                    <v-data-table
                        :search="search"
                        :headers="headers"
                        :options="options"
                        :items="items"
                        dense
                    >
                        <template v-slot:body="{ items }">
                            <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td :class="colorear(item)">{{ formatDate(item.fecha) }}</td>
                                <td :class="colorear(item)">{{ item.dh }}</td>
                                <td :class="colorear(item)">{{ item.nombre }}</td>
                                <td v-if="item.dh=='D'" :class="colorear(item, true)">{{ getCurrencyFormat(item.importe)}}</td>
                                <td else></td>
                                <td v-if="item.dh=='H'" :class="colorear(item, true)">{{ getCurrencyFormat(item.importe)}}</td>

                                <td :class="colorear(item)">{{ item.username+" "+formatDateUpdated(item.updated_at) }}</td>

                                <td>
                                    <v-icon
                                        v-if="item.manual != 'N'"
                                        small
                                        @click="editItem(item.id)"
                                    >
                                    mdi-pencil
                                    </v-icon>
                                    <v-icon
                                        v-if="item.manual != 'N'"
                                        small
                                        @click="openDialog(item.id)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </v-data-table>
                </v-container>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import moment from 'moment'
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import FiltroCaja from './FiltroCaja'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    $_veeValidate: {
        validator: 'new'
    },
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading,
        'filtro-caja': FiltroCaja
    },
    data () {
      return {
        expand: false,
        titulo: "Apuntes de caja",
        paginaActual:{},
        options:{
            model: "caja",
            page: 1,
            itemsPerPage: 10,
            sortDesc: [true],
            sortBy: ['id'],
        },
        search:"",
        headers: [
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha',
                width: '2%'
            },
            {
                text: 'A',
                align: 'left',
                value: 'dh',
                width: '3%'
            },
            {
                text: 'Concepto',
                align: 'left',
                value: 'nombre'
            },
            {
                text: 'Debe',
                align: 'left',
                value: 'importe',
                width: "8%"
            },
            {
                text: 'Haber',
                align: 'left',
                value: 'importe',
                width: "8%"
            },
            {
                text: 'Usuario',
                align: 'left',
                value: 'id',
                width: '15%'
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'id',
                width: '1%',
                sortable: false,
            }
        ],
        items:[],
        status: false,
		registros: false,
        dialog: false,
        registro_id: 0,
        show_loading: true,
        url: "/mto/cajas",
        ruta: "caja",

        ejercicio: new Date().toISOString().substr(0, 4),
        filtro: false,
        saldo: 0,
        fecha_saldo: "",
        total_debe : 0,
        total_haber: 0

      }
    },
    mounted()
    {

        if (this.getPagination.model == this.options.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get(this.url)
            .then(res => {

                this.items = res.data.caja;
                this.saldo = res.data.saldo;
                this.fecha_saldo = res.data.fecha_saldo;

                this.registros = true;
                this.show_loading = false;
            })
            .catch(err =>{

                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'isGestor',
            'isSupervisor',
            'userName',
            'getPagination'
        ])
    },
     watch: {
        items () {
            this.total_debe = this.total_haber = 0;
            this.items.forEach(element => {
                if (element.dh == 'D')
                    this.total_debe +=  Number.parseFloat(element.importe);
                else
                    this.total_haber += Number.parseFloat(element.importe);
            });

        }
    },
    methods:{
        ...mapActions([
            'setPagination',
            'unsetPagination'
        ]),
        getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        colorear(item, ali=false){
            var a = ali ? 'text-xs-right' : '';

            if (item.manual == 'C')
                return a+' brown--text darken-1 font-weight-bold';

            if (item.apunte_id > 0){
                if (item.apunte.color != null)
                    return a+' '+item.apunte.color;
                else
                    return item.dh == 'D' ? a+' red--text accent-4' : a+' indigo--text accent-4';
            }
            else
                return item.dh == 'D' ? a+' red--text accent-4' : a+' indigo--text accent-4';
        },
        formatDate(f){

            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        formatDateUpdated(f){


            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY HH:mm:ss');
        },
        puedeEditar(item){

            if (item.manual == 'R' && this.isAdmin)
                return true;

            if (item.manual != 'S') return false;


            if (this.isAdmin || this.isAdmin)
                return true;

            return (this.userName == item.username && this.formatDate(item.created_at) == this.formatDate(new Date()));

        },
        puedeBorrar(item){
            if (item.manual == 'C'){ // es apunte de cierre
                return (this.isAdmin)
            }else{
                return (item.manual == "S" || item.manual == "R") && this.isAdmin;
            }

        },
        updateEventPagina(obj){

            this.paginaActual = obj;

        },
        updatePosPagina(pag){

            this.pagination.page = pag.page;
            this.pagination.descending = pag.descending;
            this.pagination.rowsPerPage= pag.rowsPerPage;
            this.pagination.sortBy = pag.sortBy;

        },
        create(){
            this.$router.push({ name: this.ruta+'.create'})
        },
        editItem (id) {
            this.setPagination(this.paginaActual);
            this.$router.push({ name: this.ruta+'.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.registro_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post(this.url+'/'+this.registro_id,{_method: 'delete'})
                .then(res => {

                if (res.status == 200){
                    this.$toast.success('Registro eliminado!');
                    this.items = res.data.caja;
                    this.saldo = res.data.saldo;
                    this.fecha_saldo = res.data.fecha_saldo;
                }
                })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        goExcel(){
            this.show_loading = true;
            axios({
                        url: this.url+"/excel",
                        method: 'POST',
                        responseType: 'blob',
                        data:{ data: this.items }
                        })
                    .then(response => {

                        let blob = new Blob([response.data])
                        let link = document.createElement('a')
                        link.href = window.URL.createObjectURL(blob)

                        link.download = "Caja."+new Date().getFullYear()+(new Date().getMonth()+1)+(new Date().getDate())+'.xlsx';

                        document.body.appendChild(link);
                        link.click()
                        document.body.removeChild(link);

                        this.$toast.success('Download OK! '+link.download);

                        this.show_loading = false;

                    })
                    .catch(err => {
                        if (err.response.status == 403)
                            this.$toast.error("No autorizado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.show_loading = false;
                    });
        },
        filtrar(){

            this.$validator.validateAll().then((result) => {
                    if (result){
                        this.show_loading = true;
                        axios.post(this.url+'/filtrar',
                                {
                                    ejercicio: this.ejercicio,
                                }
                            )
                            .then(res => {

                                this.pagination.page = 1;
                                this.filtro = false;

                                this.items = res.data;
                                this.show_loading = false;

                            })
                            .catch(err => {

                                this.show_loading = false;
                                if (err.response.status != 419)
                                    this.$toast.error(err.response.data.message);
                                else
                                    this.$toast.error("Sesión expirada!");

                            });
                    }
            });

        },

    }
  }
</script>

<style scoped>
  .tachado{text-decoration:line-through;}
</style>
