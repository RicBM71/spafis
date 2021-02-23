
<template>

    <div>
        <v-row>
            <v-col cols="12">
                <v-data-table
                    :options="options"
                    :headers="headers"
                    :items="citas"
                    show-expand
                    dense
                >
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            color="green"
                            v-if="item.estado_id == 3 && isSupervisor"
                            small
                            @click="showCobros(item.id)"
                        >
                            mdi-currency-eur
                        </v-icon>
                    </template>
                    <template v-slot:item.estado="{ item }">
                        <span :class="item.estado.color">{{ item.estado.nombre}}</span>
                    </template>
                    <template v-slot:item.importe="{ item }">
                        <span v-if="isSupervisor">{{ getCurrencyFormat(item.importe)}}</span>
                    </template>
                    <template v-slot:item.factura_id="{ item }">
                        <span v-if="isAdmin && item.factura_id > 0">
                            <v-btn rounded text color="primary" @click="goFactura(item.factura_id)" small>
                                <v-icon small>mdi-flare</v-icon>
                            </v-btn>
                        </span>
                    </template>
                    <template v-slot:item.fecha="{ item }">
                        {{ formatDate(item.fecha)}}
                    </template>
                    <template v-slot:item.bono="{ item }">
                        <span v-if="isSupervisor">{{item.bono}}</span>
                        <span v-else></span>
                    </template>
                    <template v-slot:item.username="{ item }">
                        <span v-if="isSupervisor">{{ item.username + " " + formatDateTime(item.updated_at)}}</span>
                        <span v-else>{{formatDateTime(item.updated_at)}}</span>
                    </template>
                    <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length">{{ item.notas }}</td>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-row v-show="cita_id > 0">
            <v-col cols="12">
                <cobros-lin :cita_id="cita_id"></cobros-lin>
            </v-col>
        </v-row>
    </div>

</template>
<script>
import moment from 'moment'
import CobrosLin from './CobrosLin'
import {mapGetters} from 'vuex'
  export default {
    $_veeValidate: {
        validator: 'new'
    },
    props:{
        citas: Array
    },
    components: {
        'cobros-lin': CobrosLin,
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
            },
            {
                text: 'Hora',
                align: 'left',
                value: 'hora',
            },
            {
                text: 'Fisio',
                align: 'left',
                value: 'facultativo.nombre',
            },
            {
                text: 'Tratamiento',
                align: 'left',
                value: 'tratamiento.nombre',
            },
            {
                text: 'Importe',
                align: 'left',
                value: 'importe',
            },
            {
                text: 'Bono',
                align: 'left',
                value: 'bono',
            },
            {
                text: 'Factura',
                align: 'left',
                value: 'factura_id',
            },
            {
                text: 'Estado',
                align: 'left',
                value: 'estado',
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
            { text: '', value: 'data-table-expand' },
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
            'isSupervisor',
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
        getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        create(){
            this.$router.push({ name: this.ruta+'.create'})
        },
        goFactura(id){
            this.$router.push({ name: 'factura.edit', params: { id: id } })
        },
        showCobros(cita_id) {

            this.cita_id = cita_id;

        },
    }
  }
</script>

