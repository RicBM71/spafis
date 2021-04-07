<template>
    <v-dialog v-model="dialog_pendientes" persistent max-width="620">
        <v-card>
            <v-card-title class="headline">Facturar</v-card-title>
            <v-card-text>
                 <v-data-table

                    v-model="selected"
                    :headers="headers"
                    :items="items"
                    item-key="id"
                    show-select
                    class="elevation-1"
                >
                    <template v-slot:item.fecha="{ item }">
                    {{ formatDate(item.fecha)}}
                    </template>
                </v-data-table>

            </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text rounded @click="closeDialog()">Cancelar</v-btn>
            <v-btn color="blue darken-1" text rounded @click="goFacturar">Aceptar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import moment from 'moment'
  export default {
    props:['dialog_pendientes','reload', 'factura'],
    data () {
      return {
        ejercicio: new Date().toISOString().substr(0, 4),
        items:[],
        selected:[],

        headers: [
            { text: 'Fecha', value: 'fecha' },
            { text: 'Tratamiento', value: 'tratamiento.nombre' },
            { text: 'Importe', value: 'importe' },
        ],
      }
    },
    watch: {
        dialog_pendientes: function () {
            if (this.dialog_pendientes == true){
                this.facturables();
            }
        }
    },
    methods:{
        closeDialog (){
            this.$emit('update:dialog_pendientes', false)
        },
        facturables(){
            axios.post('/tools/citas/facturables',{
                paciente_id: this.factura.paciente_id,
                ejercicio: this.ejercicio}
            )
            .then(res => {
                this.items = res.data.items;
                
            })
            .catch(err => {
                console.log(err);
            });
        },
        goFacturar(){
            axios.post('/facturas/factlins/facturar',{
                factura: this.factura,
                citas: this.selected}
            )
            .then(res => {
                this.$emit('update:reload', this.reload + 1);
                this.$emit('update:dialog_pendientes', false);

            })
            .catch(err => {
                console.log(err);
            });
        },
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');

            return moment(f).format('DD/MM/YYYY');

        },
      }
  }
  </script>
