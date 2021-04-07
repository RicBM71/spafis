<template>
<div>
    <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
    <v-row>
        <v-col cols="12" md="10"  offset-md="1">
            <v-data-table
                :headers="headers"
                :items="items"
                dense
            >
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        color="primary"
                        small
                        @click="editItem(item)"
                    >
                        mdi-pencil
                    </v-icon>
                     <v-icon
                        color="red darken-4"
                        small
                        @click="openDialog(item.id)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
                <template v-slot:item.iva="{ item }">
                    {{getDecimalFormat(item.iva)}}
                </template>
                <template v-slot:item.importe="{ item }">
                    {{getDecimalFormat(item.importe)}}
                </template>
                <template v-slot:body.append>
                        <tr>
                            <td class=""></td>
                            <td class="font-weight-bold">TOTAL</td>
                            <td class="text-right font-weight-bold">{{computedTotal}}</td>
                            <td class=""></td>
                        </tr>
                </template>
            </v-data-table>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" md="1"  offset-md="10">
            <v-btn rounded text color="primary" v-on:click="create" small>
                <v-icon small>mdi-plus</v-icon> Crear Línea
            </v-btn>
        </v-col>
    </v-row>
    <lines-create
            :factura="factura"
            :dialog_lin_add.sync="dialog_lin_add"
            :reload.sync="reload"
        >
    </lines-create>
     <lines-edit
            :factura="factura"
            :editedItem="editedItem"
            :reload.sync="reload"
            :dialog_lin_edit.sync="dialog_lin_edit"
        >
    </lines-edit>
</div>
</template>
<script>
import {mapGetters} from 'vuex';
import MyDialog from '@/components/shared/MyDialog'
import FaclinCreate from './FaclinCreate'
import FaclinEdit from './FaclinEdit'
export default {
    props:['factura','dialog_pendientes'],
    components: {
        'my-dialog': MyDialog,
        'lines-create': FaclinCreate,
        'lines-edit': FaclinEdit
	},
    data () {
        return {
            dialog: false,
            dialog_lin_add: false,
            dialog_lin_edit: false,
            items:[],
            reload: 0,
            total_factura: 0,

            editedIndex: -1,
            editedItem: {id:0},

            headers:[
                {
                    text: 'Concepto',
                    align: 'left',
                    value: 'concepto',
                },
                {
                    text: 'IVA',
                    align: 'left',
                    value: 'iva',
                    width:'8%'
                },
                {
                    text: 'Importe',
                    align: 'right',
                    value: 'importe',
                    width:'12%'
                },
                {
                    text: 'Acciones',
                    align: 'Center',
                    value: 'actions',
                    width:'15%'
                }
             ],
            }
        },
    mounted(){

        this.refreshLines();

    },
    computed:{
         ...mapGetters([
            'isAdmin',
        ]),
        computedTotal(){

            return this.getMoneyFormat(this.total_factura);

        }

    },
    watch: {
        dialog_lin_edit: function () {
            this.$emit('update:reload', (this.reload + 1));
        },
        dialog_pendientes: function () {

            this.refreshLines();
        },
        reload: function () {
            this.refreshLines();
        },
    },
    methods:{
        refreshLines(){

            axios.get('/facturas/factlins/'+this.factura.id)
            .then(res => {
                this.items = res.data.lineas;
                this.total_factura = res.data.total;
                //this.$emit('update:totales', res.data.totales);
            })
            .catch(err => {
                if (err.response.status == 404)
                    this.$toast.error("Factura No encontrada!");
                else
                    this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'factura.index'})
            })

        },
        create(){
            this.dialog_lin_add = true;
        },
        editItem(item){

            this.editedIndex = this.items.indexOf(item)
            this.editedItem = item;

            this.dialog_lin_edit = true

        },
        openDialog (id){
            this.dialog = true;
            this.faclins_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/facturas/factlins/'+this.faclins_id,{_method: 'delete'})
                .then(res => {
                    this.refreshLines();
            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data;
                this.$toast.error(msg);

            });

        },
        getMoneyFormat(value){
                return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR"}).format(parseFloat(value))
            },
        getDecimalFormat(value, dec=2){
            return new Intl.NumberFormat("de-DE",{style: "decimal",minimumFractionDigits:dec}).format(parseFloat(value))
        },
    }

}
</script>
