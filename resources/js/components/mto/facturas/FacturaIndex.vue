<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container v-if="items.length > 0">
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <menu-ope :factura="factura"></menu-ope>
                </v-card-title>
            </v-card>
            <v-card>
                <v-container>
                    <v-data-table
                        :headers="headers"
                        :items="items"
                        :options="options"
                    >
                    <template v-slot:item.fecha="{ item }">
                        {{ formatDate(item.fecha)}}
                    </template>
                    <template v-slot:item.username="{ item }">
                        {{ item.username + " " + formatDateTime(item.updated_at)}}
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="openDialog(item)"
                        >
                            mdi-delete
                        </v-icon>
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
import FiltroFac from './FiltroFac'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading,
        'filtro-fac': FiltroFac
    },
    data () {
      return {
        titulo: "Facturas",
        factura:{
            id: 0,
            cliente_id: 0
        },
        expand: false,
        search:"",
        options:{
            page: 1,
            itemsPerPage: 10,
            sortDesc: [true],
            sortBy: ['fecha'],
        },
        headers: [
            {
                text: 'Factura',
                align: 'left',
                value: 'ser_fac',
                width: '8%'
            },
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha',
                width: '8%'
            },
            {
                text: 'NIF/NIE',
                align: 'left',
                value: 'cif'
            },
            {
                text: 'Nombre y Apellidos',
                align: 'left',
                value: 'paciente.nom_ape',
                width: '30%'
            },
            {
                text: 'Notas',
                align: 'left',
                value: 'notas',
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions'
            }
        ],
        items:[],
        status: false,
		registros: false,
        dialog: false,
        item_destroy: {},
        show_loading: true,
        url: "/facturas/facturas",
        ruta: "factura",

        filtro: false,

      }
    },
    mounted()
    {

        axios.get(this.url)
            .then(res => {

                this.items = res.data;
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
        ])
    },
    methods:{
        getDni(dni){
            return this.isAdmin ? dni : "******"+dni.substr(-4);
        },
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        create(){
            this.$router.push({ name: this.ruta+'.create'})
        },
        editItem (item) {
            this.$router.push({ name: this.ruta+'.edit', params: { id: item.id } })
        },
        openDialog (item){
            this.dialog = true;
            this.item_destroy = item;
        },
        destroyReg () {
            this.dialog = false;

            axios.post(this.url+'/'+this.item_destroy.id,{_method: 'delete'})
                .then(res => {

                    const index = this.items.indexOf(this.item_destroy)
                    this.items.splice(index, 1)

                    this.$toast.success('Registro eliminado!');

                })
            .catch(err => {
                this.status = true;

                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
    }
  }
</script>
