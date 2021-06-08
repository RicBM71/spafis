<template>
    <v-col cols="12" class="pa-0 ma-0">
        <loading :show_loading="show_loading"></loading>
        <v-card v-show="load_ini">
            <v-card-text>
                <v-row>
                    <v-col
                        v-if="areas.length > 1"
                        class="pa-0 ma-1"
                        cols="12"
                        md="1"
                    >
                        <v-select
                            class="caption"
                            v-model="area_id"
                            :items="areas"
                            dense
                            outlined
                            hide-details
                            label="Area"
                        ></v-select>
                    </v-col>
                    <auto-paciente :paciente_id.sync="paciente_id" :numero_bono.sync="numero_bono"></auto-paciente>
                    <v-col
                        cols="5"
                        md="1"
                        class="pa-0 ma-1"
                    >
                        <v-text-field
                            class="body-2 caption"
                            dense
                            outlined
                            clearable
                            append-outer-icon="mdi-alpha-b-circle-outline"
                            v-model="numero_bono"
                            v-validate="'numeric'"
                            :error-messages="errors.collect('numero_bono')"
                            label="Bono"
                            data-vv-name="numero_bono"
                            data-vv-as="numero_bono"
                            @click:append-outer="goBonos"
                        >
                        </v-text-field>
                    </v-col>
                    <v-col
                        cols="6"
                        md="1"
                        class="pa-0 ma-0"
                    >
                        <v-tooltip top v-if="paciente_id > 0">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="ma-2"
                                    small
                                    color="primary"
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="goAddCita"
                                >
                                    <v-icon>mdi-calendar-plus</v-icon>
                                </v-btn>
                            </template>
                            <span>Nueva Cita</span>
                        </v-tooltip>
                        <v-tooltip top v-if="paciente_id > 0">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="ma-2"
                                    small
                                    color="primary"
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="goPaciente"
                                >
                                    <v-icon>mdi-account</v-icon>
                                </v-btn>
                            </template>
                            <span>Ir a paciente</span>
                        </v-tooltip>
                        <v-tooltip top v-if="paciente_id > 0">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="ma-2"
                                    small
                                    color="primary"
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="goUltimas"
                                >
                                    <v-icon>mdi-calendar-multiselect</v-icon>
                                </v-btn>
                            </template>
                            <span>Últimas 10 citas</span>
                        </v-tooltip>
                    </v-col>
                    <v-spacer></v-spacer>
                    <v-col
                        cols="12"
                        md="2"
                        class="pa-0 ma-1"
                    >
                        <v-tooltip top v-if="isSupervisor">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    small
                                    class="ma-2"
                                    color="primary"
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="goAddPaciente"
                                >
                                    <v-icon>mdi-account-plus</v-icon>
                                </v-btn>
                            </template>
                            <span>Nuevo Paciente</span>
                        </v-tooltip>
                        <v-tooltip top v-if="isAdmin">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    small
                                    class="ma-2"
                                    color="primary"
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="goTratadoAll"
                                >
                                    <v-icon>mdi-check-all</v-icon>
                                </v-btn>
                            </template>
                            <span>Tratar todas</span>
                        </v-tooltip>
                        <v-btn
                            small
                            color="primary"
                            icon
                            class="ma-2"
                            @click="prev()"
                        >
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>
                        <v-btn
                            small
                            color="primary"
                            icon
                            class="ma-2"
                            @click="hoy()"
                        >
                            <v-icon>mdi-calendar-today</v-icon>
                        </v-btn>
                        <v-btn
                            small
                            color="primary"
                            icon
                            class="ma-2"
                            @click="next()"
                        >
                            <v-icon>mdi-chevron-right</v-icon>
                        </v-btn>
                    </v-col>
                    <v-col
                        class="pa-0 ma-1"
                        cols="5"
                        md="2"
                    >
                        <v-btn
                            v-if="type=='day'"
                            class="ma-2"
                            outlined
                            color="indigo"
                            small
                            @click="setType('custom-daily')"
                        >
                            <span class="caption">Semana</span>
                        </v-btn>
                        <v-btn
                            v-else
                            class="caption ma-2"
                            color="indigo"
                            small
                            outlined
                            @click="setType('day')"
                        >
                            <span class="caption">Ver Día</span>
                        </v-btn>

                        <v-btn
                            v-if="activa=='A'"
                            class="ma-2"
                            outlined
                            color="indigo"
                            small
                            @click="setActiva('B')"
                        >
                            <span class="caption">Activas</span>
                        </v-btn>
                        <v-btn
                            v-if="activa=='B'"
                            class="caption ma-2"
                            color="indigo"
                            small
                            outlined
                            @click="setActiva('T')"
                        >
                            <span class="caption">Anuladas</span>
                        </v-btn>
                        <v-btn
                            v-if="activa=='T'"
                            class="caption ma-2"
                            color="indigo"
                            small
                            outlined
                            @click="setActiva('A')"
                        >
                            <span class="caption">Todas</span>
                        </v-btn>
                    </v-col>
                    <v-col
                        class="pa-0 ma-1"
                        cols="5"
                        md="1"
                    >
                        <v-select
                            class="caption"
                            v-model="facultativo_id"
                            :items="facultativos"
                            dense
                            outlined
                            hide-details
                            label="Facultativo"
                            @change="changeFisAct()"
                        ></v-select>
                    </v-col>
                    <!-- <v-col
                        class="pa-0 ma-1"
                        cols="5"
                        md="1"
                    >
                        <v-select
                            class="caption"
                            v-model="activa"
                            :items="activas"
                            dense
                            outlined
                            hide-details
                            label="Citas"
                            @change="reloadFisAct()"
                        ></v-select>
                    </v-col> -->
                    <v-col
                        class="pa-0 ma-1"
                        cols="5"
                        md="1"
                    >
                        <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                            <v-text-field
                                class="caption"
                                dense
                                outlined
                                v-model="computedFecha"
                                label="Fecha"
                                readonly
                                v-on="on"
                            ></v-text-field>
                            </template>
                            <v-date-picker
                                :min="min_picker"
                                :events="festivos"
                                event-color="red darken-1"
                                v-model="mi_fecha"
                                no-title
                                locale="es"
                                first-day-of-week=1
                                @change="setFecha()"
                                @input="menu = false">
                            </v-date-picker>
                        </v-menu>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col
                        class="pa-0 mb-1"
                        cols="12"
                    >
                        <v-sheet height="570" v-if="citas.length > 0">

                            <v-calendar
                                ref="calendar"
                                v-model="start"
                                :categories="categories"
                                :type="type"
                                :start="start"
                                :end="end"
                                :min-weeks="minWeeks"
                                :max-days="maxDays"
                                :now="now"
                                :first-time="first_time"
                                :dark="dark"
                                :weekdays="weekdays"
                                :color="color"
                                :events="events"
                                :first-interval="intervals.first"
                                :interval-minutes="intervals.minutes"
                                :interval-count="intervals.count"
                                :interval-height="intervals.height"
                                :interval-style="intervalStyle"
                                :interval-width=40
                                :show-interval-label="showIntervalLabel"
                                :short-intervals="shortIntervals"
                                :short-months="shortMonths"
                                :short-weekdays="shortWeekdays"
                                :event-overlap-mode="mode"
                                :event-overlap-threshold=10
                                :event-color="getEventColor"
                                @change="getEvents"
                                @click:event="showEvent"
                                @click:date="clickDate"
                                @click:time="addDayCategory"
                                @click:time-category="clickTimeCategory"
                                @click:day-category="clickCategory"
                            >


                                <template  v-slot:interval="{ hour, minute }">
                                    <div
                                    class="text-center caption"
                                    >
                                    {{ hour }}:<span v-if="minute==0">00</span><span v-else>{{minute}}</span>
                                    </div>
                                </template>
                                <template v-slot:event="{event}">

                                    <div :style="{'background-color':event.color,color:'black','font-size':font_size}" class="fill-height pl-1">
                                        <div :style="{'font-size':'10px'}" class="fill-height pl-1">
                                            <!-- <v-icon v-if="event.estado_id > 1" small :color="setColorEstado(event)">mdi-check</v-icon> -->
                                            <v-icon v-if="isSupervisor" small :color="setColorEstado(event)">{{setIcono(event)}}</v-icon>
                                            <b :color="colores[event.estado_id]">{{getTimeFormat(event.start)}}</b> - <span :class="textAnulado(event.estado_id)">{{ event.name }}</span>
                                            <span>- {{getTimeFormat(event.end)}}</span>
                                            <span v-if="isSupervisor && event.notas == null && event.importe > 0" style="float:right;padding-right:5px;">{{ getCurrencyFormat(event.importe)}}</span>
                                            <br/>
                                            <span v-if="event.tratamiento!=null">({{ event.tratamiento}})</span> <span> <b v-if="isSupervisor && event.bono != null" :style="{'color':'white','background-color':'#880E4F'}">({{event.bono}})</b> </span>

                                            <span style="color:#880E4F">{{event.notas}}</span>

                                        </div>
                                        <!-- <div v-else class="fill-height pl-2">
                                            <v-icon v-if="event.estado_id > 1" small :color="setColorEstado(event)">mdi-check</v-icon>
                                            <b>{{getTimeFormat(event.start)}}</b> - ({{ event.tratamiento}}) - <span :class="textAnulado(event.estado_id)">{{ event.name }}</span> <b>*{{getTimeFormat(event.end)}}</b>
                                            <b v-if="event.bono != null" :style="{color:'black'}">({{event.bono}})</b>
                                        </div> -->

                                    </div>
                                </template>
                            </v-calendar>
                        </v-sheet>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col class="pa-4" cols="12">
                        <v-divider></v-divider>
                    </v-col>
                </v-row>

                <ver-cita v-if="action_cita=='V'"
                    :cita.sync="cita"
                    :historia.sync="historia"
                    :bono.sync="bono"
                    :saldo.sync="saldo"
                    :event="event_active"
                    :reload_id.sync="reload_id"
                    :action_cita.sync="action_cita"
                    :show_loading.sync="show_loading"
                    :dialog_ultimas.sync="dialog_ultimas"
                    :dialog_bono.sync="dialog_bono"
                    :numero_bono.sync="numero_bono"
                    :whatsapp="whatsapp"
                    :paciente_id.sync="paciente_id"
                    :ultima="ultima"
                    :noleidos="noleidos"
                    :dialog_tpv.sync="dialog_tpv">
                </ver-cita>

                <edit-cita v-show="action_cita=='E'"
                    ref="componentEditCita"
                    :cita="cita"
                    :action_cita.sync="action_cita"
                    :reload_id.sync="reload_id"
                    :show_loading.sync="show_loading">
                </edit-cita>
                <add-cita v-if="action_cita=='A'"
                    ref="componentAddCita"
                    :cita.sync="cita"
                    :action_cita.sync="action_cita"
                    :reload_id.sync="reload_id"
                    :show_loading.sync="show_loading"
                    :facultativo_id="facultativo_id">
                </add-cita>

                <cobros v-if="action_cita=='C'"
                    :cita.sync="cita"
                    :saldo.sync="saldo"
                    :reload_id.sync="reload_id"
                    :action_cita.sync="action_cita"
                    :show_loading.sync="show_loading"
                    :dialog_cobro.sync="dialog_cobro"
                >
                </cobros>
                <cancel-cobros v-if="action_cita=='CC'"
                    :cita.sync="cita"
                    :saldo.sync="saldo"
                    :reload_id.sync="reload_id"
                    :action_cita.sync="action_cita"
                    :show_loading.sync="show_loading"
                    :dialog_cancel_cobro.sync="dialog_cancel_cobro"
                >
                </cancel-cobros>
                <ultimas-citas
                    :paciente_id="cita.paciente_id"
                    :dialog_ultimas.sync="dialog_ultimas">
                </ultimas-citas>
                <citas-bono
                    :dialog_bono.sync="dialog_bono"
                    :numero_bono.sync="numero_bono">
                </citas-bono>
                <tpv v-if="cita.id > 0"
                    :cita="cita"
                    :show_loading.sync="show_loading"
                    :dialog_tpv.sync="dialog_tpv"
                    :reload_id.sync="reload_id">
                </tpv>

            </v-card-text>

        </v-card>
        </v-col>

