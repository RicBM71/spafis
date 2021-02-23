<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
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
                    v-show="id > 0 && hasDelete"
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
                    @click="goVolver"
                >
                    <v-icon color="primary">mdi-keyboard_return</v-icon>
                </v-btn>
            </template>
            <span>Volver a paciente</span>
        </v-tooltip>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';
import MyDialog from '@/components/shared/MyDialog'
export default {
    props:['id', 'paciente_id'],
    components: {
        'my-dialog': MyDialog
    },
    data () {
      return {
          dialog: false,
          ruta: "historia",
          url: "/mto/historias",
      }
    },
    computed: {
        ...mapGetters([
           'hasDelete'
        ]),
    },
    methods:{
        goCreate(){

            axios.post(this.url, {paciente_id: this.paciente_id})
                .then(response => {
                    this.$toast.success('Se ha creado una nueva entrada');
                    this.$router.push({ name: this.ruta+'.edit', params: { id: response.data.historia.id } })
                })
                .catch(err => {

                    this.$toast.error(err.response.data.message);

                });

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
                    this.$toast.success('Entrada a historia eliminada!');

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
