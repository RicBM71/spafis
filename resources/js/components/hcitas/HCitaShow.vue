<template>
<div>
        <loading :show_loading="loading"></loading>
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
                <template v-slot:item.username="{ item }">
                    {{ item.username + " " + formatDate(item.updated_at)}}
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{ formatDate(item.created_at)}}
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Histórico Citas",
        search:"",
        headers: [
            {
                text: 'A',
                align: 'left',
                value: 'accion'
            },
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha'
            },
            {
                text: 'Hora',
                align: 'left',
                value: 'hora'
            },
            {
                text: 'Paciente',
                align: 'left',
                value: 'paciente.nom_ape'
            },
            {
                text: 'Tra',
                align: 'left',
                value: 'tratamiento.nombre'
            },
            {
                text: 'F',
                align: 'left',
                value: 'facultativo.nombre'
            },
            {
                text: 'E',
                align: 'left',
                value: 'estado.nombre'
            },
            {
                text: 'I',
                align: 'left',
                value: 'importe'
            },
            {
                text: 'B',
                align: 'left',
                value: 'bono'
            },
            {
                text: 'U',
                align: 'left',
                value: 'username'
            },
            {
                text: 'C',
                align: 'left',
                value: 'created_at'
            },
            {
                text: 'N',
                align: 'left',
                value: 'notas'
            },
        ],
        items:[],
        loading: true

      }
    },
    mounted()
    {
        // if (!this.isRoot)
        //     this.$toast.error('No tiene permiso!');

        var id = this.$route.params.id;

        axios.get('/citas/hcitas/'+id)
            .then(res => {
                console.log(res);
                this.items = res.data.hcitas;

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
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY H:mm:ss');
        },
    }
  }
</script>
