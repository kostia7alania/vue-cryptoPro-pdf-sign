
const getters = {
  /*anychanges: s=> {   // return diff(s.copyObj,s.newJson)
    return JSON.stringify(s.copyObj) != JSON.stringify(s.newJson) ///1-если есть изменения
  },*/
  helpStatus: s => s.helpStatus,
  tour: s => 0,// s.tour

  CERT_LIST_GETTER: state => state.CERT_LIST.length && state.CERT_LIST.map(e => {
    return { text: e.name, value: e.id };
  }),
  DOC_ID: () => {
    const params = new URLSearchParams(window.location.search);
    return params.get("id");
  },
}


export default getters;