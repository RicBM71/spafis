<template>
    <v-dialog v-model="compartidos" persistent max-width="420">
        <v-card>
            <v-card-title class="headline">Compartir Bono</v-card-title>
            <v-card-text>
                <v-row>
                     <v-col
                        v-if="bonos.length > 0"
                        cols="12"
                        md="12"
                    >
                        <v-checkbox  v-for="bono in bonos" :key="bono.value" v-model="seleccion" :label="bono.text" :value="bono.value"></v-checkbox>

                     </v-col>
                     <v-col
                        v-else
                        cols="12"
                        md="12"
                    >
                        <p>No hay bonos previamente compartidos</p>

                     </v-col>

                </v-row>
            </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text rounded @click="closeDialog()">Cancelar</v-btn>
            <v-btn color="blue darken-1" text rounded @click="goCompartir()" :disabled="bonos.length == 0">Aceptar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
  export default {
    props:[
            'compartidos',
            'paciente_id',
            'pacbono_id',
            'bono',
    ],
    data () {
        return {
            bonos:[],
            seleccion:[],
            url : "/tools/compartir",
        }
    },
    mounted(){

        axios.post(this.url+"/locate", {
                        paciente_id: this.paciente_id,
                        bono: this.bono})
            .then(res => {
                this.bonos = res.data;
            })
            .catch(err => {
                this.$toast.error(err.response.data.message);
            })
            .finally(()=>{


            });
    },
    methods:{
        closeDialog (){
            this.$emit('update:compartidos', false)
        },
        goCompartir () {
            axios.post(this.url+'/multiple', {
                                pacientes: this.seleccion,
                                pacbono_id: this.pacbono_id})
                .then(res => {
                    this.$toast.success('Bono compartido!');
                    this.$emit('update:compartidos', false)
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                })
                .finally(()=>{
                });

        }
      }
  }
  </script>
