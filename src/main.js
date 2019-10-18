import Vue from 'vue'
import App from './views/AppMain.vue'
import store from "./store/index.js";
import 'es6-promise/auto';

//var bootstrap = require('/node_modules/bootstrap/dist/js');//require('bootstrap/dist/css/bootstrap.css');
import BootstrapVue from 'bootstrap-vue'; Vue.use(BootstrapVue);

require('./styles/main.scss');

import VueIntro from 'vue-introjs'; Vue.use(VueIntro); /*в глобал цсс еще кидал стили его! */
import swal from 'sweetalert'; window.swal = swal;

import introJs from '../node_modules/intro.js/minified/intro.min.js';

window.introJs = introJs();

//import StarRating from 'vue-star-rating' Vue.component('star-rating', StarRating);// -> local reg;

import vSelect from 'vue-select'; Vue.component('v-select', vSelect)


import { computeds_store } from './store/global_mixins.js'; Vue.mixin(computeds_store);

import myButton from "./components/my-button.vue"; Vue.component('myButton', myButton);//можно юзать как <my-button/> так и <myButton/>
import myCheckbox from "./components/my-checkbox.vue"; Vue.component('myCheckbox', myCheckbox);

Vue.config.devtools = true

require('./http.js');

window.EventBus = new Vue()
window.init_Vue = ({ el = '#app', backend_url = './api', ...props }) => {
  window.cryptoVue = new Vue({
    el,
    store,
    render: h => h(App, { props: {...props, el, backend_url }} )
  })

}
//init_Vue ('./backend_url.php');//4init app!


