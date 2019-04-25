


import Vue from 'vue';
import Vuex from 'vuex'; Vue.use(Vuex);
/*import Axios from 'axios';require('es6-promise').polyfill();var axios = require('axios');Vue.use(Axios);*/

import mutations from './mutations.js';
import getters from './getters.js';
import actions from './actions.js';
import state from './state.js';

window.store = new Vuex.Store({
  state,
  getters,
  mutations,
  actions
});

export default store;
