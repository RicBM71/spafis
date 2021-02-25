<template>
    <v-app id="inspire">
        <loading :show_loading="show_loading"></loading>
        <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            app
        >
        <v-list dense>
            <template v-for="item in items">
            <v-row
                v-if="item.heading"
                :key="item.heading"
                align="center"
            >
                <v-col cols="6">
                <v-subheader v-if="item.heading">
                    {{ item.heading }}
                </v-subheader>
                </v-col>
                <v-col
                cols="6"
                class="text-center"
                >
                <a
                    href="#!"
                    class="body-2 black--text"
                >EDIT</a>
                </v-col>
            </v-row>
            <v-list-group
                v-else-if="item.children"
                :key="item.text"
                v-model="item.model"
                :prepend-icon="item.model ? item.icon : item['icon-alt']"
                append-icon=""
            >
                <template v-slot:activator>
                <v-list-item-content>
                    <v-list-item-title>
                    {{ item.text }}
                    </v-list-item-title>
                </v-list-item-content>
                </template>
                <v-list-item
                v-for="(child, i) in item.children"
                :key="i"
                link
                >
                <v-list-item-action v-if="child.icon">
                    <v-icon>{{ child.icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-content @click="abrir(child.name)">
                    <v-list-item-title>
                    {{ child.text }}
                    </v-list-item-title>
                </v-list-item-content>
                </v-list-item>
            </v-list-group>
            <v-list-item
                v-else
                :key="item.text"
                link
            >
                <v-list-item-action>
                <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-content @click="abrir(item.name)">
                <v-list-item-title>
                    {{ item.text }}
                </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            </template>
        </v-list>
        </v-navigation-drawer>

        <v-app-bar
        dense
        :clipped-left="$vuetify.breakpoint.lgAndUp"
        app
        color="blue darken-3"
        dark
        >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title
            style="width: 300px"
            class="ml-0 pl-4"
        >
            <span class="hidden-sm-and-down">Sanaval</span>
        </v-toolbar-title>
        <v-spacer />
        <v-tooltip bottom v-if="isSupervisor">
            <template v-slot:activator="{ on }">
                <v-btn v-on="on" icon v-show="sms.sms > 0" @click="goSendSMS">
                    <v-icon color="yellow darken-2">mdi-message-alert-outline</v-icon>
                </v-btn>
            </template>
            <span>({{sms.sms}}) SMS'S pendientes de envio.</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn v-on="on" icon v-show="jobs > 0">
                    <v-icon color="yellow darken-2">mdi-email-alert</v-icon>
                </v-btn>
            </template>
            <span>({{jobs}}) Mails pendientes de envio.</span>
        </v-tooltip>

        <!-- <v-btn icon v-on:click="empresa">
            <v-icon>mdi-briefcase</v-icon>
        </v-btn> -->
        <v-progress-circular
            v-if="objetivo > 0"
            :rotate="360"
            :value="objetivo"
            color="white"
            >
            <div v-if="objetivo==100" class="objetivo">OK</div>
            <div v-else class="objetivo">{{ objetivo }}%</div>
        </v-progress-circular>
        <span>&nbsp;</span>
        <v-btn icon v-on:click="home">
            <v-icon >mdi-home</v-icon>
        </v-btn>
        <v-btn icon v-on:click="passwd">
            <v-avatar v-if="user.avatar !='#'" size="32px">
                <img class="img-fluid" :src="user.avatar">
            </v-avatar>
            <v-icon v-else>mdi-settings</v-icon>
        </v-btn>
        <strong v-html="user.name"></strong>
        <v-btn icon v-on:click="Logout">
            <v-avatar size="32px" tile>
                <v-icon>mdi-exit-to-app</v-icon>
            </v-avatar>
        </v-btn>

        </v-app-bar>
        <v-main>
        <v-container
            class="pa-0"
            fluid
        >

              <router-view :key="$route.fullPath"></router-view>


        </v-container>
        </v-main>
         <!-- <v-navigation-drawer
            v-model="drawer"
            fixed
            right

        ></v-navigation-drawer> -->
    </v-app>
</template>
<script>
import {mapActions} from "vuex";
import {mapState} from 'vuex'
import {mapGetters} from 'vuex';
import Loading from '@/components/shared/Loading'
export default {
    components: {
        'loading': Loading,
    },
    computed:{
        ...mapState({
            user: state => state.auth
        }),
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin',
            'isSupervisor',
		]),
    },
    data: () => ({
        right:true,
        menu: true,
        dialog: false,
        drawer: false,
        show: true,

        empresaTxt:"...",
        empresas:[],
        myEmpresa:false,
        empresa_id:0,
        jobs: 0,
        sms: {sms: 0, fecha: null},
        show_loading:  false,

        items: [
            { icon: 'mdi-contacts', text: 'Pacientes', name: 'paciente.index' },
            { icon: 'mdi-account-tie-outline', text: 'Facultativos', name: 'facultativo.index' },
            { icon: 'mdi-calendar', text: 'Bloqueos', name: 'bloqueo.index' },
            {
            icon: 'mdi-chevron-up',
            'icon-alt': 'mdi-chevron-down',
            text: 'Mantenimmientos',
            model: false,
            children: [
                { icon: 'mdi-plus', text: 'Areas', name:'area.index'  },
                { icon: 'mdi-plus', text: 'Caja', name:'caja.index'  },
                { icon: 'mdi-plus', text: 'Cuentas', name:'cuenta.index'  },
                { icon: 'mdi-plus', text: 'Bonos', name:'bono.index' },
                { icon: 'mdi-plus', text: 'Tratamientos', name:'tratamiento.index'  },
                { icon: 'mdi-plus', text: 'Sociedades', name:'mutua.index'  },
                { icon: 'mdi-plus', text: 'Tipos de IVA', name:'iva.index'  },
            ],
            },
            {
            icon: 'mdi-chevron-up',
            'icon-alt': 'mdi-chevron-down',
            text: 'Consultas',
            model: false,
            children: [
                { icon: 'mdi-plus', text: 'Cobros Año/Mes', name: 'cobrosmes.index' },
                { icon: 'mdi-plus', text: 'Comparativo Mes', name: 'comparativo.index' },
            ],
            },
            {
            icon: 'mdi-chevron-up',
            'icon-alt': 'mdi-chevron-down',
            text: 'Procesos',
            model: false,
            children: [
                { icon: 'mdi-circle-medium', text: 'Facturación', name: 'facturacion.index' },
            ],
            },
            {
            icon: 'mdi-chevron-up',
            'icon-alt': 'mdi-chevron-down',
            text: 'Administración',
            model: false,
            children: [
                { icon: 'mdi-circle-medium', text: 'Empresas', name: 'empresa.index' },
                { icon: 'mdi-circle-medium', text: 'Usuarios', name: 'users.index' },
                { icon: 'mdi-circle-medium', text: 'Festivos', name: 'festivo.index' },
                { icon: 'mdi-circle-medium', text: 'Parametros', name: 'parametros.index' },
            ],
            },

        ],
        mn_root:{
            icon: 'mdi-chevron-up',
            'icon-alt': 'mdi-chevron-down',
            text: 'Root',
            model: false,
            children: [
                { icon: 'mdi-plus', text: 'Roles', name:'roles.index'  },
            ],
            },

        expired: false,
        objetivo: 0,
    }),
    mounted(){

        axios.get('/dash')
                .then(res => {

                    this.setAuthUser(res.data.user);

                    this.items.push(this.mn_root);



                    this.empresa_id = this.user.empresa_id;


                    this.empresas = res.data.user.empresas;
                    var idx = this.empresas.map(x => x.value).indexOf(this.empresa_id);

                    // this.empresaTxt = this.empresas[idx].text;
                    //this.empresaTxt = this.user.empresa_nombre;

                    this.jobs = res.data.jobs;
                    this.sms = res.data.sms;

                    this.objetivo= res.data.sesiones;

                    //this.productos_online = res.data.productos_online;

                    // res.data.user.empresas.map((e) =>{
                    //     if (e.id == this.empresa_id)
                    //         this.empresaTxt = e.titulo;
                    //     this.empresas.push({id: e.id, name: e.titulo});
                    // })

                    this.empresas.sort(function (a, b) {
                    if (a.text > b.text) {
                        return 1;
                    }
                    if (a.text < b.text) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                    });

                    this.expired = res.data.expired;

                    if (this.expired){
                        this.$toast.error('Password ha Expirado, debe reemplazarla');
                        this.$router.push({name: 'edit.password'});
                    }

                })
                .catch(err => {

                    this.show = false;
                    if (err.request.status == 401){ // fallo de validated.
                        //this.$router.push("/login");
                        window.location = '/login';
                    }
                })

    },

    methods:{
        ...mapActions([
                'setAuthUser',
                'unsetParametros'
			]),
        abrir(name){
            //console.log(name);
            //this.drawer = false;
            this.$router.push({name: name});
        },
        home(){
            axios.get('/dash')
                .then(res => {

                    this.setAuthUser(res.data.user);

                    this.empresa_id = this.user.empresa_id;
                    this.empresas = res.data.user.empresas;
                    this.empresas.sort(function (a, b) {
                    if (a.text > b.text) {
                        return 1;
                    }
                    if (a.text < b.text) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                    });
                    //this.empresas = res.data.user.empresas.sortBy('text');
                    var idx = this.empresas.map(x => x.value).indexOf(this.empresa_id);

                    this.empresaTxt = this.empresas[idx].text;

                    this.jobs = res.data.jobs;
                    this.sms = res.data.sms;
                    this.objetivo= res.data.sesiones;

                    // res.data.user.empresas.map((e) =>{
                    //     if (e.value == this.empresa_id)
                    //         this.empresaTxt = e.text;
                    //     this.empresas.push({id: e.id, name: e.titulo});
                    // })

                    this.expired = res.data.expired;
                    if (this.expired){
                        this.$toast.error('Password ha Expirado, debe reemplazarla');
                        this.$router.push({name: 'edit.password'});
                    }

                })
                .catch(err => {
                    this.show = false;
                    if (err.request.status == 401){ // fallo de validated.
                        window.location = '/login';
                    }
                })

            this.$router.push({name: 'dash'});
        },
        passwd(){
            this.$router.push({name: 'edit.password'});
        },
        empresa(){
            this.empresa_id = this.empresaActiva;
            this.myEmpresa=true;
        },
        getReloadEmpresa(){
            this.show_loading = true;
            axios.get('/dash')
                .then(res => {

                    this.setAuthUser(res.data.user);
                    var idx = this.empresas.map(x => x.value).indexOf(this.empresa_id);
                    this.empresaTxt = this.empresas[idx].text;
                    if (this.$route.path != '/home')
                        this.$router.push({name: 'dash'});
                })
                .catch(err => {
                    this.$toast.error("Fallo en reload empresa...");
                })
                 .finally(()=> {
                        this.show_loading = false;
                });
        },
        setEmpresa(){

            this.show_loading = true;
            this.empresas.map((e) =>{
                   if (e.id == this.empresa_id)
                        this.empresaTxt = e.name;
                });

            axios({
                    method: 'put',
                    url: '/admin/users/'+this.user.id+'/empresa',
                    data:{ empresa_id: this.empresa_id }
                })
                .then(res => {
                    //this.$toast.success("Cambiando de empresa...");
                   // this.setAuthUser(res.data.user);

                    this.getReloadEmpresa();
                   // this.$router.push({name: 'dash'});
                })
                .catch(err => {
                    this.$toast.error("No se ha podido seleccionar la empresa");
                });

            this.myEmpresa=false;
        },
        goSendSMS(){
            this.$router.push({ name: 'enviar.sms', params: { fecha: this.sms.fecha } })
        },
        Logout() {
            this.$toast.success('Logout, espere...');
            axios.post('/logout').then(res => {

                this.$store.dispatch('unsetAuthUser')
                this.$router.push({name: 'index'});
			})
        },
    }
  }
</script>
<style scoped>
.objetivo{
    font-size: 10px;
}
</style>
