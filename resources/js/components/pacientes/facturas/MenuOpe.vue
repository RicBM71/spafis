<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goVolver"
                >
                    <v-icon color="primary">mdi-keyboard_return</v-icon>
                </v-btn>
            </template>
            <span>Volver a paciente</span>
        </v-tooltip>
    </div>
</template>
<script>
export default {
    props:['id'],
    data () {
      return {
          dialog: false,
          ruta: "facturas",
          url: "/mto/historias",
      }
    },
    computed: {
    },
    methods:{
        goVolver(){
            this.$router.push({ name: 'paciente.edit', params: { id: this.paciente_id } })
        },
        openDialog (){
            this.dialog = true;
        },
    }
}
</script>
