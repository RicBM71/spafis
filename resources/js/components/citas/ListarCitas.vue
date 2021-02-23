<template>
    <div>
        <v-card>
            <v-card-title color="primary">
                <h2>Listas</h2>
            <v-spacer></v-spacer>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-row>
                    <v-col
                        cols="2"
                        offset-md="4"
                    >
                        <v-menu
                            v-model="menu_d"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="computedFechaD"
                                label="Fecha"
                                prepend-icon="mdi-calendar"
                                readonly
                                data-vv-name="fecha_d"
                                :error-messages="errors.collect('fecha_d')"
                                data-vv-as="Desde"
                                v-on="on"
                            ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="fecha_d"
                                no-title
                                locale="es"
                                first-day-of-week=1
                                @input="menu_d = false">
                            </v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col
                        cols="2"
                    >
                        <v-select
                            v-model="turno"
                            data-vv-name="turno"
                            data-vv-as="turno"
                            :error-messages="errors.collect('turno')"
                            :items="turnos"
                            label="Turno"
                            required
                            ></v-select>
                    </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="2"
                            offset-md="4"
                        >
                            <v-select
                                v-model="facultativo_id"
                                :error-messages="errors.collect('facultativo_id')"
                                label="Facultativo"
                                data-vv-name="facultativo_id"
                                data-vv-as="facultativo_id"
                                :items="facultativos"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="2"
                        >
                            <v-btn @click="submit" block :loading="loading" rounded text color="info">
                                Enviar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

    </div>
</template>
<script>
import moment from 'moment'
import {mapGetters} from 'vuex';
export default {
    $_veeValidate: {
        validator: 'new'
    },
    data () {
      return {
            loading : true,
            disabled: true,
            result: false,
            turnos:[
                    {value: 'M', text:"Mañana"},
                    {value: 'T', text:"Tarde"},
                    {value: 'A', text:"Todos"},
                ],

            turno: 'A',
            menu_d: false,

            fecha_d: new Date().toISOString().substr(0, 10),
            facultativo_id: null,
            facultativos:[],
      }
    },
    mounted(){


        axios.get('/citas/print')
            .then(res => {

                this.facultativos = res.data.facultativos;
                this.facultativo_id = res.data.facultativo_id;

                if (this.facultativo_id == null)
                    this.facultativos.push({value: null, text: 'Todos'});

                this.disabled = this.loading = false;
            })
            .catch(err =>{
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'isAdmin',
        ]),
        computedFechaD() {
            moment.locale('es');
            return this.fecha_d ? moment(this.fecha_d).format('L') : '';
        },
    },
    methods:{
        submit(){

            if (this.loading === false){
                this.$validator.validateAll().then((result) => {
                    if (result){
                        this.loading = true;
                        axios({url: "/citas/print/listas",
                                method: 'post',
                                responseType: 'blob', // important
                                data:{
                                    fecha: this.fecha_d,
                                    turno: this.turno,
                                    facultativo_id: this.facultativo_id,
                                    area_id: 1
                                }
                        })
                        .then(res => {
                                let blob = new Blob([res.data], { type: 'data:pdf'})
                                let link = document.createElement('a')
                                link.href = window.URL.createObjectURL(blob)

                                link.download = 'listas.pdf';

                                document.body.appendChild(link);
                                link.click()
                                document.body.removeChild(link);
                                this.$toast.success("Listas ok");

                                this.loading = false;

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
                            this.loading = false;
                        });

                    }
                 });
            }

        },
    }
}
</script>

<style scoped>

.centered-input >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}

.v-form>.container>.layout>.flex {
    padding: 1px 8px 1px 8px;
}
.v-text-field {
    padding-top: 6px;
    margin-top: 2px;
}
</style>
