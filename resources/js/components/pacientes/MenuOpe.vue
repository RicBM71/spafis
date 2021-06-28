<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <loading :show_loading="show_loading"></loading>
        <v-tooltip bottom v-if="isAdmin">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goBalance"
                >
                    <v-icon color="primary">mdi-calculator</v-icon>
                </v-btn>
            </template>
            <span>Balance</span>
        </v-tooltip>
        <v-tooltip bottom v-if="hasContact && foto==false">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goCaptura"
                >
                    <v-icon color="red darken-4">mdi-camera-plus</v-icon>
                </v-btn>
            </template>
            <span>Capurar Foto</span>
        </v-tooltip>
        <v-tooltip bottom v-if="isAdmin && foto!=false && id > 0">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goDelCaptura"
                >
                    <v-icon color="red darken-4">mdi-head-remove</v-icon>
                </v-btn>
            </template>
            <span>Eliminar Foto</span>
        </v-tooltip>
        <v-tooltip bottom v-if="recomendado_id > 0">
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="!hasContact"
                    v-on="on"
                    color="white"
                    icon
                    @click="goEditLink"
                >
                    <v-icon color="primary">mdi-link-variant</v-icon>
                </v-btn>
            </template>
                <span>Ir a vinculado</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="!hasContact"
                    v-on="on"
                    color="white"
                    icon
                    @click="goLortad"
                >
                    <v-icon color="primary">mdi-printer</v-icon>
                </v-btn>
            </template>
                <span>Imprimir consentimiento</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="!hasContact"
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
                    :disabled="!hasDelete"
                    v-show="id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="openDialog"
                >
                    <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
            </template>
                <span>Borrar Paciente</span>
        </v-tooltip>
        <v-tooltip bottom v-if="isAdmin">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goExport"
                >
                    <v-icon color="green darken-4">mdi-file-excel</v-icon>
                </v-btn>
            </template>
            <span>Exportar plantilla Csv Google</span>
        </v-tooltip>
        <v-tooltip bottom>
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
                    v-on="on"
                    color="white"
                    icon
                    @click="goCitas"
                >
                    <v-icon color="primary">mdi-calendar-clock</v-icon>
                </v-btn>
            </template>
            <span>Citas</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goBack"
                >
                    <v-icon color="primary">mdi-arrow-left</v-icon>
                </v-btn>
            </template>
            <span>Volver</span>
        </v-tooltip>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
export default {
    props:['id', 'foto', 'recomendado_id'],
    components: {
        'my-dialog': MyDialog,
        'loading': Loading,
    },
    data () {
      return {
          dialog: false,
          ruta: "paciente",
          url: "/mto/pacientes",
          show_loading: false,
      }
    },
    computed: {
        ...mapGetters([
           'isAdmin',
           'hasContact',
           'hasDelete'
        ]),
    },
    methods:{
        goBack(){
            this.$router.go(-1);
        },
        goCreate(){
            this.$router.push({ name: this.ruta+'.create' })
        },
        goCierre(){
            this.$router.push({ name: this.ruta+'.cierre' })
        },
        goIndex(){
            this.$router.push({ name: this.ruta+'.index' })
        },
        goCitas(){
            this.$router.push({ name: 'cita.index' })
        },
        goEditLink(){
            this.$router.push({ name: this.ruta+'.edit', params: { id: this.recomendado_id } })
        },
        goCaptura(){
            this.$router.push({ name: this.ruta+'.captura' })
        },
        goDelCaptura(){
            axios.post(this.url+'/delcap',{'paciente_id':this.id})
                .then(response => {
                    this.$toast.success('Captura eliminada');
                    this.goCaptura();

            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });
        },
        openDialog (){
            this.dialog = true;
        },
        destroyReg () {
            this.dialog = false;

            axios.post(this.url+'/'+this.id,{_method: 'delete'})
                .then(response => {

                    this.$router.push({ name: this.ruta+'.index' })
                    this.$toast.success('Registro eliminado!');

            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        goExport(){

            if (this.show_loading === false){

                this.show_loading = true;
                axios.get('/tools/pacientes/export')
                .then(res => {


                    let blob = new Blob([res.data], { type: 'data:csv'})
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)

                    link.download = 'Contacs.csv';

                    document.body.appendChild(link);
                    link.click()
                    document.body.removeChild(link);

                    this.$toast.success('Contactos exportados '+link.download);



                })
                .catch(err => {

                    if(err.request.status == 404){
                        this.$toast.error('No hay etiquetas');
                    }
                    else{
                        this.$toast.error(err.response.data.message);
                    }

                })
                .finally(()=> {
                        this.show_loading = false;
                });

            }
        },
        goBalance(){
            this.$router.push({ name: 'balance.paciente', params: { id: this.id } })
        },
        goLortad(){

             //window.location = '/pdf/lortad/'+this.id;
             window.open('/pdf/lortad/'+this.id);

            // axios({
            //     url: "/print/lortad"+this.id,
            //     method: 'get',
            //     responseType: 'blob', // important
            // })
            // .then(res => {
            //     let blob = new Blob([res.data], { type: 'data:pdf'})
            //     let link = document.createElement('a')
            //     link.href = window.URL.createObjectURL(blob)

            //     link.download = 'Lortad.pdf';

            //     document.body.appendChild(link);
            //     link.click()
            //     document.body.removeChild(link);
            //     this.$toast.success("Formulario generado ok");
            // })
            // .catch(err => {
            //     this.$toast.error(err.response.data.message);
            // })
            // .finally(()=> {
            //     this.loading = false;
            // });

        }


    }
}
</script>
