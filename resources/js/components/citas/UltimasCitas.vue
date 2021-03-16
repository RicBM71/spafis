<template>
    <div class="text-center">
        <v-dialog
            v-model="dialog_ultimas"
            width="800"
        >

        <v-card>
            <v-card-title class="mb-4 headline blue lighten-2">
                Últimas sesiones: {{ nombre }}
            </v-card-title>

            <v-card-text>

                <v-data-table
                    :loading="loading"
                    :headers="headers"
                    :items="items"
                    :items-per-page="10"
                    hide-default-footer
                    dense
                    class="elevation-1"
                >
                    <template v-slot:item.fecha="{ item }">
                        {{ formatDate(item)}}
                    </template>
                    <template v-slot:item.importe="{ item }">
                        {{ getCurrencyFormat(item.importe)}}
                    </template>
                    <template v-slot:item.estado.nombre="{ item }">
                        <span :class="item.estado.color">{{ item.estado.nombre}}</span>
                    </template>
                </v-data-table>

            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                rounded
                @click="close"
            >
                Cerrar
            </v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
    </div>

</template>

<script>
import moment from 'moment'
import {mapGetters} from 'vuex';
export default {
 props:['paciente_id', 'dialog_ultimas'],
    data () {
        return {
            nombre: null,
            loading: false,
            items: [],
            headers: [
                {
                    text: 'Fecha',
                    align: 'start',
                    sortable: false,
                    value: 'fecha',
                },
                { text: 'Fisio', value: 'facultativo.alias' },
                { text: 'Estado', value: 'estado.nombre' },
                { text: 'Tratamiento', value: 'tratamiento.nombre' },
                { text: 'Bono', value: 'bono' },
                { text: 'Importe', value: 'importe', align: 'end', },
            ],
        }
    },
    watch: {
        dialog_ultimas: function () {

            this.items = [];

            if (!this.isSupervisor)
                this.headers = [
                    {
                        text: 'Fecha',
                        align: 'start',
                        sortable: false,
                        value: 'fecha',
                    },
                    { text: 'Estado', value: 'estado.nombre' },
                    { text: 'Tratamiento', value: 'tratamiento.nombre' },

                ];

            if (this.dialog_ultimas){

                this.loading = true;

                axios.get('/citas/ultimas/'+this.paciente_id)
                    .then(res => {
                        this.nombre = res.data.paciente.nom_ape;
                        this.items = res.data.citas;

                        this.loading = false;
                    })
                    .catch(err =>{
                    })
                    .finally(()=>{
                    });
            }


        },
    },
    computed: {
        ...mapGetters([
            'isSupervisor',
        ]),
     },
    methods:{
        formatDate(item){
            moment.locale('es');

            var f = item.fecha + " " + item.hora;

            return moment(f).format('DD/MM/YYYY H:mm ddd');

        },
        getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
            },
        close(){
            this.$emit('update:dialog_ultimas', false);
        }
    }
}
</script>

