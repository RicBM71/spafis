<template>
<div>
        <loading :show_loading="loading"></loading>
		<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card v-if="!loading">

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
        </v-card>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Areas",
        search:"",
        headers: [
            {
                text: 'Nombre',
                align: 'left',
                value: 'nombre'
            },
            {
                text: 'Inicio',
                align: 'left',
                value: 'hora1'
            },
            {
                text: 'Fin',
                align: 'left',
                value: 'hora2'
            },
            {
                text: 'Freq',
                align: 'center',
                value: 'frecuencia'
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions'
            }
        ],
        items:[],
        dialog: false,
        item_id: 0,
        loading: true

      }
    },
    mounted()
    {


        axios.get('/mto/areas')
            .then(res => {

                this.items = res.data;

            })
            .catch(err =>{
               ;
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
            .finally(()=>{
                this.loading = false;
            });
    },
    computed: {
        ...mapGetters([
            'isRoot',
        ])
    },
    methods:{
        create(){
            this.$router.push({ name: 'area.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'area.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.item_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/areas/'+this.item_id,{_method: 'delete'})
                .then(res => {

                if (res.status == 200){
                    this.$toast.success('Area eliminado!');
                    this.items = res.data;
                }
                })
            .catch(err => {
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        }
    }
  }
</script>
