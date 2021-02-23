<template>
    <v-container>
        <v-row>
            <v-col cols=2 offset-md="4">
                 <v-btn rounded block large color="grey" class="blue-grey lighten-3" @click="goCitas()">
                     Citas
                     <v-icon right dark>mdi-calendar</v-icon>
                </v-btn>
            </v-col>
            <v-col cols=2>
                 <v-btn rounded block large color="grey" class="blue-grey lighten-3" @click="goListas()">
                     Listas
                     <v-icon right dark>mdi-printer</v-icon>
                </v-btn>
            </v-col>
        </v-row>
        <v-row v-show="isAdmin">
            <v-col cols=2 offset-md="3">
                 <v-btn v-show="hasContact" rounded block large color="grey" class="blue-grey lighten-3" @click="goPacientes()">
                     Pacientes
                     <v-icon right dark>mdi-account-multiple</v-icon>
                </v-btn>
            </v-col>
            <v-col cols=2>
                 <v-btn rounded block large color="grey" class="blue-grey lighten-3" @click="goCaja()">
                     Caja
                     <v-icon right dark>mdi-cash</v-icon>
                </v-btn>
            </v-col>
            <v-col cols=2>
                 <v-btn rounded block large color="grey" class="blue-grey lighten-3" @click="goFacturas()">
                     Facturas
                     <v-icon right dark>mdi-desktop-mac-dashboard</v-icon>
                </v-btn>
            </v-col>
        </v-row>
        <v-row v-if="logo!=null">
            <v-col cols=12 md="4" offset-md="4">
                <v-img class="img-fluid" :src="logo"></v-img>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="4" offset-md="4">

            </v-col>

        </v-row>
    </v-container>
</template>
<script>
import {mapActions} from "vuex";
import {mapGetters} from 'vuex';
export default {
    computed:{
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin',
            'empresaActiva',
            'imgFondo',
            'hasContact'
		]),
    },
    data: () => ({
        logo: "",
    }),
    mounted(){

        axios.get('/dash')
            .then(res => {

                this.setAuthUser(res.data.user);

                //this.logo = "/storage/logos/"+this.empresaActiva+".png";
                this.logo = this.imgFondo;

            })
            .catch(err => {
                console.log(err);
        })

    },
    watch: {
        empresaActiva: function (newValue) {

            //this.logo = "/storage/logos/"+newValue+".png";
            this.logo = this.imgFondo;
        }
    },
    methods:{
        ...mapActions([
				'setAuthUser'
            ]),
        goPacientes(){
            this.$router.push({ name: 'paciente.index' })
        },
        goCitas(){
            this.$router.push({ name: 'cita.index' })
        },
        goListas(){
            this.$router.push({ name: 'citas.listas' })
        },
        goFacturas(){
            this.$router.push({ name: 'factura.index' })
        },
        goCaja(){
            this.$router.push({ name: 'caja.index' })
        },
    },

  }
</script>
