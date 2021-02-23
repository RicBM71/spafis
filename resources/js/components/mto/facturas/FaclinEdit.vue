<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog_lin_edit" persistent max-width="800px">

      <v-card v-if="dialog_lin_edit">
        <v-card-title>
          <span class="headline">Editar línea</span>
        </v-card-title>
        <v-card-text>
            <v-form>
                <v-row>
                        <v-col cols="8">
                            <v-text-field
                                v-model="editedItem.concepto"
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
                                v-model="editedItem.iva"
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
                                v-model="editedItem.importe"
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
    props:['factura','editedItem','reload','dialog_lin_edit'],
    data: () => ({
        loading: false,
        isLoading: false,
        // editedItem: {
        //     factura_id:"",
        //     concepto:"",
        //     iva:0,
        //     importe:0,
        // },
    }),
    computed:{

        computedImporte(){
            var importe = (this.editedItem.importe_unidad * this.editedItem.unidades).toFixed(2);
            return this.editedItem.importe_venta = (importe - (importe * this.editedItem.descuento / 100)).toFixed(2);
        },
    },
    methods:{
        closeDialog (){
            this.$emit('update:dialog_lin_edit', false)
        },
        submit() {
            if (this.loading === false){
                this.loading = true;

                //this.editedItem.factura_id = this.factura.id;

                var url = "/facturas/factlins/"+this.editedItem.id;


                this.$validator.validateAll().then((result) => {

                    if (result){

                        axios.put(url, this.editedItem)
                            .then(res => {
                                //this.$router.push({ name: 'albaran.edit', params: { id: this.compra_id } })
                                //var i = this.reload + 1;
                                this.loading = false;
                                //this.$emit('update:reload', 1);


                                //this.reset();
                                //this.$validator.reset();
                                this.$emit('update:dialog_lin_edit', false)

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
            this.editedItem.concepto = '';
            this.editedItem.iva = 0;
            this.editedItem.importe = 0;
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
