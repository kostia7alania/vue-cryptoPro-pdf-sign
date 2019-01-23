const state = {
  production: 1, // 0 тест, 1 - продакшн 
  backend_url: '',//'http://localhost:8383/rest-api',
  saveState: 0,//как изначально работать с сохранением состояния (после обновления стр);
  helpStatus: 'main', ////main,feedback,interactive
  tour: false //включен ли режим тура; 
};
  
  

import Vue from 'vue';
import Vuex from 'vuex'; Vue.use(Vuex);
/*import Axios from 'axios';require('es6-promise').polyfill();var axios = require('axios');Vue.use(Axios);*/
 
import mutations from './mutations.js'; 
import getters from './getters.js'; 
import actions from './actions.js';

window.store = new Vuex.Store({
  state, 
  getters,
  mutations,
  actions
});

export default store;
