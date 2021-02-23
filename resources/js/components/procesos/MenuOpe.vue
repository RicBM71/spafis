<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goBack"
                >
                    <v-icon color="primary">mdi-arrow-left</v-icon>
                </v-btn>
            </template>
            <span>Lista</span>
        </v-tooltip>
    </div>
</template>
<script>
export default {
    props:[],
    data () {
      return {
      }
    },
    methods:{
        goBack(){
            this.$router.go(-1);
        },
    }
}
</script>
