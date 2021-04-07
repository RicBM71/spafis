<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog_lin_add" persistent max-width="800px">

      <v-card>
        <v-card-title>
          <span class="headline">Nueva línea</span>
        </v-card-title>
        <v-card-text>
            <v-form>
                <v-row>
                        <v-col cols="8">
                            <v-text-field
                                v-model="item.concepto"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                label="Concepto "
                                data-vv-name="concepto"
                                data-vv-as="concepto"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="2">
                            <v-text-field
                                v-model="item.iva"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('iva')"
                                label="IVA"
                                data-vv-name="iva"
                                data-vv-as="iva"
                                class="inputPrice"
                                type="number"
                                v-on:keyup.enter="submit"
                                >
                            </v-text-field>
                        </v-col>
                        <v-col cols="2">
                            <v-text-field
                                v-model="item.importe"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('importe')"
                                label="Importe"
                                data-vv-name="importe"
                                data-vv-as="importe"
                                class="inputPrice"
                                type="number"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-col>
                </v-row>
            </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" rounded text @click="closeDialog">Cerrar</v-btn>
          <v-btn color="blue darken-1" rounded text @click="submit" :loading="loading">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>
<script>

  export default {
    $_veeValidate: {
        validator: 'new'
    },
    props:['factura','dialog_lin_add','reload'],
    data: () => ({
        loading: false,
        isLoading: false,
        items:[],
        search:"",
        model:"",
        item: {
            factura_id:"",
            concepto:"",
            iva:0,
            importe:0,
        },
    }),
    computed:{
        computedImporte(){
            var importe = (this.item.importe_unidad * this.item.unidades).toFixed(2);
            return this.item.importe_venta = (importe - (importe * this.item.descuento / 100)).toFixed(2);
        },
    },
    methods:{
        closeDialog (){
            this.$emit('update:dialog_lin_add', false)
        },
        submit() {
            if (this.loading === false){
                this.loading = true;

                this.item.factura_id = this.factura.id;

                var url = "/facturas/factlins";

                this.$validator.validateAll().then((result) => {

                    if (result){

                        axios.post(url, this.item)
                            .then(res => {
                                console.log(this.computedReload);

                                this.loading = false;
                                this.$emit('update:reload', this.reload + 1);

                                this.reset();
                                this.$validator.reset();
                                this.$emit('update:dialog_lin_add', false)

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
        reset(){
            this.item.concepto = '';
            this.item.iva = 0;
            this.item.importe = 0;
        }
      }
  }
</script>

<style scoped>
.v-form .container{
    padding: 4px;
}
.v-text-field {
    padding-top: 4px;
    margin-top: 2px;
}


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
</style>
