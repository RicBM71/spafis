<template>
<div>

        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
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
            </v-card-title>
        </v-card>
        <v-card v-if="cita.id > 0">
            <v-form>
                <v-container>
                    <v-row>
                        <v-col
                            cols="3"
                        >
                        </v-col>
                        <v-col
                            cols="3"
                        >
                            <v-text-field
                                outlined
                                v-model="cita.paciente.nom_ape"
                                label="Paciente"
                                background-color="blue lighten-5"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="3"
                        >
                            <v-text-field
                                hide-details="true"
                                outlined
                                v-model="computedCitaDateTime"
                                label="Cita"
                                background-color="blue lighten-5"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="3"
                        >
                        </v-col>
                        <v-col
                            cols="6"
                        >
                            <v-textarea
                                rows="5"
                                row-height="5"
                                outlined
                                v-model="anexo"
                                label="Anexo"
                                hint="Indicar información adicional para incluir en justificante"
                            >
                            </v-textarea>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="8"
                        >
                        </v-col>
                        <v-col
                            cols="2"
                        >
                            <v-btn @click="submit"  rounded  :loading="loading" small color="primary">
                                Imprimir
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>

        </v-card>
    </div>
</template>
<script>
import moment from 'moment'
  export default {
    components: {
    },
    data () {
      return {
        titulo: "Justificante",
        cita:{
            id:0
        },
        loading:false,
        anexo:""

      }
    },
    mounted()
    {

        var id = this.$route.params.id;

        axios.get('/citas/justificante/'+id)
            .then(res => {
                this.cita = res.data;
            })
            .catch(err =>{
                this.$toast.error(err.response.data.message);
           })
    },
    computed: {
        computedCitaDateTime(){
            if (this.cita.hora == null) return 'n/d';
            moment.locale('es');
            const start = this.cita.fecha + " " + this.cita.hora;

            return  moment(start).format('dddd, D [de] MMMM [de] YYYY [-] HH:mm');
        },
    },
    methods:{
        goBack(){
            this.$router.push({ name: 'cita.index' })
        },
        submit() {

            if (this.loading === false){
                this.loading = true;
                    axios({
                        url: "/citas/justificante/print",
                        method: 'post',
                        responseType: 'blob', // important
                        data:{
                            cita_id : this.cita.id,
                            anexo: this.anexo,
                            file: false,
                        }
                    })
                    .then(res => {
                        let blob = new Blob([res.data], { type: 'data:pdf'})
                        let link = document.createElement('a')
                        link.href = window.URL.createObjectURL(blob)

                        link.download = 'Justificante.pdf';

                        document.body.appendChild(link);
                        link.click()
                        document.body.removeChild(link);
                        this.$toast.success("Justificante ok");
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                    })
                    .finally(()=> {
                        this.loading = false;
                    });
                }

        },
    }
  }
</script>
