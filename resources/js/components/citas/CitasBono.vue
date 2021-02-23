<template>
    <div class="text-center">
        <v-dialog
            v-model="dialog_bono"
            width="800"
        >

        <v-card>
            <v-card-title class="mb-4 headline blue lighten-2">
                Sesiones Bono: {{ numero_bono }} <span v-if="bono.sesiones > 0">- ({{ bono.sesiones }} sesiones)</span>
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
 props:['numero_bono', 'dialog_bono'],
    data () {
        return {
            bono:{sesiones: 0},
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
                { text: 'Paciente', value: 'paciente.nom_ape' },
                { text: 'Bono', value: 'bono' },
                { text: 'Importe', value: 'importe', align: 'end', },
            ],
        }
    },
    watch: {
        dialog_bono: function () {

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

            if (this.dialog_bono){

                this.loading = true;

                if (this.numero_bono > 0)
                    axios.get('/citas/bono/'+this.numero_bono)
                        .then(res => {
                            this.bono = res.data.bono;
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

            return moment(f).format('DD/MM/YYYY H:mm');

        },
        getCurrencyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
            },
        close(){
            this.$emit('update:dialog_bono', false);
        }
    }
}
</script>

