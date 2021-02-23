<template>
    <div>
        <loading :show_loading="show_loading"></loading>
		<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card v-if="!show_loading">
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

            <v-data-table
                :search="search"
                :headers="headers"
                :items="empresas"
            >
                <template v-slot:item.roles="{ item }">
                    {{ extrae(item.roles)}}
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
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
                <v-data-footer>
                    <template v-slot:page-text="pagetext">
                    {{pagetext}}
                    </template>
                </v-data-footer>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex';
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Empresas",
        search:"",
        headers: [
          {
            text: 'ID',
            align: 'center',
            value: 'id'
          },
          {
            text: 'Nombre',
            align: 'left',
            value: 'titulo'
          },
          {
            text: 'CIF',
            align: 'left',
            value: 'cif'
          },
          {
            text: 'Contacto',
            align: 'left',
            value: 'contacto'
          },
          {
            text: 'Teléfono',
            align: 'Left',
            value: 'telefono1'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: 'actions',
            sortable: false
          }
        ],

        empresas:[],
        status: false,
        dialog: false,
        empresa_id: 0,
        titulo:"Empresas",
        show_loading: true
      }
    },
    mounted()
    {

        axios.get('/admin/empresas')
            .then(res => {
                this.empresas = res.data;
                this.show_loading= false;
            })
            .catch(err =>{

                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'empresaActiva',
        ]),
    },
    methods:{
        create(){
            this.$router.push({ name: 'empresa.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'empresa.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.empresa_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/empresas/'+this.empresa_id,{_method: 'delete'})
                .then(response => {
                    this.empresas = response.data;
                    this.$toast.success('Empresa eliminada!');

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
<style scoped>
  .tachado{text-decoration:line-through;}
</style>
