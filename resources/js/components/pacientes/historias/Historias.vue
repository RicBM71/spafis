
<template>

    <div>
        <v-data-table
            :options="options"
            :headers="headers"
            :items="historias"
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
                {{ formatDate(item.fecha)}}
            </template>
            <template v-slot:item.username="{ item }">
                <span v-if="isSupervisor || item.username == userName">{{ item.username + " "+formatDateTime(item.updated_at)}}</span>
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
        historias: Array
    },
    data () {
      return {
        options:{
            itemsPerPage: 5,
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
                text: 'Juicio',
                align: 'left',
                value: 'juicio',
            },
            {
                text: 'Tratamiento',
                align: 'left',
                value: 'tratamiento',
            },
            {
                text: 'Ult. mod',
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
        ruta: "historia"
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
            'userName'
        ])
    },
    methods:{
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
