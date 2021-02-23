<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0"
                    :disabled="!hasDelete"
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
                    <v-icon color="primary">mdi-keyboard-return</v-icon>
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
        goCitas(){
            this.$router.push({ name: 'cita.index' })
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
