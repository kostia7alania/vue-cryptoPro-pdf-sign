 
const getters = { 
  /*anychanges: s=> {   // return diff(s.copyObj,s.newJson) 
    return JSON.stringify(s.copyObj) != JSON.stringify(s.newJson) ///1-если есть изменения
  },*/ 
  helpStatus: s => s.helpStatus,
  tour: s => s.tour
}


export default getters;