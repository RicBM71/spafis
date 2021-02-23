<template>
<div>
        <loading :show_loading="loading"></loading>
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
                            @click="create"
                        >
                            <v-icon color="primary">mdi-plus</v-icon>
                        </v-btn>
                    </template>
                        <span>Nuevo</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            color="white"
                            icon
                            @click="goBack()"
                        >
                            <v-icon color="primary">mdi-arrow-left</v-icon>
                        </v-btn>
                    </template>
                        <span>Volver</span>
                </v-tooltip>
            </v-card-title>
        </v-card>

        <v-card v-if="!loading">
            <v-data-table
                :headers="headers"
                :items="items"
            >
            <template v-slot:item.fecha="{ item }">
                {{ formatDate(item.fecha)}}
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    v-if="item.fecha_baja == null"
                    small
                    @click="openDialog(item)"
                >
                    mdi-delete
                </v-icon>
                <v-icon
                    v-else
                    small
                    @click="openDialog(item)"
                >
                    restore
                </v-icon>
            </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
import moment from 'moment';
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'loading': Loading,
    },
    data () {
      return {
        titulo: "Horarios",
        headers: [
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha'
            },
            {
                text: 'Inicio',
                align: 'left',
                value: 'inim_1'
            },
            {
                text: 'Inicio',
                align: 'left',
                value: 'finm_1'
            },
            {
                text: 'Inicio',
                align: 'left',
                value: 'init_1'
            },
            {
                text: 'Fin',
                align: 'left',
                value: 'fint_1'
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions'
            }
        ],
        items:[],
        dialog: false,
        dialog_rest: false,
        item_id: 0,
        loading: true,
        facultativo:{
            nombre: '',
            apellidos:''
        }
      }
    },
    mounted()
    {


        axios.get('/mto/horarios')
            .then(res => {

                this.items = res.data.horario;
                this.facultativo = res.data.facultativo;

                this.titulo = "Horario "+this.facultativo.nombre+" "+this.facultativo.apellidos;
                //console.log(this.items);

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
        formatDate(f){
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        goBack(){
            this.$router.go(-1);
        },
        create(){
            this.$router.push({ name: 'horario.create'})
        },
        editItem(item) {
            this.$router.push({ name: 'horario.edit', params: { id: item.id } })

        },
        openDialog(item){
            this.dialog = true;
            this.item_id = item.id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/horarios/'+this.item_id,{_method: 'delete'})
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

        }
    }
  }
</script>