</template>
<script>
import moment from 'moment'
import MenuOpe from './MenuOpe'
import Loading from '@/components/shared/Loading'
import Autopac from './Autopac'
import VerCita from './VerCita'
import EditCita from './EditCita'
import AddCita from './AddCita'
import Cobros from './Cobros'
import Tpv from './Tpv'
import CancelCobros from './CancelCobros'
import UltimasCitas from './UltimasCitas.vue'
import CitasBono from './CitasBono.vue'
import {mapGetters} from 'vuex';
import {mapActions} from "vuex";
import {mapState} from 'vuex'

  const intervalsDefault = {
    first: 0,
    minutes: 30,
    count: 23,
    height: 48,
  }

  const stylings = {
    default (interval) {
      return undefined
    },
    workday (interval) {
      const inactive = interval.weekday === 0 ||
        interval.weekday === 6 ||
        (interval.hour === 14 && interval.minute >= 30) ||
        interval.hour === 15 ||
        (interval.hour === 16 && interval.minute === 0) ||
        (interval.weekday < 5 && interval.hour === 14 ) ||
        (interval.weekday === 5 && interval.hour >= 15 ) ||
        interval.hour >= 20
      const startOfHour = interval.minute === 0
      const dark = this.dark
      const mid = dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'

      return {
        backgroundColor: inactive ? (dark ? 'rgba(0,0,0,0.4)' : 'rgba(0,0,0,0.05)') : undefined,
        borderTop: startOfHour ? undefined : '1px dashed ' + mid,
      }
    },
    past (interval) {
      return {
        backgroundColor: interval.past ? (this.dark ? 'rgba(0,0,0,0.4)' : 'rgba(0,0,0,0.05)') : undefined,
      }
    },
  }

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'loading': Loading,
            'auto-paciente': Autopac,
            'ver-cita': VerCita,
            'edit-cita': EditCita,
            'add-cita': AddCita,
            'cobros': Cobros,
            'tpv': Tpv,
            'cancel-cobros': CancelCobros,
            'ultimas-citas': UltimasCitas,
            'citas-bono': CitasBono
		},
    	data () {

            return {
                load_ini: false,
                drawer: false,
                titulo:"Timing Citas",
                show_loading: true,

                dark: false,
                startMenu: false,

                endMenu: false,
                start: new Date(),
                end: '2020-12-31',
                //end: (new Date().getDate() + 365).toISOString().substr(0, 10),
                nowMenu: false,
                minWeeks: 1,
                now: null,
                events: [],
                // colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
                // names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
                type: 'custom-daily',
                typeOptions: [
                     { text: 'Diaria', value: 'day'},
                    // { text: 'Mes', value: 'month'},
                    { text: 'Semanal', value: 'custom-daily'},
                    { text: 'Fisios', value: 'category' },
                ],

                weekdays: [],
                // weekdaysOptions: [
                //     { text: 'Sunday - Saturday', value: weekdaysDefault },
                //     { text: 'Mon, Wed, Fri', value: [1, 3, 5] },
                //     { text: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
                //     { text: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
                // ],
                intervals: intervalsDefault,
                intervalsOptions: [
                    { text: 'Default', value: intervalsDefault },
                    { text: 'Workday', value: { first: 16, minutes: 60, count: 20, height: 48 } },
                ],
                maxDays: 6,
                // maxDaysOptions: [
                //     { text: '7 days', value: 7 },
                //     { text: '5 days', value: 5 },
                //     { text: '4 days', value: 4 },
                //     { text: '3 days', value: 3 },
                // ],
                styleInterval: 'workday',
                styleIntervalOptions: [
                    { text: 'Default', value: 'default' },
                    { text: 'Workday', value: 'workday' },
                    { text: 'Past', value: 'past' },
                ],
                color: 'primary',

                shortIntervals: true,
                shortMonths: false,
                shortWeekdays: false,
                mode:"column",
                threshold:45,

                citas:[],

                categories:[],

                first_time:"09:00",

                event_active: { id: 0},
                menu: false,
                mi_fecha: new Date().toISOString().substr(0, 10),

                area_id: 1,
                areas:[],
                facultativo_id: null,
                facultativos:[],

                activa : 'A',
                activas:[
                    {text: 'Activas', value: 'A'},
                    {text: 'Anuladas', value: 'B'},
                    {text: 'Todas', value: 'T'},
                ],
                paciente_id: null,
                paciente_nombre: null,
                cita_id: '',
                action_cita: false,
                reload_id: 0,

                cita:{
                    id: 0,
                    paciente_id: null,
                    fecha: new Date().toISOString().substr(0, 10),
                    hora: null,
                    notas: null,
                    tratamiento_id:null,
                    importe:0,
                    importe_ponderado: 0,
                    facultativo_id: null,
                    estado_id: 1,
                    mutua_id: null,
                    area_id: 1,
                    bono: null

                },

                historia:{},
                saldo:0,
                bono:null,
                isLoading: false,
                font_size: '12px',

                colores:['orange','blue','green','red darken-4'],

                dialog_cobro: false,
                dialog_cancel_cobro: false,
                dialog_ultimas: false,
                dialog_bono: false,
                dialog_tpv: false,
                numero_bono: null,
                festivos:[],
                whatsapp: false,
                showMenu: false,
                ultima: false,
                bloqueos:[],
                min_picker: null,
                noleidos: 0,
            }
        },
        mounted(){

            if (this.getUltCita.id > 0){

                this.reloadCita(this.getUltCita.id);
                this.mi_fecha = this.getUltCita.fecha;
                this.start = this.mi_fecha;
                this.reload_id++;
                this.action_cita = "V";
            }


            var d = new Date();
            d.setDate(d.getDate() + 365);

            this.end = d;

            if (!this.isAdmin){
                var m = new Date();
                m.setDate(m.getDate() - 60);
                this.min_picker = m.toISOString().substr(0, 10);
            }else{
                this.min_picker = "2000-01-01";
            }

                //  if (this.facultativo == null)
                //         this.facultativos.push({value:null,text:"Todos"});
                //     else{
                //         this.facultativo_id = this.facultativo;
                //     }

            axios.post('/citas/filtro',{
                    fecha:  this.mi_fecha,
                    estado: this.activa,
                    facultativo_id: this.facultativo_id
            })
                .then(res => {

                    this.weekdays = res.data.weekdays;

                    this.citas = res.data.citas;
                    this.areas = res.data.areas;
                    this.bloqueos = res.data.bloqueos;

                    this.facultativos = res.data.facultativos;

                    if (this.facultativo == null){
                        this.facultativos.push({value:null,text:"Todos"});
                        this.categories = res.data.categories;
                    }
                    else{
                        this.categories.push(this.facultativo);
                        this.facultativo_id = this.facultativo;
                    }

                    this.festivos = res.data.festivos;

                    this.load_ini = true;
                    this.show_loading = false;
                })
                .catch(err =>{
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'dash' })
                })


        },
        computed: {
            ...mapState({
                ult_cita: state => state.cita
            }),
            ...mapGetters([
                'isAdmin',
                'isSupervisor',
                'facultativo',
                'getUltCita'
            ]),
            computedFecha() {
                moment.locale('es');
                return this.mi_fecha ? moment(this.mi_fecha).format('dd DD/MM/YYYY') : '';
            },
            intervalStyle () {

                console.log(this.styleInterval);

              return stylings[this.styleInterval].bind(this)
            },
            hasIntervals () {
                return this.type in {
                    week: 1, day: 1, 'custom-weekly': 1, 'custom-daily': 1,
            }
            },
            hasEnd () {
                return this.type in {
                    'custom-weekly': 1, 'custom-daily': 1,
            }
            },
        },
        watch: {
            mi_fecha: function () {

                if (this.action_cita == 'A'){
                    console.log('mi_fecha '+this.action_cita);
                    this.cita.fecha = this.mi_fecha;
                    this.$refs.componentAddCita.loadHoras();
                }

                if (this.action_cita == 'E'){
                    this.cita.fecha = this.mi_fecha;
                    this.$refs.componentEditCita.loadHoras();
                }
            },
            action_cita: function () {

                if (this.action_cita == 'A'){
                    this.cita.hora = null;
                    this.cita.notas = null;
                    this.cita.bono = null;
                }

                if (this.action_cita == 'C')
                    this.dialog_cobro = true;
                if (this.action_cita == 'CC')
                    this.dialog_cancel_cobro = true;

            },
            reload_id: function () {
                                //this.$refs.calendar.parseEvent(this.event_active, this.event_active.index)
                if (this.action_cita != 'A' && this.action_cita != 'E'){
                    this.reloadFisAct();
                    this.reloadCita(this.cita.id);
                }

            },
        },
    	methods:{
            ...mapActions([
                'setCita',
			]),
            getCurrencyFormat(value){
                return value == null ?  null : new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:0}).format(parseFloat(value))
            },
            setColorEstado(event){
                if (event.estado_id == 2)
                    return (event.bono > 0 && event.importe == 0) ? this.colores[1] : this.colores[3];

                return this.colores[event.estado_id - 1];
            },
            setIcono(event){
                if (event.estado_id == 2)
                    return (event.bono > 0 && event.importe == 0) ? 'mdi-check-all' : 'mdi-alert-circle';

                if (event.estado_id == 3)
                    return 'mdi-check-all';


            },
            textAnulado(estado_id){
                return estado_id == 4 ? 'text-decoration-line-through' : null;
            },
            home(){
                this.$router.push({name: 'dash'})
            },
            reload(f, incremento=0){

                this.show_loading = true;

                axios.post('/citas/reload',{ fecha: f,
                                             incremento: incremento,
                                             estado: this.activa,
                                             facultativo_id: this.facultativo_id})
                    .then(res => {


                        this.weekdays = res.data.weekdays;

                        this.citas = res.data.citas;
                        this.bloqueos = res.data.bloqueos;




                        this.start =  moment(res.data.today).format('YYYY-MM-DD h:mm');

                        this.mi_fecha = this.start;

                        this.show_loading = false;
                    })
                    .catch(err =>{
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash' })
                    })
                    .finally(()=> {
                        this.show_loading = false;
                    });
            },

            reloadCita(id){

                if (id == 0) return;

                this.show_loading = true,

                axios.get('/citas/citas/'+id)
                    .then(res => {

                        this.cita = res.data.cita;
                        this.saldo = res.data.saldo;
                        this.historia = res.data.historia;
                        this.bono      = res.data.bono;
                        this.whatsapp  = res.data.whatsapp;
                        this.ultima    = res.data.ultima;
                        this.noleidos  = res.data.noleidos;

                        this.setCita({fecha: this.cita.fecha,
                                      facultativo_id: this.cita.facultativo_id,
                                      type: this.type,
                                      id: this.cita_id});

                    })
                    .catch(err =>{
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash' })
                    })
                    .finally(()=> {
                        this.show_loading = false;
                    });
            },
            hoy(){
                this.mi_fecha = new Date().toISOString().substr(0, 10);
                this.reload(this.mi_fecha,0);
            },
            setFecha(){

                this.reload(this.mi_fecha,0);
            },
            changeFisAct(){
                this.setType('custom-daily');
                this.reloadFisAct();
            },
            reloadFisAct(){
                this.axFisAct();
                this.cita.facultativo_id = this.facultativo_id;
                console.log('reloadFisAct');

                if (this.action_cita == 'A')
                    this.$refs.componentAddCita.loadHoras();

                if (this.action_cita == 'E')
                    this.$refs.componentEditCita.loadHoras();

            },
            async axFisAct(f, incremento = 0) {

                //console.log(f);

                this.show_loading = true;

                let res = await axios.post('/citas/reload',{ fecha: this.mi_fecha,
                                             incremento: 0,
                                             estado: this.activa,
                                             facultativo_id: this.facultativo_id});
                this.weekdays = res.data.weekdays;

                this.citas = res.data.citas;


                this.loadEvents();
                this.show_loading = false;

            },
            getTimeFormat(dt){

                return moment(dt).format('H:mm');
            },
            // viewDay ({ date }) {

            //     this.start = date
            //     this.type = 'day'
            // },

            showIntervalLabel (interval) {
                return interval.minute === 0
            },
            prev () {
                //this.$refs.calendar.prev()
                this.reload(this.start, -1);
            },
            next () {
                //this.$refs.calendar.next()
                this.reload(this.start, 1);
            },
            showEvent ({ nativeEvent, event }) {

                this.cita_id = event.id;
                console.log('showEvent');

                // if (this.event_active.id > 0){

                //     this.event_active.color = this.event_active.color_original;
                //     this.$refs.calendar.parseEvent(this.event_active, this.event_active.index)
                // }

                // event.color = "orange";
                // this.$refs.calendar.parseEvent(event, event.index)
                this.event_active = event;

                this.reloadCita(this.cita_id);

                this.action_cita = 'V';
                this.showMenu = true;

            },
            clickDate(event){

                console.log('clickDate');
                // console.log(this.categories);
                // console.log(this.events);
                // this.categories = ['Sara','Alejandro'];

                this.mi_fecha = event.date;
                this.start = event.date;
                this.type ="category";

                if (this.facultativo_id > 0 && this.facultativo == null){
                    this.facultativo_id = null;
                    this.reload_id++;
                }

            },
            clickTimeCategory(e){
                console.log('clickTimeCategory');


                if (this.action_cita == 'E' || this.action_cita == 'A'){
                    var idx = this.facultativos.map(x => x.text).indexOf(e.category.categoryName);

                    this.cita.facultativo_id = this.facultativos[idx].value;

                    if (this.action_cita == 'A')
                        this.$refs.componentAddCita.loadHoras();
                    if (this.action_cita == 'E')
                        this.$refs.componentEditCita.loadHoras();

                }


            },
            addDayCategory(e){
                console.log('addDayCategory '+this.reload_id);

                // console.log('addDayCategory');
                if (this.action_cita == 'E' || this.action_cita == 'A'){



                    if (this.cita.tune){
                        this.cita.fecha = e.date;
                        if (this.action_cita == 'E')
                            this.cita.hora = e.time;
                    }else{

                        this.cita.fecha = e.date;
                        if (e.time.substr(3,2) <= 30)
                            this.cita.hora  = e.time.substr(0,2)+":00";
                        else
                            this.cita.hora  = e.time.substr(0,2)+":30";

                        if (this.action_cita == 'A')
                            this.$refs.componentAddCita.loadHoras();
                        if (this.action_cita == 'E')
                            this.$refs.componentEditCita.loadHoras();


                    }

                    //console.log(this.cita);

                    //this.mi_fecha = this.cita.fecha;

                    this.reload_id++;

                }
            },
            getEvents ({ start, end }) {

                const min = new Date(`${start.date}T00:00:00`)
                const max = new Date(`${end.date}T23:59:59`)
                const days = (max.getTime() - min.getTime()) / 86400000
                const eventCount = 10;

                const secondTimestamp = 108000;

                this.loadEvents();



            },
            loadEvents(){
                console.log('loadEvents');

                const events = []

                this.citas.forEach(function(cita) {


                    events.push({
                        id: cita.id,
                        name: cita.nomape,
                        start: new Date(cita.start).getTime(),
                        end: new Date(cita.end).getTime(),
                    //    color: cita.estado_id == 4 ? 'gray' : cita.color,
                        color_original: cita.estado_id == 4 ? '#EF5350' : cita.color,
                        category: cita.facultativo,
                        timed: true,
                        bono: cita.bono,
                        tratamiento: cita.tratamiento,
                        paciente_id: cita.paciente_id,
                        estado_id: cita.estado_id,
                        importe: cita.importe,
                        notas: cita.notas,
                    })


                });


                this.festivos.forEach(function(festivo) {

                    events.push({
                        id: 0,
                        name: 'FESTIVO',
                        start: festivo +' 09:00:00',
                        end: festivo +' 21:00:00',
                        color: '#ECEFF1',
                        category: null,
                        timed: true,
                        bono: null,
                        tratamiento: null,
                        paciente_id: null,
                        estado_id: null,
                        importe: null,
                        notas: null
                    });

                });

                 this.bloqueos.forEach(function(bloqueo) {

                    //  continue;
                    //  if (this.category != null){
                    //      if (bloqueo.facultativo != this.category)
                    //  }


                    events.push({
                        id: bloqueo.id,
                        name: bloqueo.motivo,
                        start: new Date(bloqueo.start).getTime(),
                        end: new Date(bloqueo.end).getTime(),
                        color: bloqueo.color,
                        color_original: bloqueo.color,
                        category: bloqueo.facultativo,
                        timed: true,
                        bono: null,
                        tratamiento: null,
                        paciente_id: null,
                        estado_id: null,
                        importe: 0,
                        notas: null,
                    })


                });




                this.events = events

            },
            getEventColor (event) {

                if (event.paciente_id == null) return;

                if (event.paciente_id == this.event_active.paciente_id && this.event_active.color_original == event.color_original){


                   // return event.estado_id == 4 ? this.LightenDarkenColor('#EF5350') : '#CCFF90';

                    return event.estado_id == 4 ? this.LightenDarkenColor('#EF5350') : this.LightenDarkenColor(this.event_active.color_original);
                }
                else{

                    // if (event.paciente_id == 7470){

                    //     console.log(event.color_original+" co: "+event.color);
                    // }

                    return event.color_original
                }
            },
            clickCategory(category){
                // const index = this.facultativos.map(x => x.text).indexOf(category.category);

                // if (this.action_cita == 'E' || this.action_cita == 'A'){
                //     this.cita.facultativo_id = this.facultativos[index].value;
                // }else{
                //     this.type = 'custom-daily';
                //     this.facultativo_id = this.facultativos[index].value;
                //     this.reloadFisAct();
                // }

                //verificar esto porque no va.

                console.log("category.category");
            },
            goAddCita(){
                console.log('addcita');
                this.cita.fecha = this.mi_fecha;
                this.cita.paciente_id = this.paciente_id;
                this.cita.notas = null;

                this.show_loading =true;
                this.action_cita='A';

            },
            goPaciente(){
                this.show_loading =true;
                this.$router.push({ name: 'paciente.edit', params: { id: this.paciente_id } })
            },
            goAddPaciente(){
                this.show_loading =true;
                this.$router.push({ name: 'paciente.create' })
            },
            goUltimas(){
                this.cita.paciente_id = this.paciente_id;
                this.dialog_ultimas=true;
            },
            goBonos(){
                if (this.numero_bono > 0){
                    this.cita.bono = this.numero_bono;
                    this.dialog_bono = true;
                }
            },
            goTratadoAll(){

                this.show_loading = true;
                axios.put('/citas/tratado')
                    .then(res => {
                        this.reload_id++;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            setType(type){
                this.type = type;
                if (this.type =="category"){
                    if (this.facultativo == null)
                        this.facultativo_id = null;
                    else
                        this.facultativo_id = this.facultativo;

                    this.reload_id++;
                }
            },
            setActiva(st){
                this.activa = st;
                this.reloadFisAct();
            },
            LightenDarkenColor(color,percent=-20){

                    var R = parseInt(color.substring(1,3),16);
                    var G = parseInt(color.substring(3,5),16);
                    var B = parseInt(color.substring(5,7),16);

                    R = parseInt(R * (100 + percent) / 100);
                    G = parseInt(G * (100 + percent) / 100);
                    B = parseInt(B * (100 + percent) / 100);

                    R = (R<255)?R:255;
                    G = (G<255)?G:255;
                    B = (B<255)?B:255;

                    var RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
                    var GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
                    var BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));

                    return "#"+RR+GG+BB;

            },
        }

  }
</script>
