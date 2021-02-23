<template>
	<div>
        <loading :show_loading="loading"></loading>
        <my-dialog :dialog.sync="dialog" registro="avatar" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="empresa.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-tabs fixed-tabs>
                <v-tab>
                        Datos generales
                </v-tab>
                <v-tab>
                        Logos
                </v-tab>
                <v-tab>
                        Permisos
                </v-tab>
                <v-tab-item>
                    <v-form>
                        <v-container>
                            <v-row>
                                <v-col
                                    cols="12"
                                    md="3"
                                >
                                    <v-text-field
                                        v-model="empresa.nombre"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('name')"
                                        label="Nombre"
                                        data-vv-name="name"
                                        data-vv-as="nombre"
                                        v-on:keyup.enter="submit"
                                        required
                                        autofocus
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="3"
                                >
                                    <v-text-field
                                        v-model="empresa.razon"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('razon')"
                                        label="Razon"
                                        data-vv-name="razon"
                                        required
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-text-field
                                        v-model="empresa.cif"
                                        :error-messages="errors.collect('cif')"
                                        label="CIF"
                                        data-vv-name="cif"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-text-field
                                        v-model="empresa.telefono1"
                                        :error-messages="errors.collect('telefono1')"
                                        label="Telefono"
                                        data-vv-name="telefono1"
                                        data-vv-as="Teléfono"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="2"
                                >
                                    <v-text-field
                                        v-model="empresa.telefono2"
                                        :error-messages="errors.collect('telefono2')"
                                        label="Telefono"
                                        data-vv-name="telefono2"
                                        data-vv-as="Teléfono"
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
                                        v-model="empresa.direccion"
                                        :error-messages="errors.collect('direccion')"
                                        label="Dirección"
                                        data-vv-name="direccion"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                </v-col>
                                    <v-col cols="12" md="1">
                                        <v-text-field
                                            v-model="empresa.cpostal"
                                            :error-messages="errors.collect('cpostal')"
                                            label="CP"
                                            data-vv-name="CP"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field
                                            v-model="empresa.poblacion"
                                            :error-messages="errors.collect('poblacion')"
                                            label="Población"
                                            data-vv-name="poblacion"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field
                                            v-model="empresa.provincia"
                                            :error-messages="errors.collect('provincia')"
                                            label="Provincia"
                                            data-vv-name="provincia"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-model="empresa.sigla"
                                            :error-messages="errors.collect('sigla')"
                                            label="Sigla"
                                            data-vv-name="sigla"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="3">
                                        <v-text-field
                                            v-model="empresa.titulo"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('titulo')"
                                            label="Tíitulo"
                                            data-vv-name="titulo"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field
                                            v-model="empresa.contacto"
                                            :error-messages="errors.collect('contacto')"
                                            label="Contacto"
                                            data-vv-name="contacto"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field
                                            v-model="empresa.email"
                                            v-validate="'email'"
                                            :error-messages="errors.collect('email')"
                                            label="email"
                                            data-vv-name="email"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col  cols="12" md="3">
                                        <v-text-field
                                            v-model="empresa.web"
                                            :error-messages="errors.collect('web')"
                                            label="Web"
                                            data-vv-name="web"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="1">
                                        <v-text-field
                                            v-model="empresa.sms_usr"
                                            :error-messages="errors.collect('sms_usr')"
                                            label="SMS User"
                                            data-vv-name="sms_usr"
                                            data-vv-as="usuario"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-model="empresa.sms_password"
                                            :error-messages="errors.collect('sms_password')"
                                            label="SMS Password"
                                            data-vv-name="sms_password"
                                            data-vv-as="password"
                                            :append-icon="show_sms_passwd ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show_sms_passwd ? 'text' : 'password'"
                                            @click:append="show_sms_passwd = !show_sms_passwd"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-model="empresa.sms_licencia"
                                            :error-messages="errors.collect('sms_licencia')"
                                            label="SMS Licencia"
                                            data-vv-name="sms_licencia"
                                            data-vv-as="licencia"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-model="empresa.sms_sender"
                                            :error-messages="errors.collect('sms_sender')"
                                            label="Remite"
                                            data-vv-name="sms_sender"
                                            data-vv-as="usuario"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="1">
                                        <v-text-field
                                            v-model="empresa.sms_pais"
                                            :error-messages="errors.collect('sms_pais')"
                                            label="País"
                                            data-vv-name="sms_pais"
                                            data-vv-as="pais"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-menu
                                            ref="menu"
                                            v-model="menu_am"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            :return-value.sync="empresa.sms_am"
                                            transition="scale-transition"
                                            offset-y
                                            max-width="190px"
                                            min-width="190px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="empresa.sms_am"
                                                label="Envío mañanas"
                                                append-icon="mdi-clock-outline"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                            </template>
                                            <v-time-picker
                                            v-if="menu_am"
                                            v-model="empresa.sms_am"
                                            full-width
                                            @click:minute="$refs.menu.save(empresa.sms_am)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-menu
                                            ref="menu2"
                                            v-model="menu_pm"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            :return-value.sync="empresa.sms_pm"
                                            transition="scale-transition"
                                            offset-y
                                            max-width="190px"
                                            min-width="190px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="empresa.sms_pm"
                                                label="Envío tardes"
                                                append-icon="mdi-clock-outline"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                            </template>
                                            <v-time-picker
                                            v-if="menu_pm"
                                            v-model="empresa.sms_pm"
                                            full-width
                                            @click:minute="$refs.menu2.save(empresa.sms_pm)"
                                            ></v-time-picker>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="empresa.ult_bono"
                                            v-validate="'required|numeric'"
                                            :error-messages="errors.collect('ult_bono')"                                            d
                                            label="Último bono"
                                            data-vv-name="ult_bono"
                                            data-vv-as="bono"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-text-field
                                            v-model="empresa.ccc_ss"
                                            v-validate="'max:30'"
                                            :error-messages="errors.collect('ccc_ss')"                                            d
                                            label="Cod. Cuenta SS"
                                            data-vv-name="ccc_ss"
                                            data-vv-as="código"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-text-field
                                            v-model="empresa.disco"
                                            v-validate="'required|alpha'"
                                            :error-messages="errors.collect('disco')"                                            d
                                            label="Disco Adjuntos"
                                            data-vv-name="disco"
                                            data-vv-as="disco"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                    <v-switch
                                        label="TPV PC"
                                        v-model="empresa.tpv"
                                        color="primary"
                                    ></v-switch>
                                </v-col>
                                 </v-row>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-text-field
                                            v-model="empresa.txtpie1"
                                            :error-messages="errors.collect('txtpie1')"
                                            label="Pie"
                                            data-vv-name="txtpie1"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-text-field
                                            v-model="empresa.txtpie2"
                                            :error-messages="errors.collect('txtpie2')"
                                            label="Pie"
                                            data-vv-name="txtpie2"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-model="empresa.username"
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
                                    <v-col cols="12" md="4"></v-col>
                                    <v-col cols="12" md="2">
                                        <div class="text-xs-center">
                                                    <v-btn @click="submit"  rounded  :loading="loading" small color="primary">
                                            Guardar
                                            </v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-tab-item>
                    <v-tab-item>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="2" class="font-weight-black text-xs-center"><p>{{empresa.nombre}}</p></v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="2"></v-col>
                                <v-col cols="12" md="3" v-if="empresa.img_logo==null">
                                    <vue-dropzone
                                            ref="myVueDropzone"
                                            id="dropzone"
                                            :options="dropzoneOptions"
                                            v-on:vdropzone-success="uploadLogo"
                                    ></vue-dropzone>
                                </v-col>
                                <v-col cols="12" md="2" v-else>
                                    <v-img class="img-fluid" :src="empresa.img_logo"></v-img>
                                    <v-btn @click="borraLogo" flat round><v-icon color="red darken-4">mdi-delete</v-icon> Eliminar Logo</v-btn>
                                </v-col>
                                <v-col cols="12" md="1"></v-col>
                                <v-col cols="12" md="3" v-if="empresa.img_fondo==null">
                                    <vue-dropzone
                                            ref="myVueDropzone2"
                                            id="dropzone2"
                                            :options="dropzoneOptions2"
                                            v-on:vdropzone-success="uploadFondo"
                                    ></vue-dropzone>
                                </v-col>
                                <v-col cols="12" md="3" v-else>
                                    <v-img class="img-fluid" :src="empresa.img_fondo"></v-img>
                                    <v-btn @click="borraFondo" flat round><v-icon color="red darken-4">mdi-delete</v-icon> Eliminar Fondo</v-btn>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tab-item>
                    <v-tab-item>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="2" class="font-weight-black text-xs-center"><p>{{empresa.nombre}}</p></v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Activa"
                                        v-model="sw[0]"
                                        color="primary"
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Compras"
                                        v-model="sw[1]"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Ventas"
                                        v-model="sw[2]"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Nuevas Compras"
                                        v-model="sw[3]"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Nuevas Ventas"
                                        v-model="sw[4]"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Proveedora Efectivo"
                                        v-model="sw[5]"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-switch
                                        label="Bloquear Facturación"
                                        v-model="sw[6]"
                                        color="primary">
                                    ></v-switch>
                                </v-col>
                            </v-row>
                            <v-row>
                                   <v-col cols="12" md="5"></v-col>
                                   <v-col cols="12" md="2">
                                        <div class="text-xs-center">
                                                    <v-btn @click="submit"  rounded small :loading="loading" color="primary">
                                            Guardar
                                            </v-btn>
                                        </div>
                                    </v-col>
                            </v-row>
                        </v-container>
                    </v-tab-item>
                </v-tabs>

            </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MyDialog from '@/components/shared/MyDialog'
