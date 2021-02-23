
<template>

    <div>
        <v-data-table
            :options="options"
            :headers="headers"
            :items="pacbonos"
        >
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    @click="editItem(item.id)"
                >
                    mdi-pencil
                </v-icon>
            </template>
            <template v-slot:item.fecha="{ item }">
                <span v-if="item.caducado" class="grey--text tachado">{{ formatDate(item.fecha)}}</span>
                <span v-else>{{ formatDate(item.fecha)}}</span>
            </template>
            <template v-slot:item.caducidad="{ item }">
                <span v-if="item.caducado" class="red--text">Caducado</span>
                <span v-else>{{ item.caducidad}}</span>
            </template>
            <template v-slot:item.importe="{ item }">
                <span v-if="hasPrecios">{{ getCurrencyFormat(item.importe) }}</span>
            </template>
            <template v-slot:item.username="{ item }">
                <span v-if="isSupervisor">{{ item.username + " " + formatDateTime(item.updated_at)}}</span>
                <span v-else>{{formatDateTime(item.updated_at)}}</span>
            </template>
        </v-data-table>
    </div>

</template>
<script>
import moment from 'moment'
import {mapGetters} from 'vuex'
  export default {
    $_veeValidate: {
        validator: 'new'
    },
    props:{
        pacbonos: Array
    },
    data () {
      return {
        options:{
            itemsPerPage: 10,
            sortDesc: [true],
            sortBy: ['fecha'],
        },
        headers: [
            {
                text: 'Fecha',
                align: 'left',
                value: 'fecha',
            },
            {
                text: 'Bono',
                align: 'left',
                value: 'bono',
            },
            {
                text: 'Tratamiento',
                align: 'left',
                value: 'tratamiento.nombre',
            },
            {
                text: 'Sesiones',
                align: 'left',
                value: 'sesiones',
            },
            {
                text: 'Caducidad',
                align: 'left',
                value: 'caducidad',
            },
            {
                text: 'Importe',
                align: 'left',
                value: 'importe',
            },
            {
                text: 'Texto',
                align: 'left',
                value: 'texto',
            },
            {
                text: 'Um',
                align: 'left',
                value: 'username',
            },
            {
                text: 'Acciones',
                align: 'Center',
                value: 'actions',
            }
        ],
        items:[],
        ruta: "pacbono"
      }
    },
    mounted()
    {

        //console.log('historia');
        // axios.get(this.url)
        //     .then(res => {

        //         this.items = res.data;

        //     })
        //     .catch(err =>{

        //         this.$toast.error(err.response.data.message);
        //         this.$router.push({ name: 'dash' })
        //     })
        //     .finally(()=>{
        //         this.show_loading = false;
        //     });
    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'isSupervisor',
            'hasPrecios'
        ])
    },
    methods:{
        getCurrencyFormat(value){
            return new Intl.NumberFormat("de-DE",{style: "currency", currency: "EUR",minimumFractionDigits:2}).format(parseFloat(value))
        },
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');

            return moment(f).format('DD/MM/YYYY');

        },
        formatDateTime(f){
            if (f == null) return null;
            moment.locale('es');

            return moment(f).format('DD/MM/YYYY H:mm:ss');

        },
        create(){
            this.$router.push({ name: this.ruta+'.create'})
        },
        editItem (id) {
            this.$router.push({ name: this.ruta+'.edit', params: { id: id } })
        },
    }
  }
</script>
<style scoped>
  .tachado{text-decoration:line-through;}
</style>
