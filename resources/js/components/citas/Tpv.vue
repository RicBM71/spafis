<template>
    <div>
            <v-row justify="center">
                <v-dialog
                    v-model="dialog_tpv"
                    persistent
                    max-width="1024px"
                    transition="dialog-bottom-transition"
                    >
                <v-card>
                    <v-toolbar
                        dark
                        color="primary"
                    >
                        <v-toolbar-title>TPV Productos</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                            icon
                            dark
                            @click="cancelar"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                    <v-row>
                        <v-col cols="6">
                            <v-card class="pa-3">
                                <v-row>
                                    <v-col
                                        cols="10"
                                        offset-md="1"
                                    >
                                        <v-text-field
                                            hide-details="true"
                                            outlined
                                            dense
                                            :value="cita.paciente.nom_ape"
                                            label="Paciente"
                                            background-color="blue lighten-5"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        cols="12"
                                    >
                                        <v-simple-table dense>
                                            <template v-slot:default>
                                                <thead>
                                                    <tr>
                                                    <th class="text-left">
                                                        Fecha
                                                    </th>
                                                    <th class="text-left">
                                                        Producto
                                                    </th>
                                                    <th class="text-right">
                                                        Importe
                                                    </th>
                                                    <th>
                                                        A
                                                    </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                    v-for="item in items"
                                                    :key="item.cita_id"
                                                    >
                                                    <td>{{ getDate(item.fecha) }}</td>
                                                    <td>{{ item.tratamiento.nombre }}</td>
                                                    <td class="text-right">{{ getCurrencyFormat(item.importe) }}</td>
                                                    <td><v-icon small
                                                        color="red"
                                                        @click="destroy(item)">
                                                        mdi-trash-can-outline</v-icon></td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </v-simple-table>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                        <v-col cols="6">
                            <v-card class="pa-3">
                                <v-row>
                                    <v-col
                                        cols="5"
                                        offset-md="1"
                                    >
                                        <v-menu
                                            v-model="menu1"
                                            :close-on-content-click="false"
                                            :nudge-top="40"
                                            transition="scale-transition"
                                            offset-x
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on }">
                                            <v-text-field
                                                dense
                                                v-model="computedFecha"
                                                label="Fecha Cobro"
                                                prepend-icon="mdi-calendar"
                                                readonly
                                                data-vv-name="fecha"
                                                :error-messages="errors.collect('fecha')"
                                                data-vv-as="Fecha"
                                                v-on="on"
                                            ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="item.fecha"
                                                no-title
                                                locale="es"
                                                first-day-of-week=1
                                                @input="menu1 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col
                                        cols="1"
                                    >

                                    </v-col>
                                    <v-col
                                        cols="4"
                                    >
                                        <v-text-field
                                            dense
                                            outlined
                                            v-model="item.importe"
                                            v-validate="'required|decimal:2|min:1'"
                                            :error-messages="errors.collect('importe')"
                                            label="Importe"
                                            data-vv-name="importe"
                                            data-vv-as="importe"
                                            class="inputPrice"
                                            type="number"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        offset-md="1"
                                        cols="8"
                                    >
                                        <v-select
                                            v-model="item.tratamiento_id"
                                            :error-messages="errors.collect('tratamiento_id')"
                                            label="Tratamiento"
                                            data-vv-name="tratamiento_id"
                                            data-vv-as="tratamiento_id"
                                            :items="tratamientos"
                                            @change="setPVP"
                                        ></v-select>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        offset-md="4"
                                        cols="3"
                                    >
                                        <v-btn @click="submit" :loading="isLoading" outlined small block rounded color="green lighten-1">Añadir Producto</v-btn>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </v-row>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';
import moment from 'moment'
export default {
    props:['cita','show_loading','dialog_tpv','reload_id'],
    components: {
    },
    data () {
        return {

            loading: false,
            menu1:false,


            tratamientos:[],
            items:[],


            isLoading: false,

            item:{
                area_id:9999,
                fecha: new Date().toISOString().substr(0, 10),
                hora:'00:00:00',
                paciente_id:'',
                tratamiento_id:'',
                facultativo_id: 3,
                importe:0,
                importe_ponderado:0,
                estado_id:1
            }

        }
    },
    mounted(){

    },

    computed: {
         ...mapGetters([
            'isAdmin'
        ]),
        computedFecha() {
            moment.locale('es');
            return this.item.fecha ? moment(this.item.fecha).format('DD/MM/YYYY') : '';
        },

    },
    watch: {
        dialog_tpv: function () {
            if (this.dialog_tpv == true){
                this.reload();
            }
        },
    },
    methods:{
        reload(){
            this.items=[];
            this.isLoading=true;
            axios.post('/tools/citas/vendibles',{paciente_id:this.cita.paciente_id})
                .then(res => {
                    this.tratamientos = res.data.productos;

                    if (res.data.items.length > 0)
                        this.items = res.data.items;
                    this.item.tratamiento_id = this.tratamientos[0].value;
                    this.item.importe = this.tratamientos[0].importe;
                    this.item.iva = this.tratamientos[0].iva;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                })
                .finally(()=>{
                    this.isLoading = false;
                });

        },
        destroy(item){

           if (confirm("Borrar registro?")){

                axios.post('/citas/citas/'+item.id,{_method: 'delete'})
                    .then(response => {
                        const editedIndex = this.items.indexOf(item)
                        const editedItem = Object.assign({}, item)
                        this.items.splice(editedIndex, 1)
                })
                .catch(err => {
                        console.log(err);
                });
            }
        },
        setPVP(i){

            const idx = this.tratamientos.map(x => x.value).indexOf(i);

            this.item.importe = this.tratamientos[idx].importe;
            this.item.iva = this.tratamientos[idx].iva;

            console.log(idx);

        },
        getDecimalFormat(value){
            return value;
                return new Intl.NumberFormat("de-DE",{style: "decimal",minimumFractionDigits:2}).format(parseFloat(value))
        },
        getDate(f) {
            moment.locale('es');
            return moment(f).format('D/M/YYYY');
        },
        submit(){

                this.item.paciente_id = this.cita.paciente_id;
                this.item.importe_ponderado = this.item.importe;

                this.$validator.validateAll().then((result) => {
                    this.loading = true;
                    if (result){
                        axios.post('/citas/addpro', this.item)
                            .then(res => {
                                 this.items = res.data.items;
                            })
                            .catch(err => {
                                this.$toast.error(err.response.data.message);
                            })
                            .finally(()=>{
                                this.loading = false;
                            });
                    }
                });

        },
        getCurrencyFormat(value){
            return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        cancelar(){
            var id = this.reload_id + 1;
            this.$emit('update:reload_id', id);
            this.$emit('update:dialog_tpv', false);
        },
    }
}
</script>



<style scoped>

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
