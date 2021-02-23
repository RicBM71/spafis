<template>
    <v-container>
        <v-row no-gutters>
            <h3>Ip's Autorizadas</h3>
        </v-row>
        <v-row no-gutters>
            <v-col
                cols="12"
                md="2"
            >
                <v-text-field
                    v-model="ip"
                    v-validate="'ip'"
                    :error-messages="errors.collect('ip')"
                    label="Indicar Ip"
                    data-vv-name="ip"
                    data-vv-as="ip"
                    v-on:keyup.enter="add"
                >
                </v-text-field>
            </v-col>
            <v-col
                cols="4"
                md="2"
            >
                <v-btn @click="add()" text rounded><v-icon color="blue darken-4">mdi-plus</v-icon>Nueva IP</v-btn>
            </v-col>
        </v-row>
        <v-row no-gutters>

            <v-col cols="12" md="2"
                v-for="item in items"
                :key="item.value"
            >
                {{item.text}}

                    <v-btn @click="del(item.value)" text icon rounded><v-icon color="red darken-4">mdi-close</v-icon></v-btn>

            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import {mapGetters} from 'vuex';
export default {
    props:['user_id','ips'],
    data(){
        return{
            ip: "",
            ip_usr: "",
            show: false,
            items:[],
        }
    },
    mounted(){
        this.items = this.ips;
    },
    computed: {
        ...mapGetters([
            'isRoot'
        ]),
    },
    methods:{
        add(){

             this.$validator.validateAll().then((result) => {
                if (result){

                    axios.post('/admin/ipusers', {ip: this.ip, user_id: this.user_id})
                        .then(res => {

                            this.items = res.data.ips;
                            this.loading = false;

                        })
                        .catch(err => {

                            if (err.request.status == 422){ // fallo de validated.
                                const msg_valid = err.response.data.errors;

                                for (const prop in msg_valid) {
                                    this.errors.add({
                                        field: prop,
                                        msg: `${msg_valid[prop]}`
                                    })
                                }
                            }
                            this.loading = false;
                        });
                    }
                else{
                    this.loading = false;
                }
            });

        },
        del(id){
            if (confirm("¿Eliminar Ip?")){
                axios.post('/admin/ipusers/'+id,{_method: 'delete'})
                    .then(res => {
                        this.items = res.data.ips;
                        this.$toast.success("Ip borrada");
                })
                .catch(err => {
                    this.status = true;
                    var msg = err.response.data.message;
                    this.$toast.error(msg);

                });
            }
        },
    }
}
</script>

