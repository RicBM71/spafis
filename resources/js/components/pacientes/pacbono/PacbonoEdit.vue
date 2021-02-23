<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="pacbono.id" :paciente_id="pacbono.paciente_id" :pacbono="pacbono"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>

                        <v-form>
                            <v-row>
                                <v-col cols="12" md="2"></v-col>
                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-menu
                                        v-model="menu1"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFecha"
                                            label="Fecha"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            data-vv-name="fecha"
                                            :error-messages="errors.collect('fecha')"
                                            data-vv-as="Fecha"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="pacbono.fecha"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu1 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-select
                                            v-model="pacbono.tratamiento_id"
                                            v-validate="'numeric'"
                                            :error-messages="errors.collect('tratamiento_id')"
                                            label="Tratamiento"
                                            data-vv-name="tratamiento_id"
                                            data-vv-as="tratamiento_id"
                                            :items="tratamientos"
                                        ></v-select>
                                </v-col>
                                <v-col cols="12" md="1"></v-col>
                                <v-col cols="12" md="2">
                                    <v-switch
                                        label="Caducado"
                                        v-model="pacbono.caducado"
                                        color="red darken-4">
                                    ></v-switch>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="2"></v-col>
                                <v-col
                                        cols="12"
                                        md="1"
                                    >
                                    <v-text-field
                                        v-model="pacbono.bono"
                                        v-validate="'numeric'"
                                        :error-messages="errors.collect('bono')"
                                        label="Nº Bono"
                                        data-vv-name="bono"
                                        data-vv-as="bono"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                        cols="12"
                                        md="1"
                                    >
                                    <v-text-field
                                        v-model="pacbono.sesiones"
                                        v-validate="'numeric|max:99'"
                                        :error-messages="errors.collect('sesiones')"
                                        label="Sesiones"
                                        data-vv-name="sesiones"
                                        data-vv-as="sesiones"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                        cols="12"
                                        md="1"
                                    >
                                    <v-text-field
                                        v-model="pacbono.importe"
                                        v-validate="'decimal'"
                                        :error-messages="errors.collect('importe')"
                                        label="Importe"
                                        data-vv-name="importe"
                                        data-vv-as="importe"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                        cols="12"
                                        md="1"
                                    >
                                    <v-text-field
                                        v-model="pacbono.caducidad"
                                        v-validate="'numeric'"
                                        :error-messages="errors.collect('caducidad')"
                                        label="Caducidad"
                                        data-vv-name="caducidad"
                                        data-vv-as="caducidad"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                        cols="12"
                                        md="4"
                                    >
                                    <v-text-field
                                        v-model="pacbono.texto"
                                        :error-messages="errors.collect('texto')"
                                        label="Observaciones"
                                        data-vv-name="texto"
                                        data-vv-as="texto"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                 <v-col cols="12" md="2"></v-col>
                                <v-col cols="12" md="2">
                                    <v-text-field
                                        v-model="pacbono.username"
                                        label="Usuario"
                                        readonly
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="2">
                                    <v-text-field
                                        v-model="computedFModFormat"
                                        label="Modificado"
                                        readonly
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="2">
                                    <v-text-field
                                        v-model="computedFCreFormat"
                                        label="Creado"
                                        readonly
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" md="1"></v-col>

                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-btn @click="submit"  rounded  :loading="loading" small color="primary">
                                        Guardar
                                    </v-btn>
                                </v-col>
                            </v-row>


                        </v-form>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex';
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'loading': Loading,
		},
    	data () {
      		return {
                titulo:"Bonos",
                pacbono: {},
                url: "/mto/pacbonos",
                ruta: "pacbono",
                loading: false,
                menu1: false,
                show: false,
                show_loading: true,
                tratamientos:[],
                bonos:[],
                menu1:false
      		}
        },
        mounted(){

            var id = this.$route.params.id;

            if (id > 0)
                axios.get(this.url+'/'+id+'/edit')
                    .then(res => {

                        this.pacbono = res.data.pacbono;
                        this.tratamientos = res.data.tratamientos;
                        //this.bonos = res.data.bonos;

                        this.titulo = this.pacbono.paciente.nom_ape;
                        this.show = true;

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'paciente.edit', params: { id: this.pacbono.paciente_id } })
                    })
                    .finally(()=> {
                        this.show_loading = false;
                    });
        },
        computed: {
        ...mapGetters([
                'hasDelete',
                'isAdmin'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.pacbono.fecha ? moment(this.pacbono.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.pacbono.updated_at ? moment(this.pacbono.updated_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.pacbono.created_at ? moment(this.pacbono.created_at).format('DD/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{

            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                             axios.put(this.url+"/"+this.pacbono.id, this.pacbono)
                                .then(res => {

                                    this.$toast.success(res.data.message);
                                    this.pacbono = res.data.pacbono;
                                    this.$validator.reset();
                                })
                                .catch(err => {

                                    if (err.request.status == 422){ // fallo de validated.
                                        const msg_valid = err.response.data.errors;
                                        for (const prop in msg_valid) {
                                            // this.$toast.error(`${msg_valid[prop]}`);

                                            this.errors.add({
                                                field: prop,
                                                msg: `${msg_valid[prop]}`
                                            })
                                        }
                                    }else{
                                        this.$toast.error(err.response.data.message);
                                    }
                                })
                                .finally(()=> {

                                    this.loading = false;
                                    this.show_loading = false;
                                });
                            }
                        else{
                            this.loading = false;
                        }
                    });
                }

            },

    }
  }
</script>
<style scoped>

.inputNumber >>> input {
  text-align: center;
  -moz-appearance:textfield;
}


.v-form>.container>.layout>.flex {
    padding: 1px 0px 1px 0px;
}
.v-text-field {
    padding-top: 1px;
    margin-top: 1px;
}

</style>
