<template>
    <div id="app">
    <v-app>
        <v-main>
            <v-container
                class="fill-height"
                fluid
                >

                    <v-row justify="center">

                       <h1><a href="https://sanaval.com">sanaval.com</a></h1>

                    </v-row>
            </v-container>
        </v-main>
    </v-app>
</div>

</template>
<script>
  export default {
    data: () => ({
      isLoading: false,
      items: [],
      model: null,
      search: null,

    }),
    created: function () {
        this.debouncedGetAnswer = _.debounce(this.getPacientes, 500)
    },

    watch: {

        search: function (newQuestion, oldQuestion) {
            this.debouncedGetAnswer()
        },

    },
    methods:{

    }
  }
</script>
