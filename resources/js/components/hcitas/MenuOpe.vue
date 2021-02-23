<template>
    <div>
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
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
    data () {
      return {
      }
    },
     computed: {
        ...mapGetters([
            'isAdmin',
        ])
    },
    methods:{
        goBack(){
            this.$router.go(-1)
        },

    }
}
</script>
