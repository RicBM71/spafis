<template>
   <v-col
        class="pa-0 ma-1"
        cols="12"
        md="2"
    >

        <v-autocomplete
            class="body-2 caption"
            dense
            v-model="model"
            :items="items"
            :loading="isLoading"
            :search-input.sync="search"
            clearable
            hide-details
            hide-selected
            label="Paciente"
            outlined
            @click:clear="clearRec"
            @change="change"
            >

            </v-autocomplete>

    </v-col>
</template>
<script>
  export default {
    props:['paciente_id','numero_bono'],
    data: () => ({
      isLoading: false,
      items: [],
      model: null,
      search: null,

    }),
    created: function () {
        this.debouncedGetAnswer = _.debounce(this.getPacientes, 1000)
    },

    watch: {
        paciente_id: function (newQuestion, oldQuestion) {
            if (this.paciente_id == null)
                this.clearRec();
        },
        search: function (newQuestion, oldQuestion) {
            this.debouncedGetAnswer()
        },

    },
    methods:{
        getPacientes(){

            if (this.items.length > 0 || this.search == null) return;

            this.isLoading = true

            axios.post('/tools/helpcli', {search: this.search})
                    .then(res => {

                        if (res.data.length == 1){ // esto es porque falla cuando solo devuelve un registro, no selecciona
                            this.items = res.data;
                            this.model = this.items[0].value;
                            this.getUltimoBono(this.model);
                            this.$emit('update:paciente_id', this.model);
                        }
                        else if (res.data.length > 0){
                            this.items = res.data;
                            this.model = this.items[0].value;
                            this.$emit('update:paciente_id', this.model);
                            this.getUltimoBono(this.model);
                        }

                    })
                    .catch(err => {
                        console.log(err)
                    })
                    .finally(() => (this.isLoading = false))
        },
        change(paciente_id) {
           // console.log(paciente_id);
            this.$emit('update:paciente_id', paciente_id);

            this.getUltimoBono(paciente_id);

        },
        getUltimoBono(paciente_id){

            axios.get('/tools/helpcli/'+paciente_id+'/bono')
                    .then(res => {

                        if (res != null)
                            this.$emit('update:numero_bono', res.data.bono);
                    })
                    .catch(err => {
                        console.log(err)
                    });


        },
        clearRec(){
            this.items = [];
            this.search = null;
        },
    }
  }
</script>
