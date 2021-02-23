<template>
    <div>
        <v-data-table
            :options="options"
            :headers="headers"
            :items="items"
            hide-default-footer
            dense
            :loading="loading"
        >

            <template v-slot:item.estado="{ item }">
                <span :class="item.estado.color">{{ item.estado.nombre}}</span>
            </template>
            <template v-slot:item.fecha="{ item }">
                {{ formatDate(item.fecha)}}
            </template>
            <template v-slot:item.username="{ item }">
                {{ item.username + " " + formatDateTime(item.updated_at)}}
            </template>
        </v-data-table>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    props:['cita_id'],
    data () {
      return {

        items:[],
        loading: false,

        options:{
            itemsPerPage: 10,
            sortDesc: [true],
            sortBy: ['fecha','hora'],
        },

          headers: [
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha',
            },
            {
                text: 'F. Pago',
                align: 'left',
                value: 'fpago.nombre',
            },
            {
                text: 'Importe',
                align: 'left',
                value: 'importe',
            },
            {
                text: 'Autorización',
                align: 'left',
                value: 'autorizacion',
            },
            {
                text: 'Usuario',
                align: 'left',
                value: 'username',
            }
          ]
      }
    },
    mounted(){

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
    },
    watch: {
        cita_id: function () {

            this.loading = true;
            axios.get('/citas/cobros/'+this.cita_id+'/show')
                .then(res => {
                    this.items = res.data.cobros;
                    this.loading = false;
                })
                .catch(err =>{
                })
                .finally(()=>{
                });

        },
    }



}
</script>

