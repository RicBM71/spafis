<template>
    <div>
        <v-container>
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
                <v-row>
                    <v-col cols="10"></v-col>
                    <v-col cols="1">
                        <v-btn
                            color="primary"
                            small
                            text
                            rounded
                            :disabled="this.selected.length == 0"
                            :loading="loading"
                            @click="submit">
                                    Enviar SMS's
                        </v-btn>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="1"></v-col>
                    <v-col cols="10">
                        <v-data-table
                            :options="options"
                            v-model="selected"
                            :headers="headers"
                            :items="items"
                            item-key="id"
                            show-select
                            class="elevation-1"
                        >
                            <template v-slot:item.fecha="{ item }">
                            {{ formatDate(item.fecha)}}
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>


        </v-card>
        </v-container>
    </div>
</template>
<script>
import moment from 'moment'
  export default {
    components: {
    },
    data () {
      return {
        titulo: "Enviar por SmsPubli",
        loading:false,
        fecha: '',
        items:[],
        selected:[],
        options:{
            itemsPerPage: -1,
            },
        headers: [
            { text: 'Fecha', value: 'fecha' },
            { text: 'Hora', value: 'hora' },
            { text: 'Nombre', value: 'nombre' },
            { text: 'Apellidos', value: 'apellidos' },
            { text: 'Tf', value: 'telefonom' },
        ],
      }
    },
    mounted()
    {
        this.fecha = this.$route.params.fecha;
        axios.get('/tools/sms/send/'+this.fecha)
            .then(res => {
                this.items = res.data;
                this.selected = this.items;

            })
            .catch(err =>{
            ;
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
            .finally(()=>{
                this.loading = false;
            });
    },
    computed: {
    },
    methods:{
        formatDate(f){

            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        goBack(){
            this.$router.push({ name: 'home' })
        },
        submit() {

            if (this.loading === false){
                this.loading = true;
                   axios.post('/tools/sms/send',{'citas': this.selected})
                    .then(res => {
                        this.items = [];
                        this.selected = [];
                        this.$toast.success(res.data+' mensajes enviados!');
                    })
                    .catch(err =>{
                    ;
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash' })
                    })
                    .finally(()=>{
                        this.loading = false;
                    });
                }

        },
    }
  }
</script>