import MenuOpe from './MenuOpe'
import vue2Dropzone from 'vue2-dropzone'
import {mapGetters} from 'vuex';
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'vueDropzone': vue2Dropzone,
            'my-dialog': MyDialog,
            'loading': Loading
		},
    	data () {
      		return {
                titulo:"Empresas",
                empresa: {},
                dialog:false,
                show_sms_passwd:false,
                sw:[] ,
                almacenes:[],
                empresas:[],
                empresa_id: "",

        		status: false,
                loading: true,
                menu_am: false,
                menu_pm: false,

                show: false,
                menu: false,
                dropzoneOptions: {
                    url: '/admin/empresas/'+this.$route.params.id+'/logo',
                    paramName: 'logo',
                    acceptedFiles: '.jpg,jpeg,.png',
                    thumbnailWidth: 150,
                    maxFiles: 1,
                    maxFilesize: 2,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra la imagen LOGOTIPO aquí'
                },
                dropzoneOptions2: {
                    url: '/admin/empresas/'+this.$route.params.id+'/fondo',
                    paramName: 'logo',
                    acceptedFiles: 'image/*',
                    thumbnailWidth: 150,
                    maxFiles: 1,
                    maxFilesize: 2,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra la imagen FONDO PANTALLA aquí'
                }

      		}
        },
        mounted(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get('/admin/empresas/'+id+'/edit')
                    .then(res => {

                        this.empresa = res.data.empresa;

                        this.almacenes = res.data.almacenes;
                        this.empresas = res.data.empresas;

                        // var a  = Array.from(this.empresa.flags);

                        // this.sw = a.map(function (x) {
                        //     return parseInt(x, 10);
                        // });
                        this.getFlags(this.empresa.flags);

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'empresa.index'})
                    })
                    .finally(()=> {
                        this.loading = false;
                    });
        },
        computed: {
            ...mapGetters([
                'isRoot'
            ]),
            computedDateFormat() {
                moment.locale('es');
                return this.empresa.scan_doc ? moment(this.empresa.scan_doc).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.empresa.updated_at ? moment(this.empresa.updated_at).format('D/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.empresa.created_at ? moment(this.empresa.created_at).format('D/MM/YYYY H:mm') : '';
            }

        },
    	methods:{
            getFlags(flags){

                    var a  = Array.from(flags);
                    this.sw = a.map(function (x) {
                        return parseInt(x);
                    });

            },
            setFlags(){

                var a = this.sw.toString();

                a = a.replace(/false/g,'0');
                a = a.replace(/true/g,'1');
                a = a.replace(/,/g,'');

                this.empresa.flags = a;
            },
            clearDate(){
                this.empresa.scan_doc = null;
            },
            uploadLogo(file, response){
                this.empresa = response.empresa;
            },
            uploadFondo(file, response){
                this.empresa = response.empresa;
            },
            borraLogo(){
                axios.put('/admin/empresas/'+this.empresa.id+'/logo/delete')
                    .then(response => {
                        this.empresa = response.data.empresa;
                        this.loading = false;
                    })
            },
            borraFondo(){
                axios.put('/admin/empresas/'+this.empresa.id+'/fondo/delete')
                    .then(response => {
                        this.empresa = response.data.empresa;
                        this.loading = false;
                    })
            },
            destroyReg(){
                //  axios({
                //     method: 'delete',
                //     url: '/admin/avatars/'+this.user.id+'/delete',
                //     })
                //     .then(response => {
                //         this.user.avatar = "#";
                //         this.$toast.success(response.data.msg);
                //     })
                //     .catch(err => {
                //         this.$toast.error(err.response.data);
                //     });
            },
            submit() {
                if (this.loading === false){

                    this.loading = true;
                    this.setFlags();

                    var url = "/admin/empresas/"+this.empresa.id;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                            axios.put(url, this.empresa)
                                .then(response => {
                                    this.$toast.success(response.data.message);
                                    this.empresa = response.data.empresa;
                                    this.loading = false;
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

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}

.v-form>.container>.layout>.col {
    padding: 1px 8px 1px 8px;
}
.v-text-field {
    padding-top: 6px;
    margin-top: 2px;
}
</style>
