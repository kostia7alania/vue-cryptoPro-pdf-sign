const mutations = {

  sendFeedBack(obj, {index, prop, state}){// console.log('appErrorsCommit', arguments)
    //obj[prop][index] = state;
    console.log(arguments)
  },  
  changeData (obj, {index, prop, state}){// console.log('appErrorsCommit', arguments)
  obj[prop] = state;
  console.log(arguments)
},


  



  changesRepeatMutator(obj, { prop, index, state }){// console.log('appErrorsCommit', arguments)
    obj[prop][index] = state;
    obj.changesRepeatStatus = obj.changesRepeat.some(e=>e=='изменения_есть');
  },

  save_mutator(obj, {prop, state}){ },//example;  
  /* maximize: s => { s.global_state = 1 }*/
  
  setRand(obj, {prop,state}) {
    obj[prop] = state > 0 ? +new Date() : -new Date();
  },
  
  sett(obj, {prop,state}) { obj[prop] = obj[prop] == state ? "": state; },//2 раза незя клик
  /* 
    search_mutator(obj, {prop, state}) { obj[prop] = obj[prop] == state ? "": state; }//2 раза незя клик
    obj[prop].leadNumber = obj[prop].leadNumber == state.leadNumber ? "": state.leadNumber; }//второй клик по той же ячейке - уьирает выделение
  },
  */

  state_mutator(s, {prop, val}) {
    s[prop] = val;
  },

  obj_mutator (s, {obj, prop, val}) {
    //console.error('obj_mutator',arguments)
    s[obj][prop] = val;
  }


}

export default mutations;