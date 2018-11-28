import Vue from 'vue'
import App from './AppMain.vue'

import './libs/jquery-1.4.2.min.js'

import './libs/jquery-ui-1.8.6.custom.min.js'
import './libs/jquery-watermarker-0.3.js' 
 

//var bootstrap = require('/node_modules/bootstrap/dist/js');
//require('bootstrap/dist/css/bootstrap.css');

window.CryptoPro = require('./libs/cryptopro_src/index.js')
//import './libs/polyfills/atob-btoa.js'

window.goNewVue = function goNewVue(props) {
  new Vue({
    el: props.selector,
    render: h => h(App, { props: { 'doc_id': props.doc_id } })
  })
}