<template>
	<div>
        <loading :show_loading="loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="user.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card v-show="!loading">
            <v-form>
                <v-container>
                    <v-row>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="user.name"
                                v-validate="'required'"
                                :error-messages="errors.collect('name')"
                                label="Nombre"
                                data-vv-name="name"
                                data-vv-as="nombre"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="user.lastname"
                                :error-messages="errors.collect('lastname')"
                                label="Apellidos"
                                data-vv-name="lastname"
                                data-vv-as="apellidos"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="user.username"
                                v-validate="'required|min:6'"
                                :counter="20"
                                :error-messages="errors.collect('username')"
                                label="Usuario"
                                data-vv-name="username"
                                data-vv-as="usuario"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="3"
                        >
                            <v-text-field
                                v-model="user.email"
                                v-validate="'required_if:id,1,2|email'"
                                :error-messages="errors.collect('email')"
                                label="email"
                                data-vv-name="email"
                                v-on:keyup.enter="submit"
                                required
                            >
                            </v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="2"
                        >
                            <v-btn @click="submit"  rounded  :loading="loading" small text color="primary">
                                Guardar Usuario
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
    import {mapGetters} from 'vuex';
    import MenuOpe from './MenuOpe'
    import Loading from '@/components/shared/Loading'

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
                user: {
                    id:       0,
                    name:     "",
                    lastname: "",
                    username: "",
                    email:    "",
                    blocked_at:"",
                    blocked:  "",
                    updated_at:"",
                    created_at:"",
                },

                titulo:   "Crear Usuario",

                loading: false,

      		}
        },
        mounted(){

            // if (!this.isRoot){
            //     this.$toast.error('No autorizado');
            // }

            axios.get('/admin/users/create')
                .then(res => {

                })
                .catch(err => {
                     if (err.response.status != 401){
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'users.index'});
                        }
                })
        },
        computed: {
            ...mapGetters([
	    		'isRoot'
    		]),
            computedDateFormat() {
                moment.locale('es');
                return this.user.blocked_at ? moment(this.user.blocked_at).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.user.updated_at ? moment(this.user.updated_at).format('D/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.user.created_at ? moment(this.user.created_at).format('D/MM/YYYY H:mm') : '';
            },

        },
    	methods:{
            submit() {
                if (this.loading === false){
                    this.loading = true;

                    var url = "/admin/users";

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.post(url, this.user)
                                .then(response => {

                                    this.$router.push({ name: 'users.edit', params: { id: response.data.user.id } })
                                    this.loading = false;
                                })
                                .catch(err => {
                                        const msg_valid = err.response.data.errors;
                                        for (const prop in msg_valid) {
                                            this.errors.add({
                                                field: prop,
                                                msg: `${msg_valid[prop]}`
                                            })
                                        }
                                    this.loading = false;
                                });
                            }
                        else{
                            this.loading = false;
                        }
                    });
                }

            },
            clearDate(){
                this.user.blocked_at = null;
            }

    }
  }
</script>
