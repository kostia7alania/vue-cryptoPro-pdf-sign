import Vue from 'vue'
import App from './views/AppMain.vue'
//import App from './old_branch/AppMain.vue'
import './libs/jquery-1.4.2.min.js'
import './libs/jquery-ui-1.8.6.custom.min.js'
import './libs/jquery-watermarker-0.3.js' 
import swal from 'sweetalert'; 
//import StarRating from 'vue-star-rating' Vue.component('star-rating', StarRating);// -> local reg;


//var bootstrap = require('/node_modules/bootstrap/dist/js');//require('bootstrap/dist/css/bootstrap.css'); 

 window.cryptoVue =  new Vue({
    el: '#app',
  render: h => h(App, { props: { 'doc_id': 123, backend_url: 'https://ssd.emarinet.ru/backend/api_dss.php'/*action=sign&stage=1&stampGen=1'*/ } })
  })

Vue.config.devtools = true
