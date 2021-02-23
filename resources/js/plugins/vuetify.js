// src/plugins/vuetify.js

import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

import es from 'vuetify/src/locale/es.ts'

const opts = {
    lang: {
        locales: { es },
        current: 'es',
      },

}

export default new Vuetify(opts)
