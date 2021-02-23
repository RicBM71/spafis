<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <dialog-compartir :compartir.sync="compartir" :paciente_id="paciente_id" :pacbono_id="id"></dialog-compartir>
        <dialog-compartidos v-if="pacbono.bono > 0" :compartidos.sync="compartidos" :paciente_id="paciente_id" :bono="pacbono.bono" :pacbono_id="id"></dialog-compartidos>
        <v-tooltip bottom v-if="id>0">
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="computedCaducado"
                    v-on="on"
                    color="white"
                    icon
                    @click="goAsignarBono"
                >
                    <v-icon color="primary">mdi-axe</v-icon>
                </v-btn>
            </template>
            <span>Asignar bono a paciente</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="computedCaducado"
                    v-on="on"
                    color="white"
                    icon
                    @click="compartidos = !compartidos"
                >
                    <v-icon color="primary">mdi-folder-account</v-icon>
                </v-btn>
            </template>
                <span>Buscar Compartidos anteriores</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="computedCaducado"
                    v-on="on"
                    color="white"
                    icon
                    @click="compartir = !compartir"
                >
                    <v-icon color="primary">mdi-share-variant</v-icon>
                </v-btn>
            </template>
                <span>Compartir bono</span>
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
                    v-show="id > 0"
                    :disabled="!isAdmin"
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
                    @click="goVolver"
                >
                    <v-icon color="primary">mdi-arrow-left</v-icon>
                </v-btn>
            </template>
            <span>Volver a paciente</span>
        </v-tooltip>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';
import MyDialog from '@/components/shared/MyDialog'
import DialogCompartir from './DialogCompartir'
import BuscarCompartidos from './BuscarCompartidos'
export default {
    props:['id', 'paciente_id', 'pacbono'],
    components: {
        'my-dialog': MyDialog,
        'dialog-compartir': DialogCompartir,
        'dialog-compartidos': BuscarCompartidos
    },
    data () {
      return {
          dialog: false,
          ruta: "pacbono",
          url: "/mto/pacbonos",
          compartir:false,
          compartidos: false,
      }
    },
    computed: {
        ...mapGetters([
           'isAdmin'
        ]),
        computedCaducado(){
            return this.pacbono.caducado == 1 ? true : false;
        }
    },
    methods:{
        goCreate(){
            this.$router.push({ name: 'pacbono.create', params: { id: this.paciente_id } })
        },
        goVolver(){
            this.$router.push({ name: 'paciente.edit', params: { id: this.paciente_id } })
        },
        openDialog (){
            this.dialog = true;
        },
        destroyReg () {
            this.dialog = false;

            axios.post(this.url+'/'+this.id,{_method: 'delete'})
                .then(response => {

                    this.$router.push({ name: 'paciente.edit', params: { id: this.paciente_id } })
                    this.$toast.success('Bono eliminado!');

            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        goCitas(){
            this.$router.push({ name: 'cita.index' })
        },
        goAsignarBono(){

            axios.get('/tools/reasignar/'+this.id)
                .then(response => {
                    this.$toast.success('Bono asignado');
                })
                .catch(err => {
                    console.log(err);
                });
        }

    }
}
</script>
