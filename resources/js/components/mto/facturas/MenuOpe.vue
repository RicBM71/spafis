<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>

        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="factura.id>0  && isAdmin"
                    :disabled="!computedMail"
                    v-on="on"
                    color="white"
                    icon
                    @click="goEmail"
                >
                    <v-icon color="primary">mdi-send</v-icon>
                </v-btn>
            </template>
            <span>Enviar por email</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goCreate"
                >
                    <v-icon color="primary">mdi-plus</v-icon>
                </v-btn>
            </template>
                <span>Nuevo</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="factura.id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="goCliente"
                >
                    <v-icon color="primary">mdi-account</v-icon>
                </v-btn>
            </template>
            <span>Ir a paciente</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goFind"
                >
                    <v-icon color="primary">mdi-magnify</v-icon>
                </v-btn>
            </template>
                <span>Buscar por número</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="factura.id > 0"
                    :disabled="!computedAuthBorrar"
                    v-on="on"
                    color="white"
                    icon
                    @click="openDialog"
                >
                    <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
            </template>
                <span>Borrar Registro</span>
        </v-tooltip>
        <v-tooltip bottom v-if="factura.id > 0">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goIndex"
                >
                    <v-icon color="primary">mdi-format-list-bulleted</v-icon>
                </v-btn>
            </template>
            <span>Lista</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="factura.id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="printAlbPDF"
                >
                    <v-icon color="primary">mdi-printer</v-icon>
                </v-btn>
            </template>
            <span>Imprimir albarán/factura</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goBack()"
                >
                    <v-icon color="primary">mdi-arrow-left</v-icon>
                </v-btn>
            </template>
                <span>Volver</span>
        </v-tooltip>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import {mapGetters} from 'vuex';
export default {
    props:['factura','reload'],
    components: {
        'my-dialog': MyDialog,
        'loading': Loading,
    },
    data () {
      return {
          dialog: false,
          ruta: "factura",
          url: "/facturas/facturas",
          hoy: new Date().toISOString().substr(0, 10),
          show_loading: false,
      }
    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'userName',
            'isRoot',
        ]),
        computedMail(){

            if (this.factura.id > 0 && this.factura.paciente.email > '')
                return true;
            return false;
        },
        computedImprimeCompra(){
            return false;
        },
        computedAuthBorrar(){

            return (this.isAdmin);

        }
    },
    methods:{
        goBack(){
            this.$router.go(-1);
        },
        goCliente(){
            this.$router.push({ name: 'paciente.edit', params: { id: this.factura.paciente_id } })
        },
        goCreate(){
            this.$router.push({ name: this.ruta+'.create', params: { paciente_id: this.factura.paciente_id }  })
        },
        goIndex(){
            this.$router.push({ name: this.ruta+'.index' })
        },
        goFind(){
            this.$router.push({ name: 'find.albaran' })
        },
        printAlbPDF(){
            var url = '/facturas/print/'+this.factura.id;
            window.open(url, '_blank');
        },

        openDialog (){
            this.dialog = true;
        },
        goEmail(){
            this.show_loading = true;
            axios.get('/facturas/mail/'+this.factura.id)
                .then(res => {
                    this.$toast.success('Mail en cola de envío...');
                })
                .catch(err => {
                    this.$toast.error(err.response.data);
                })
                .finally(()=> {
                    this.show_loading = false;
                });

        },
        destroyReg () {
            this.dialog = false;

            axios.post(this.url+'/'+this.factura.id,{_method: 'delete'})
                .then(res => {

                    this.$toast.success('Registro eliminado!');
                    this.$router.push({ name: this.ruta+'.index' })

            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
}
</script>
