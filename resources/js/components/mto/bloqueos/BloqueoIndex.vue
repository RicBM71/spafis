<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container v-if="items.length > 0">
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>

                    <menu-ope></menu-ope>
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
    },
    data () {
      return {
        expand: false,
        titulo: "Bloqueos",
        search:"",
        options:{
            page: 1,
            itemsPerPage: 10,
            sortDesc: [true],
            sortBy: ['fecha'],
        },
        headers: [
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha',
            },
            {
                text: 'Facultativo',
                align: 'left',
                value: 'facultativo.alias',
            },
            {
                text: 'Ini',
                align: 'left',
                value: 'start',
            },
            {
                text: 'Fin',
                align: 'left',
                value: 'end',
            },
            {
                text: 'Motivo',
                align: 'left',
                value: 'motivo',
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
        items:[],

        dialog: false,
        registro_id: 0,
        show_loading: true,
        url: "/mto/bloqueos",
        ruta: "bloqueo",

      }
    },
    mounted()
    {


        axios.get(this.url)
            .then(res => {

                this.items = res.data;

            })
            .catch(err =>{

                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
            .finally(()=>{
                this.show_loading = false;
            });
    },
    computed: {
        ...mapGetters([
            'isSupervisor',
        ])
    },
    methods:{
        formatDate(f){

            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        formatDateTime(f){

            if (f == null) return null;
            moment.locale('es');

            return moment(f).format('DD/MM/YYYY H:mm:ss');

        },
        create(){
            this.$router.push({ name: this.ruta+'.create'})
        },
        editItem (id) {
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
                    this.items = res.data;

                }
                })
            .catch(err => {

                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
    }
  }
</script>
