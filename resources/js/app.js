
require('./bootstrap');

window.Vue = require('vue');

import vuetify from '@/plugins/vuetify'

import router from './router';
import store from './store/index';

axios.interceptors.response.use(response => {
	return response;
}, error => {

    if (error.response.status == 401){
        window.location = "/login";
    }else if (error.response.status == 423){
        window.location = "/users/password";
    }else if (error.response.status == 419){
        window.location = "/login";
    }
    else if (error.response.status == 503){
        window.location = "/";
    }

	return Promise.reject(error);
});

// import 'material-design-icons-iconfont/dist/material-design-icons.css'

/*********************************
/* VuetifyToast
**********************************/
import Vuetify, { VSnackbar } from 'vuetify/lib'
import VuetifyToast from 'vuetify-toast-snackbar'

Vue.use(Vuetify, {
    components: {
    VSnackbar
    }
    })

Vue.use(VuetifyToast, { $vuetify: vuetify.framework,
    x: 'center',
    y: 'top', // default
    color: 'info', // default
    icon: '', iconColor: '', // default
    classes: [ 'body-2' ], timeout: 4000, // default
    dismissable: true, // default
    multiLine: false, // default
    vertical: false, // default
    queueable: true, // default
    showClose: false, // default
    closeText: '', // default
    closeColor: '', // default
    shorts: { custom: { color: 'purple' } },
    property: '$toast' // default
 });


/*************************
 * VeeValidate
 *************************/
import VeeValidate from 'vee-validate';
import VueValidationEs from 'vee-validate/dist/locale/es';
const config = {
	locale: 'es',
  	dictionary: {
	  	es: VueValidationEs
  	}
};
Vue.use(VeeValidate, config);

/***************************
 *  formateo números
 *******************************/
//import Vue2Filters from 'vue2-filters'

//Vue.use(Vue2Filters)
/***************************** */


Vue.component('form-login', require('./components/auth/FormLogin.vue').default);
Vue.component('mail-login', require('./components/auth/MailLogin.vue').default);
Vue.component('reset-login', require('./components/auth/ResetLogin.vue').default);
Vue.component('tpv-pc', require('./components/tpvpc/TpvPc.vue').default);


const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store
});
