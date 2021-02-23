<template>
    <div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="bloqueo.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>

                    <v-row>
                        <v-col
                            cols="2"
                            md="2">
                        </v-col>
                        <v-col
                            cols="10"
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
                                    v-model="bloqueo.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu1 = false">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="6"
                            md="2"
                        >
                            <v-select
                                v-model="bloqueo.facultativo_id"
                                :error-messages="errors.collect('facultativo_id')"
                                label="Facultativo"
                                data-vv-name="facultativo_id"
                                data-vv-as="facultativo_id"
                                :items="facultativos"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="6"
                            md="1"
                        >
                           <v-menu
                                ref="menu1"
                                v-model="menuh1"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="bloqueo.start"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="bloqueo.start"
                                    label="Desde"
                                    prepend-icon="mdi-clock-time-four-outline"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-time-picker
                                    min="09:00"
                                    max="20:00"
                                    format="24hr"
                                    v-if="menuh1"
                                    v-model="bloqueo.start"
                                    full-width
                                    @click:minute="$refs.menu1.save(bloqueo.start)"
                                ></v-time-picker>
                            </v-menu>
                        </v-col>
                         <v-col
                            cols="6"
                            md="1"
                        >
                           <v-menu
                                ref="menu2"
                                v-model="menuh2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="bloqueo.end"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="bloqueo.end"
                                    label="Hasta"
                                    prepend-icon="mdi-clock-time-four-outline"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-time-picker
                                    min="09:00"
                                    max="20:00"
                                    format="24hr"
                                    v-if="menuh2"
                                    v-model="bloqueo.end"
                                    full-width
                                    @click:minute="$refs.menu2.save(bloqueo.end)"
                                ></v-time-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="2"
                            md="2">
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="bloqueo.motivo"
                                v-validate="'max:100'"
                                :error-messages="errors.collect('motivo')"
                                label="Motivo"
                                data-vv-name="motivo"
                                data-vv-as="motivo"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="10" md="1">
                            <v-text-field
                                v-model="bloqueo.username"
                                label="Usuario"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="10" md="2">
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="10" md="2">
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-btn @click="submit" text  rounded  :loading="loading" color="primary">
                                Guardar
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
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex';
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'loading': Loading
		},
    	data () {
      		return {
                titulo:"Editar",
                bloqueo: {},
                url: "/mto/bloqueos",
                ruta: "bloqueo",
                loading: false,
                menu1: false,

                show: false,
                show_loading: true,
                facultativos:[],
                menuh1: false,
                menuh2: false,

      		}
        },
        mounted(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get(this.url+'/'+id+'/edit')
                    .then(res => {

                        this.bloqueo = res.data.reg;
                        this.facultativos = res.data.facultativos;

                        this.show = true;
                        this.show_loading = false;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
        },
        computed: {
        ...mapGetters([
                'isAdmin',
                'isSupervisor'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.bloqueo.fecha ? moment(this.bloqueo.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.bloqueo.updated_at ? moment(this.bloqueo.updated_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.bloqueo.created_at ? moment(this.bloqueo.created_at).format('DD/MM/YYYY H:mm') : '';
            }

        },
    	methods:{
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                             axios.put(this.url+"/"+this.bloqueo.id, this.bloqueo)
                                .then(res => {

                                    this.$toast.success(res.data.message);
                                    this.bloqueo = res.data.reg;
                                    this.loading = false;
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
                                    this.loading = false;
                                })
                                .finally(()=> {
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
