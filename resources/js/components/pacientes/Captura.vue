<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            v-on="on"
                            color="white"
                            icon
                            @click="goBack()"
                        >
                            <v-icon color="primary">mdi-arrow-left</v-icon>
                        </v-btn>
                    </template>
                        <span>Volver</span>
                </v-tooltip>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>
                <v-row>
                    <v-col cols="12" md="3" v-if="img_anverso!=null"></v-col>
                    <v-col cols="12" md="3" v-if="img_anverso!=null">
                            <h3>Captura</h3>
                            <figure>
                                 <v-avatar size="256px">
                                    <v-img :src="img_anverso"/>
                                </v-avatar>
                            </figure>
                    </v-col>
                    <v-col v-else cols="12" md="6"></v-col>

                    <v-col cols="12" md="4">
                        <v-btn v-show="!loading" text rounded :disabled="disabled_cap" @click="onCapture"><v-icon color="red darken-4">mdi-camera</v-icon></v-btn>
                        <v-btn v-show="!loading" text rounded @click="reset()"><v-icon dark color="gray">mdi-adjust</v-icon>Reset</v-btn>
                        <v-btn :loading="loading" text rounded :disabled="disabled_save" @click="submit"><v-icon dark color="primary">mdi-save</v-icon>Guardar</v-btn>
                        <br/>
                            <!-- <code v-if="device">{{ device.label }}</code> -->
                                <vue-web-cam
                                    ref="webcam"
                                    :device-id="deviceId"
                                    height="250"
                                    width="100%"
                                    @started="onStarted"
                                    @stopped="onStopped"
                                    @error="onError"
                                    @cameras="onCameras"
                                    @camera-change="onCameraChange"
                                >
                                </vue-web-cam>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="3" >
                                    <vue-dropzone
                                            ref="myVueDropzone"
                                            id="dropzone"
                                            :options="dropzoneOptions"
                                            v-on:vdropzone-success="uploadFoto"
                                    ></vue-dropzone>
                                </v-col>
                </v-row>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import MenuOpe from './MenuOpe'
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import { WebCam } from "vue-web-cam"
export default {
    components: {
        'vue-web-cam': WebCam,
        'menu-ope': MenuOpe,
        'vueDropzone': vue2Dropzone,
        },
    data () {
        return {
            paciente:{},
            titulo: "Scanear Foto",
            camera: null,
            deviceId: null,
            devices: [],
            disabled_cap: true,
            disabled_save: true,
            loading: false,
            img_anverso: null,
            ruta_ant: {},
            id: null,
            dropzoneOptions: {
                url: '/mto/pacientes/'+this.$route.params.id+'/foto',
                paramName: 'foto',
                acceptedFiles: '.jpg,jpeg,.png',
                thumbnailWidth: 150,
                maxFiles: 1,
                maxFilesize: 2,
                headers: {
                    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                },
                dictDefaultMessage: 'Arrastra la FOTO aquí'
            },
        }
    },
    mounted(){

        this.id = this.$route.params.id;

        if (this.id > 0)
            axios.get('/mto/pacientes/'+this.id)
                .then(res => {
                    this.paciente = res.data.paciente;
                    this.titulo = res.data.paciente.nom_ape;

                    this.show = true;
                    this.show_loading = false;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'paciente.index'})
                })
                .finally(()=> {
                    this.show_loading = false;
                });

        // this.cliente_id = parseInt(this.$route.params.cliente_id,10);
        // this.compra_id =  parseInt(this.$route.params.compra_id,10);

        // axios.get('/mto/pacientes/'+this.paciente_id+'/captura')
        //     .then(res => {
        //         this.cliente = res.data.cliente;

        //     })
        //     .catch(err => {
        //         this.$toast.error(err.response.data.message);
        //         this.$router.push({ name: 'cliente.index'})
        //     })
    },
    computed: {
        device: function() {
            return this.devices.find(n => n.deviceId === this.deviceId);
        }
    },
    watch: {
        camera: function(id) {
            this.deviceId = id;
        },
        devices: function() {
            // Once we have a list select the first one
            const [first, ...tail] = this.devices;
            if (first) {
                this.camera = first.deviceId;
                this.deviceId = first.deviceId;
            }
        }
    },
    methods: {
        goBack(){
           this.$router.go(-1)
        },
        onCapture() {
            //if (this.img_anverso == null)
                this.img_anverso = this.$refs.webcam.capture();
            // else
            //     this.img_reverso = this.$refs.webcam.capture();

            // if (this.cliente.tipodoc == "N"){
            //     if (this.img_anverso != null && this.img_reverso != null){
            //         this.disabled_save = false;
            //     }
            // }
            // else{
            //     if (this.img_anverso != null)
                    this.disabled_save = false;
            //}



        },
        submit(){

            if (this.loading === false){
                this.loading = true;
                axios.post('/mto/pacientes/capture', {
                    paciente_id: this.paciente.id,
                    img: this.img_anverso,
                })
                    .then(res => {

                        this.goBack();

                        this.loading = false;

                    })
                    .catch(err => {

                        if (err.request.status == 422){ // fallo de validated.
                            const msg_valid = err.response.data.errors;
                            for (const prop in msg_valid) {
                                this.$toast.error(`${msg_valid[prop]}`);
                            }
                        }else{
                            this.$toast.error(err.response.data.message);
                        }
                        this.loading = false;
                    });
                }


        },
        onStarted(stream) {
         //   console.log("On Started Event", stream);
            this.disabled_cap = false;
        },
        onStopped(stream) {
           // console.log("On Stopped Event", stream);
        },
        onStop() {
            this.$refs.webcam.stop();
            this.$emit('update:show_webcam', false);
        },
        onStart() {
            this.$refs.webcam.start();
        },
        onError(error) {
            console.log("On Error Event", error);
        },
        onCameras(cameras) {
            this.devices = cameras;
          //  console.log("On Cameras Event", cameras);
        },
        onCameraChange(deviceId) {
            this.deviceId = deviceId;
            this.camera = deviceId;
          // console.log("On Camera Change Event", deviceId);
        },
        reset(){
            this.img_anverso = null;
        },
        uploadFoto(file, response){
            this.$toast.success('Upload OK');
        },
    }
}

</script>
