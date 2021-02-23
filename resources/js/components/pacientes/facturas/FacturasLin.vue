
<template>

    <div>
        <v-row>
            <v-col cols="12">
                <v-data-table
                    :options="options"
                    :headers="headers"
                    :items="facturas"
                    dense
                >
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click="goFactura(item.id)"
                        >
                            mdi-pencil
                        </v-icon>
                    </template>
                    <template v-slot:item.fecha="{ item }">
                        {{ formatDate(item.fecha)}}
                    </template>
                    <template v-slot:item.username="{ item }">
                        <span v-if="isAdmin">{{ item.username + " " + formatDateTime(item.updated_at)}}</span>
                        <span v-else>{{formatDateTime(item.updated_at)}}</span>
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
    props:['facturas'],
    data () {
      return {
        cobros:[],
        options:{
            itemsPerPage: 10,
            sortDesc: [true],
            sortBy: ['fecha','factura'],
        },
        headers: [
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha',
            },
            {
                text: 'Factura',
                align: 'left',
                value: 'ser_fac',
            },
            {
                text: 'CIF',
                align: 'left',
                value: 'cif',
            },
            {
                text: 'Razon',
                align: 'left',
                value: 'razon',
            },
            {
                text: 'Um',
                align: 'left',
                value: 'username',
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions',
            },
        ],
        factura_id: 0,
        ruta: "facturas"
      }
    },
    mounted()
    {
        console.log(this.facturas);

    },
    computed: {
        ...mapGetters([
            'isAdmin',
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
        goFactura(id){
            this.$router.push({ name: 'factura.edit', params: { id: id } })
        },
    }
  }
</script>

