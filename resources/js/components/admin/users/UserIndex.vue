<template>
    <div>
        <loading :show_loading="show_loading"></loading>
		<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card v-if="!show_loading">
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Buscar"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>

            <v-data-table
                :search="search"
                :headers="headers"
                :items="usuarios"
            >
            <template v-slot:item.roles="{ item }">
                {{ extrae(item.roles)}}
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item.id)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    @click="openDialog(item.id)"
                >
                    mdi-delete
                </v-icon>
            </template>
            <v-data-footer>
                <template v-slot:page-text="pagetext">
                  {{pagetext}}sss
                </template>
            </v-data-footer>
                <!-- <template slot="items" slot-scope="props">
                    <td  v-if="props.item.blocked == false"  class="text-xs-left">{{ props.item.name + " " + props.item.lastname }}</td>
                    <td v-else class="text-xs-left"><span class="red--text">BLOQUEADO -></span></td>
                    <td class="text-xs-left">{{ props.item.username }}</td>
                    <td>{{ props.item.email }}</td>
                    <td class="text-xs-left">{{ formatDate(props.item.login_at) }}</td>
                    <td class="text-xs-left">-</td>
                    <td class="justify-center">

                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(props.item.id)"
                        >
                            mdi-lead-pencil
                        </v-icon>


                        <v-icon
                            v-if="isRoot"
                            small
                            @click="openDialog(props.item.id)"
                            >
                            mdi-delete
                        </v-icon>
                    </td>
                </template> -->



            </v-data-table>
        </v-card>
    </div>
</template>
<script>
import moment from 'moment';
import {mapGetters} from 'vuex';
import MyDialog from '@/components/shared/MyDialog'
import MenuOpe from './MenuOpe'
import Loading from '@/components/shared/Loading'
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Usuarios",
        show_loading: true,
        search:"",

        headers: [
          {
            text: 'Nombre',
            align: 'left',
            value: 'name'
          },
          { text: 'Username', align: 'left', value: 'username' },
           {
            text: 'Mail',
            align: 'left',
            value: 'email'
          },
          { text: 'Login', align: 'left', value: 'login_at' },
          { text: 'Roles', align: 'left', value: 'roles', sortable: false, },
          { text: 'Acciones', align: 'left', value: 'actions', sortable: false, }
        ],
        usuarios:[],
        status: false,
		registros: false,
        dialog: false,
        user_id: 0
      }
    },
    mounted()
    {

        axios.get('/admin/users')
            .then(res => {

                this.usuarios = res.data;
                this.registros = true;
            })
            .catch(err =>{
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
            .finally(()=> {
                this.show_loading = false;
            });
    },
    computed: {
        ...mapGetters([
	    	'isRoot'
        ]),
    },
    methods:{
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY HH:mm:ss');
        },
        create(){
            this.$router.push({ name: 'users_create', params: { id: '0' } })
        },
        extrae(role){

            var i=0;
            var roles = "";
            role.forEach(function(element) {
            roles += (element.name);
            ++i;
            if (i < role.length)
                roles+=", ";
            });

            return roles;

        },
        editItem (id) {
            this.$router.push({ name: 'users.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.user_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/users/'+this.user_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Usuario borrado correctamente');
                    this.usuarios = response.data;
                }
                })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
  }
</script>

<style scoped>
  .tachado{text-decoration:line-through;}
</style>
