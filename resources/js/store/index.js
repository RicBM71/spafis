import Vue from 'vue';
import Vuex from 'vuex';
import auth from "./modules/auth";
import pagination from "./modules/pagination";
import result from "./modules/result";
import parametros from "./modules/parametros";
import citas from "./modules/citas";

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
        auth,
        pagination,
        result,
        parametros,
        citas
	},
	strict: true
});
