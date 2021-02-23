
<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container>
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <menu-ope></menu-ope>
                </v-card-title>
            </v-card>
            <v-card>
                <v-container  v-if="cuentas.length > 0">
                    <v-data-table
                        :headers="headers"
                        :items="cuentas"
                    >
                    <template v-slot:item.iban="{ item }">
                        {{ datosIBAN(props.item.iban,props.item.bic) }}
                    </template>
                    <template v-slot:item.username="{ item }">
                        {{ item.username + " " + formatDateTime(item.updated_at)}}
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            @click="editItem(item.id)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="openDialog(item.id)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    </v-data-table>
                </v-container>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Cuentas",
        search:"",
        headers: [
            {
                text: 'Nombre',
                align: 'left',
                value: 'nombre'
            },
            {
                text: 'IBAN',
                align: 'left',
                value: 'rebu'
            },
            {
                text: 'Activa',
                align: 'left',
                value: 'importe'
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions'
            }
        ],
        cuentas:[],
        status: false,
		registros: false,
        dialog: false,
        cuenta_id: 0,
        show_loading: true,
        items:[]

      }
    },
    mounted()
    {

        axios.get('/admin/cuentas')
            .then(res => {
                
                this.cuentas = res.data;
                this.registros = true;
                this.show_loading = false;
            })
            .catch(err =>{
               ;
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'isAdmin',
        ])
    },
    methods:{
        datosIBAN(iban,bic){

            if (iban == null) return '';

            var iban_f= iban.substring(0,4)+" "+ iban.substring(4,8) + " " + iban.substring(8,12) + " "+ iban.substring(12,16) +" "+ iban.substring(16,20)+" "+ iban.substring(20,24);

            return (this.isAdmin) ? "IBAN: "+iban_f+" - BIC: "+bic :  iban.substring(0,4)+" "+ iban.substring(4,8) + " **** **** **** *" + iban.substring(21,24);
        },
        create(){
            this.$router.push({ name: 'cuenta.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'cuenta.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.cuenta_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/cuentas/'+this.cuenta_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('cuenta eliminado!');
                    this.cuentas = response.data;
                }
                })
            .catch(err => {
                this.status = true;

                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        }
    }
  }
</script>

<style scoped>
  .tachado{text-decoration:line-through;}
</style>
