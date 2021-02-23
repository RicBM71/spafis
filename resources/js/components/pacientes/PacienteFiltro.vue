<template>
    <v-row>
        <v-spacer></v-spacer>
        <v-col
            cols="12"
            md="3"
        >
            <v-text-field
                v-model="query.nombre"
                label="Nombre/Apellidos"
                hint="[Nombre,Apellidos] - [Nombre,] - [,Apellidos]"
                v-on:keyup.enter="submit"
            >
            </v-text-field>
        </v-col>
        <v-col
            cols="12"
            md="1"
        >
            <v-text-field
                v-model="query.telefono"
                label="Teléfono"
                v-on:keyup.enter="submit"
            >
            </v-text-field>
        </v-col>
        <v-col
            cols="12"
            md="2"
        >
            <v-text-field
                v-model="query.direccion"
                label="Dirección"
                v-on:keyup.enter="submit"
            >
            </v-text-field>
        </v-col>
        <v-col cols="12" md="2">
            <v-switch
                label="Espera"
                v-model="query.espera"
                color="primary">
            ></v-switch>
        </v-col>
        <v-col
            cols="12"
            md="2"
        >
            <v-btn @click="submit"  text rounded  :loading="loading" color="primary">
                Filtrar
            </v-btn>
        </v-col>
    </v-row>
</template>
<script>
import moment from 'moment'
export default {
    $_veeValidate: {
        validator: 'new'
    },
    props:{
        show_filtro: Boolean,
        items: Array
    },
    data () {
      return {
            query:{
                nombre: null,
                apellidos: null,
                telefono: null,
                direccion: null,
                notas: null,
                espera: false
            },
            loading: false,
            result: false,

      }
    },
    methods:{
        submit(){

            if (this.loading === false){
                this.$validator.validateAll().then((result) => {
                    if (result){
                        this.loading = true;
                        axios.post('/mto/pacientes/filtrar', this.query)
                        .then(res => {

                            this.loading = false;

                             if (res.data.length > 0){
                                 this.$emit('update:items', res.data);
                                 this.$emit('update:show_filtro', false);
                             }
                            else
                                this.$toast.warning('No se han encontrado pacientes');


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
                 });
            }

        },
    }
}
</script>
