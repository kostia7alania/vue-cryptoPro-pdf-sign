import Vue from 'vue'
import App from './views/AppMain.vue'
import Vuex from 'vuex'; Vue.use(Vuex); import store from "./store/index.js"; 
import 'es6-promise/auto';
import BootstrapVue from 'bootstrap-vue';Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'; import 'bootstrap-vue/dist/bootstrap-vue.css';

import VueIntro from 'vue-introjs'; Vue.use(VueIntro); /*в глобал цсс еще кидал стили его! */

//import App from './old_branch/AppMain.vue'
import './libs/jquery-1.4.2.min.js'
import './libs/jquery-ui-1.8.6.custom.min.js'
import './libs/jquery-watermarker-0.3.js' 
import swal from 'sweetalert'; window.swal = swal;
import introJs  from '../node_modules/intro.js/minified/intro.min.js'; 
window.introJs = introJs();
//import StarRating from 'vue-star-rating' Vue.component('star-rating', StarRating);// -> local reg;

import vSelect from 'vue-select'; Vue.component('v-select', vSelect)

//var bootstrap = require('/node_modules/bootstrap/dist/js');//require('bootstrap/dist/css/bootstrap.css'); 
import {computeds_store} from './store/global_mixins.js'; Vue.mixin(computeds_store); 

import myButton from "./components/my-button.vue"; Vue.component('myButton', myButton);//можно юзать как <my-button/> так и <myButton/>
import myCheckbox from "./components/my-checkbox.vue"; Vue.component('myCheckbox', myCheckbox);
Vue.config.devtools = true

window.init_Vue = backend_url => {
  window.cryptoVue = new Vue({
    el: '#app',
    store,
    render: h => h(App, { props: { 'doc_id': 123, backend_url: backend_url /*'./backend/api_dss.php'*/  /*action=sign&stage=1&stampGen=1'*/ } })
  })
}
//init_Vue ('./backend_url.php');//4init app!


 