
<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container>
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom v-if="isSupervisor">
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                color="white"
                                icon
                                @click="show_filtro = !show_filtro"
                            >
                                <v-icon color="primary">mdi-filter</v-icon>
                            </v-btn>
                        </template>
                        <span>Filtrar</span>
                    </v-tooltip>
                    <v-tooltip bottom v-if="isAdmin">
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-show="items.length > 0"
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
            <v-card v-show="show_filtro">
                <v-card-text>
                    <filtro :show_filtro.sync="show_filtro" :items.sync="items" :filtro_query.sync="filtro_query"></filtro>
                </v-card-text>
            </v-card>
            <v-card>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                        :search="search"
                        :headers="headers"
                        :items="items"
                        :options.sync="options"
                    >
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click="editItem(item.id)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="openDialog(item.id)"
                        >
                            mdi-delete
                        </v-icon>
                        <v-icon
                            v-show="item.espera == true"
                            small
                            @click="setEspera(item)"
                        >
                            mdi-marker-cancel
                        </v-icon>
                    </template>
                    <template v-slot:item.id="{ item }">
                        <v-avatar size="32px" v-if="item.foto!=false">
                            <img class="img-fluid" :src="item.foto">
                        </v-avatar>
                    </template>
                    <template v-slot:item.username="{ item }">
                        {{ item.username + " " + formatDate(item.updated_at)}}
                    </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import moment from 'moment'
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import Filtro  from './PacienteFiltro'
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
        'filtro': Filtro,
    },
    data () {
      return {
        search: null,
        titulo: "Pacientes",
        search:"",
        headers: [
            {
                text: 'F',
                align: 'left',
                value: 'id',
            },
            {
                text: 'Nombre',
                align: 'left',
                value: 'nom_ape',
            },
            {
                text: 'Edad',
                align: 'left',
                value: 'edad',
            },
            {
                text: 'Direccion',
                align: 'left',
                value: 'direccion',
            },
            {
                text: 'Teléfono',
                align: 'left',
                value: 'telefono1'
            },
            {
                text: 'Móvil',
                align: 'left',
                value: 'telefonom'
            },
            {
                text: 'Mail',
                align: 'left',
                value: 'email',
            },
            {
                text: 'Observaciones',
                align: 'left',
                value: 'notas1',
            },
            {
                text: 'Usuario',
                align: 'left',
                value: 'username',
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions',
            }
        ],

        options: {
            page: 1,
            itemsPerPage: 10,
            sortBy: ['id'],
            sortDesc: [true],
        },

        items:[],

        dialog: false,
        registro_id: 0,
        show_loading: true,
        show_filtro: false,
        url: "/mto/pacientes",
        ruta: "paciente",
        filtro_query:false

      }
    },
    mounted()
    {

        if (this.getPagination.model == this.ruta && this.getPagination.filtro !== false){
            axios.post('/mto/pacientes/filtrar', this.getPagination.filtro)
                .then(res => {
                    this.items = res.data;
                    this.filtro_query = this.getPagination.filtro;
                    this.options = this.getPagination.options;
                    this.show_loading = false;
                })
                .catch(err =>{
                    console.log(err);
                    this.show_loading = false;
                })
        }
        else{
            axios.get(this.url)
                .then(res => {
                    this.options = this.getPagination.options;
                    this.items = res.data;
                })
                .catch(err =>{
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'dash' })
                })
                .finally(()=>{
                    this.show_loading = false;
                });
        }


    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'isSupervisor',
            'hasContact',
            'getPagination'
        ])
    },
    methods:{
        ...mapActions([
            'setPagination',
            'unsetPagination'
        ]),
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY H:mm:ss');
        },
        create(){
            this.$router.push({ name: this.ruta+'.create'})
        },
        editItem (id) {

            this.setPagination({
                    'model'   : this.ruta,
                    'options' : this.options,
                    'filtro'  : this.filtro_query}
                );
            this.$router.push({ name: this.ruta+'.edit', params: { id: id } })
        },
        setEspera(item) {

            item.espera = false;

            axios.put(this.url+"/"+item.id, item)
                .then(res => {
                    //item = res.data.paciente;
                })
                .catch(err => {
                })
                .finally(()=> {
                });

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
                    this.items = res.data;

                }
                })
            .catch(err => {

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

    }
  }
</script>
