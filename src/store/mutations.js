const mutations = {

  sendFeedBack(obj, { index, prop, state }) {// console.log('appErrorsCommit', arguments)
    //obj[prop][index] = state;
    console.log(arguments)
  },
  changeData(obj, { index, prop, state }) {// console.log('appErrorsCommit', arguments)
    obj[prop] = state;
    console.log(arguments)
  },






  changesRepeatMutator(obj, { prop, index, state }) {// console.log('appErrorsCommit', arguments)
    obj[prop][index] = state;
    obj.changesRepeatStatus = obj.changesRepeat.some(e => e == 'изменения_есть');
  },

  save_mutator(obj, { prop, state }) { },//example;
  /* maximize: s => { s.global_state = 1 }*/

  setRand(obj, { prop, state }) {
    obj[prop] = state > 0 ? +new Date() : -new Date();
  },

  sett(obj, { prop, state }) { obj[prop] = obj[prop] == state ? "" : state; },//2 раза незя клик
  /*
    search_mutator(obj, {prop, state}) { obj[prop] = obj[prop] == state ? "": state; }//2 раза незя клик
    obj[prop].leadNumber = obj[prop].leadNumber == state.leadNumber ? "": state.leadNumber; }//второй клик по той же ячейке - уьирает выделение
  },
  */

  state_mutator(s, { prop, val }) {
    s[prop] = val;
  },

  obj_mutator(s, { obj, prop, val }) {
    //console.error('obj_mutator',arguments)
    s[obj][prop] = val;
  },
  SET_SELECTED_CERT_OBJ(state, obj) {
    this._vm.$set(state, 'SELECTED_CERT_OBJ', obj)
  },
  CLEAR_SELECTED_CERT_OBJ() {
    this._vm.$set(state, 'SELECTED_CERT_OBJ', null)
  },
  SET_CERT_LIST(state, payload) {
    this._vm.$set(state, 'CERT_LIST', payload)
  },

  SET_SELECTED_CERT_INFO(state, payload) {
    this._vm.$set(state, 'SELECTED_CERT_INFO', payload)
  },
  SET_SELECTED_CERT_BASE64(state, payload) {
    this._vm.$set(state, 'SELECTED_CERT_BASE64', payload)
  },

  SET_PECHAT_POS(state, pechat_pos) {
    this._vm.$set(state, 'PECHAT_POS', pechat_pos)
  },
  SET_HASH_VALUE(state, HASH_VALUE) {
    this._vm.$set(state, 'HASH_VALUE', HASH_VALUE)
  },
  SET_CACHE_OBJECT_ID(state, CACHE_OBJECT_ID) {
    this._vm.$set(state, 'CACHE_OBJECT_ID', CACHE_OBJECT_ID)
  },
  SET_CREATED_SIGN(state, CREATED_SIGN) {
    this._vm.$set(state, 'CREATED_SIGN', CREATED_SIGN)
  },
  SET_BASE64_BINARY(state, BASE64_BINARY) {
    this._vm.$set(state, 'BASE64_BINARY', BASE64_BINARY)
  },
  SET_STAMP_IMG(state, payload) {
    this._vm.$set(state, 'STAMP_IMG', payload)
    return 'COMMIT SET_STAMP_IMG'
  },
  SET_DOC_PREV(state, payload) {
    this._vm.$set(state, 'DOC_PREV', payload)
    return { name: 'p1', status: 'success' }
  },
  SET_LAST_ERROR(state, payload) {
    this._vm.$set(state, 'LAST_ERROR', payload)
  },


}

export default mutations;