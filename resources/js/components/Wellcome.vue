<template>
<div id="app">
  <v-app>
    <v-app-bar
      app
      color="white"
      height="60"
    >

        <v-toolbar-title class="font-weight-black headline teal--text accent-2">
            <v-card flat tile class="d-flex">

            </v-card>
        </v-toolbar-title>
        <v-spacer />
        <!-- <v-btn text small @click="$vuetify.goTo('#features')">Tratamientos</v-btn>
        <v-btn text small @click="$vuetify.goTo('#contact')">Contacto</v-btn> -->
        <v-btn v-if="!isLoggedIn" icon v-on:click="login">
            <v-icon>mdi-account</v-icon>
        </v-btn>
        <v-btn v-else icon :to="{name: 'dash'}">
            <v-icon>desktop_windows</v-icon>
        </v-btn>

    </v-app-bar>
    <v-main>
        <v-container fluid>
            <v-row>
                <v-col cols=12 md="4" offset-md="4">
                    <v-img src="assets/logo.jpg"></v-img>
                </v-col>
            </v-row>
            <!-- <router-view :key="$route.fullPath"></router-view> -->

        </v-container>
    </v-main>

    <v-footer
      class="justify-center"
      color="#292929"
      height="100"
    >




      <div class="caption font-weight-light grey--text text--lighten-1 text-center">
        &copy; {{ (new Date()).getFullYear() }} — Centro de fisioterapia Sanaval. Av de Betanzos, 10 Posterior 28029 Madrid. Tf. 91 739 87 08.
      </div>
    </v-footer>
  </v-app>
 </div>
</template>
<script>
import {mapGetters} from 'vuex';
	export default {
        computed:{
            ...mapGetters([
				'isLoggedIn'
            ]),
            computedColorTitulo(){
                if (this.empresa.img1 == null)
                    return "black--text";
                else
                    return "white--text";
            }
        },
        data: () => ({
            bottomNav: 'recent',
            alert: true,
            legal: false,
            empresa: {},
            parametros:{},

        }),
        beforeMount(){
            axios.get('api/param')
                .then(res => {
                    this.parametros = res.data.parametros;

                })
                 .catch(err => {
                    this.$toast.error("Fallo en reload empresa...");
                })
                 .finally(()=> {
                        this.show_loading = false;
                });
        },
         methods:{
            login(){
                window.location = '/login';
            },
        }
	}
</script>
