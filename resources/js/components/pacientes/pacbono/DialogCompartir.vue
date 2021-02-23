<template>
    <v-dialog v-model="compartir" persistent max-width="420">
        <v-card>
            <v-card-title class="headline">Compartir Bono</v-card-title>
            <v-card-text>
                <v-row>
                     <v-col
                        cols="12"
                        md="12"
                    >
                        <v-autocomplete
                            v-model="recomendado_id"
                            :items="recomendados"
                            :loading="isLoading"
                            :search-input.sync="search"
                            label="Paciente Destino"
                            placeholder="Indicar nom,ape"
                            prepend-icon="mdi-database-search"
                            v-validate="'required'"
                            :error-messages="errors.collect('recomendado_id')"
                            data-vv-name="recomendado_id"
                            data-vv-as="paciente"
                            clearable
                            @click:clear="clearRec"
                        ></v-autocomplete>
                    </v-col>
                </v-row>
            </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text rounded @click="closeDialog()">Cancelar</v-btn>
            <v-btn color="blue darken-1" text rounded @click="goCompartir()">Aceptar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
  export default {
    props:['compartir','paciente_id','pacbono_id'],
    data () {
        return {

            recomendados:[],
            isLoading: false,
            search: null,
            recomendado_id: null,
        }
    },
     watch: {
        search (val) {

            if (this.search == null || this.search.length <= 4) return;

            // Items have already been requested
            if (this.isLoading) return;

            this.isLoading = true;


                axios.post('/tools/helpcli', {search: this.search})
                    .then(res => {
                        //console.log(res.data);
                        if (res.data.length > 0){
                            this.recomendados = res.data;
                            this.recomendado_id = this.recomendados[0].value;

                        }

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
                    .finally(() => (
                        this.isLoading = false
                    ));


        },
    },
    methods:{
        clearRec(){
            this.recomendado_id = null;
            this.recomendados = [];
        },
        closeDialog (){
            this.$emit('update:compartir', false)
        },
        goCompartir () {

            var url = "/tools/compartir/bono";
            this.$validator.validateAll().then((result) => {
                if (result){
                    axios.post(url, {dest_paciente_id: this.recomendado_id,
                                     pacbono_id: this.pacbono_id})
                        .then(res => {
                            this.$toast.success('Bono compartido con '+res.data.paciente.nombre);

                            this.$emit('update:compartir', false);
                           // this.$router.push({ name: 'pacbono.edit', params: { id: res.data.id } })
                        })
                        .catch(err => {

                            if (err.request.status == 422){ // fallo de validated.
                                const msg_valid = err.response.data.errors;
                                for (const prop in msg_valid) {
                                    this.errors.add({
                                        field: prop,
                                        msg: `${msg_valid[prop]}`
                                    })
                                }
                            }else{
                                this.$toast.error(err.response.data.message);
                            }
                        })
                        .finally(()=>{


                        });
                    }
            });
        },
        goLocalizar() {

            var url = "/tools/compartir/locate";
            this.$validator.validateAll().then((result) => {
                if (result){
                    axios.post(url, {paciente_id: this.paciente_id,
                                     bono: this.bono})
                        .then(res => {
                            console.log(res);
                        })
                        .catch(err => {
                            this.$toast.error(err.response.data.message);
                        })
                        .finally(()=>{
                        });
                    }
            });

            //this.$emit('destroyReg');
        }
      }
  }
  </script>
