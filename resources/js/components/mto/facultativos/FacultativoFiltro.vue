<template>
    <v-row>
        <v-spacer></v-spacer>
        <v-col cols="12" md="2">
            <v-switch
                label="Solo activos"
                v-model="query.activo"
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
                activo: false
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
                        axios.post('/mto/facultativos/filtrar', this.query)
                        .then(res => {

                            this.loading = false;

                            this.$emit('update:items', res.data);

                             if (res.data.length > 0)
                                this.$emit('update:show_filtro', false);
                            else
                                this.$toast.warning('No se han encontrado facultativos');


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
