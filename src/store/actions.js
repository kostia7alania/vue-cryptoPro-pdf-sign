 
const actions = { // запросы к серверу:
  /*search({state,dispatch,commit}, query ) {//state-из раздела state; 
      dispatch('search', 'text search'); //запуск экшнов асинхронно;
      commit('delete', 'my text')//мутация - измение данных в state;
      commit('change', 'my text '); commit('set', 'my text')
    },*/
     
    exportExcel({commit,state}, {appNumber,type}) {
      axios_client
      .get(`${state.server}/excel/download/${state.json.appNumber}`,{responseType: 'blob'})
      .then(
        function(res) {
          window.res = this;
          if(!res){ 
            console.log('[!RES]');
          //  myModal.show_modal({title: 'Ошибка при выгрузке в Excel', text: 'Произошла сетевая ошибка!'});
          }
          console.log('загружаем ЭКСЕЛЬ! [STORE excel] *  * *  * *  res ==>>>>>', res.data); 
        }
      )
      .catch(e=>{ 
        //myModal.show_modal({title: 'Сетевая ошибка', text: 'Произошла ошибка при выгрузке в Excel'});
       });
    } 

  } 

  export default actions;
