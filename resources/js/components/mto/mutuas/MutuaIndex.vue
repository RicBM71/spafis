
<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container v-if="items.length > 0">
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <!-- <v-tooltip bottom>
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
                    </v-tooltip> -->

                    <menu-ope></menu-ope>
                </v-card-title>
            </v-card>
            <v-card>
                <v-container>
                    <v-data-table
                        :headers="headers"
                        :items="items"
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
        titulo: "Mutuas/Sociedades",
        search:"",
        headers: [
            {
                text: 'CIF',
                align: 'left',
                value: 'cif',
            },
            {
                text: 'Nombre',
                align: 'left',
                value: 'nombre',
            },
            {
                text: 'Contacto',
                align: 'left',
                value: 'contacto',
            },
            {
                text: 'Teléfono',
                align: 'left',
                value: 'telefono1'
            },
            {
                text: 'Mail',
                align: 'left',
                value: 'email',
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
        url: "/mto/mutuas",
        ruta: "mutua",

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
            'isAdmin',
        ])
    },
    methods:{
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
