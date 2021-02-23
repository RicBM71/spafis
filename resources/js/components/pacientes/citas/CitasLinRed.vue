
<template>

    <div>
        <v-row>
            <v-col cols="12">
                <v-data-table
                    :options="options"
                    :headers="headers"
                    :items="citas"
                    dense
                >

                    <template v-slot:item.fecha="{ item }">
                        {{ formatDate(item.fecha)}}
                    </template>

                </v-data-table>
            </v-col>
        </v-row>
    </div>

</template>
<script>
import moment from 'moment'
import {mapGetters} from 'vuex'
  export default {
    $_veeValidate: {
        validator: 'new'
    },
    props:['citas'],
    components: {
    },
    data () {
      return {
        cobros:[],
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
                width: '10%'
            },
            {
                text: 'Hora',
                align: 'left',
                value: 'hora',
                width: '10%'
            },
            {
                text: 'Tratamiento',
                align: 'left',
                value: 'tratamiento.nombre',
                width: '20%'
            },
            {
                text: 'Notas',
                align: 'left',
                value: 'notas',
            },
        ],
        cita_id: 0,
        ruta: "citas"
      }
    },
    mounted()
    {


    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'hasPrecios'
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
    }
  }
</script>

