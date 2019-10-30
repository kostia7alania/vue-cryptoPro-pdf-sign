import { DirectiveLocation } from "graphql";

const actions = { // запросы к серверу:
  /*search({state,dispatch,commit}, query ) {//state-из раздела state;
      dispatch('search', 'text search'); //запуск экшнов асинхронно;
      commit('delete', 'my text')//мутация - измение данных в state;
      commit('change', 'my text '); commit('set', 'my text')
    },*/
  sendFeedBack({ state, commit }, { index, data }) {
    console.log('actions!!-', arguments)
    if (data) {
      let j = JSON.stringify({
        attachUserData: data.attachUserData,
        feedback_text: data.feedback_text,
        rating: data.rating
      });
      axios_instance
        .post(state.backend_url + '?action=feedback', j)
        .then(e => console.log('Отправлено!', e))
        .catch(e => console.log('Ошибка при отправке фидбека!', e));

    }
  },
  /*
  exportExcel({commit,state}, {appNumber,type}) {
    axios_instance
    .get(`${state.backend_url}/excel/download/${state.json.appNumber}`,{responseType: 'blob'})
    .then(
      function(res) {
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
  */
   GET_CERT_LIST({ state, commit, dispatch}) {
    // получение списка сертификатов
    if (!window.cryptopro) {
      window.cryptopro = new CryptoPro();
      cryptopro
        .init()
        .then(e => console.warn("[Inited] ruscryptojs->", e))
        .catch(e => console.log("Ошибка при cryptopro.init()=>", e));
    }
    EventBus.$emit("loading", "Загрузка....");
    cryptopro
      .listCertificates() //window.CryptoPro.call('getCertsList')
      .then(list => {
        console.log("GET_CERT_LIST", list, this);
        const promises = list.map(cert => (dispatch('IS_VALID_CERT', cert)) )
        Promise.allSettled(promises)
          .then(e =>{
            const filteredCertList = e.reduce((a,el)=> el.value.IsValid?[...a, el.value.cert]:a,[])
            commit('SET_CERT_LIST', filteredCertList);
          })
           
      })
      .catch(e => {
        alert(e.message)
        console.warn("appGetCertList - CATCH 188", e);
        commit('SET_CERT_LIST', []);
      })
      .finally(() => EventBus.$emit("loading", ""));
  },

  

  IS_VALID_CERT({ state }, cert) {
    //const sertId = state.SELECTED_CERT_OBJ.value;
    return cryptopro
      .certificateInfo(cert.id)
      .then( ({ IsValid }) => ({ IsValid, cert }) )
      .catch(e => false)
  },



  GET_SELECTED_CERT_INFO({ state, commit }) {
    const sertId = state.SELECTED_CERT_OBJ.value;
    return cryptopro
      .certificateInfo(sertId)
      .then(cert => {
        console.log("GET_SELECTED_CERT_INFO=>", cert);
        commit("SET_SELECTED_CERT_INFO", cert);
      })
      .catch(e => {
        throw ("Ошибка -> SET_SELECTED_CERT_INFO", e);
      })
  },

  GET_SELECTED_CERT_BASE64({ state, commit }) {
    const sertId = state.SELECTED_CERT_OBJ.value;
    return cryptopro
      .readCertificate(sertId) //this.selected_sert._cert.Export(0)  //getCertBase64
      .then(cert64 => {
        console.log("GET_SELECTED_CERT_BASE64=>", cert64);
        commit("SET_SELECTED_CERT_BASE64", cert64);
      })
      .catch(err => {
        throw ("Ошибка -> GET_SELECTED_CERT_BASE64", err);
      })
  },



  
  GET_PREVIEW({ state, commit, dispatch, getters }, stamp_stage) {
    const DOC_ID = getters.DOC_ID
    if (!DOC_ID) return swal("id документа не передан. Попробуйте перезайти.");

    const stamp_prev = stamp_stage == 'preview' ? 1 : stamp_stage == 'final' ? 0 : 'unknown :-D'
    //stamp_prev ==0 - final. 1 - prev;
    let url = `${state.BACKEND_URL}?action=sign&stage=1&stampGen=${stamp_prev}&id=${DOC_ID}`;
    EventBus.$emit("loading", "загрузка .... 1/4");
    return axios_instance
      .post(url, {
        cert_base64: state.SELECTED_CERT_BASE64,
        pechat_pos: state.PECHAT_POS,
        selected_sert: state.SELECTED_CERT_INFO
      } )
      .then(res => {
        !res.data && location.reload(); // пустой ответ - редирект на логин
        console.log("stage1=>", res);
        const d = res.data
        if (!d || !d.stat) throw res.msg;
        commit('SET_HASH_VALUE', d.HashValue)
        commit('SET_CACHE_OBJECT_ID', d.СacheObjectId)
        EventBus.$emit("loading", "загрузка .... 2/4");
        dispatch('CREATE_SIGN', stamp_prev);
      })
      .catch(err => {
        console.log("GET_PREVIEW catch err=>" + err);
        EventBus.$emit("echo_end_die", { msg: "Сетевая ошибка!", err });
      })
      .finally(() => EventBus.$emit("loading", ""));
  },

  CREATE_SIGN({ state, commit, dispatch }, payload) {

  },
  CONFIRM_POSITION({ state, commit }, payload) {

  },

  CREATE_SIGN({ state, commit, dispatch }, stamp_prev) { //createSign
    console.warn("[createSign] [HashValue] => " + state.HASH_VALUE);
    const Thumbprint = state.SELECTED_CERT_INFO.Thumbprint;
    const HashValue = state.HASH_VALUE;
    if (!Thumbprint) throw "В сертификате отсутствует Thumbprint!";
    const cert64 = state.SELECTED_CERT_BASE64;
    if (!cert64) throw "В сертификате отсутствует cert64";
    //уходит в кетч,откуда была вызвана етот метод (т.е из createSign -> в вызвавший его podpisat().catch)
    cadesplugin
      .CreateObjectAsync("CAdESCOM.Store")
      .then(oStore =>
        oStore.Open(cadesplugin.CAPICOM_CURRENT_USER_STORE, cadesplugin.CAPICOM_MY_STORE, cadesplugin.CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED)
          .then(() =>
            oStore.Certificates.then(oFnd =>
              //oFnd.Find(cadesplugin.CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME,'ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""')
              oFnd.Find(cadesplugin.CAPICOM_CERTIFICATE_FIND_SHA1_HASH, Thumbprint)
                .then(oCertificates =>
                  oCertificates.Count.then(count => {
                    if (!count) {
                      EventBus.$emit("loading", "");
                      throw "Нет сертификатов с таким именем!";
                    }
                    return oCertificates
                      .Item(1)
                      .then(oCertificate => (window.oCertificate = oCertificate))
                      .then(() => step2(HashValue, stamp_prev, commit, state))
                      .catch(e => console.log("5"));
                  }).catch(e => console.log("44444"))
                ).catch(e => console.log("33333"))
            ).catch(e => console.log("222"))
          ).catch(e => console.log("11111111111"))
      ).catch(e => { console.warn("ОШИБКА в CREATE_SIGN =>", e); EventBus.$emit("loading", "Возникла ошибка на первом шаге"); });
  },




  PODPISAT_2_PREVIEW({ state, commit, dispatch, getters }, data) {
    let iteration = 1;
    const p1 = dispatch('GET_FIRST_PAGE', data)
    const p2 = dispatch('GET_STAMP_IMG', { data, iteration })
    Promise.all([p1, p2]).then(v => EventBus.$emit("loading", ""))
  },

  GET_STAMP_IMG({ state, commit, dispatch, getters }, { data, iteration }) {
    let url = `${state.BACKEND_URL}?action=sign&stage=2&stampGen=1&get-stamp-img&id=${getters.DOC_ID}`;
    return axios_instance
      .post(url, data)
      .then(res => {
        if (typeof res.data == 'string' && res.data.length > 20) {
          commit('SET_STAMP_IMG', res.data) //png base64
          return { name: 'GET_STAMP_IMG', status: 'success', payload: res.data }
        }
        if (typeof res == 'object' && !res.stat) {
          if (iteration == 1) { return dispatch('GET_STAMP_IMG', { data, iteration: iteration + 1 }) }
          throw res.msg || 'Ошибка Soap Service'
        }
        if (!res.data || res.data.length < 10) throw 'Ответ сервера невалидный'
        /*
        if (res.headers["content-type"].match("application/pdf"))
          return commit('SET_BASE64_BINARY', res.data)
        console.log("PODPISAT_2_PREVIEW=>", res);
        let d = res.data;
        // debugger
        try { d = eval(`[${d}]`)[0]; } catch (err) {
          return EventBus.$emit("echo_end_die", {
            msg: "Ошибка при разборе ответа сервера во второй стадии",
            err
          });
        }
        if (typeof d != "object" || typeof d.stat == "undefined")
          return EventBus.$emit("echo_end_die", {
            msg: "Ошибка сервера во время второй стадии подписания",
            err
          });
        if (!d.stat) {
          const msg = parse_soap_error(d)
          EventBus.$emit("echo_end_die", { msg });
        }
        */

      })
      .catch(err => {
        const msg = parse_catch(err, 'Ошибка во второй стадии подписания')
        if (msg == state.LAST_ERROR) EventBus.$emit("echo_end_die", { msg });
        else commit('SET_LAST_ERROR', msg)
        if (iteration == 1) { return dispatch('GET_STAMP_IMG', { data, iteration: iteration + 1 }) }
        return { name: 'GET_STAMP_IMG', status: 'catch', payload: err }
      })
  },

  GET_FIRST_PAGE({ state, commit, dispatch, getters }, data) {
    commit('SET_DOC_PREV', null)
    const url = `${state.BACKEND_URL}?action=sign&stage=2&stampGen=1&get-first-page&id=${getters.DOC_ID}`;
    //commit('SET_DOC_PREV', url)return;//STOP ! Пойдем другим путем! Скормим его путем,чтобы сам получил данные!
    return axios_instance
      .post(url, data)
      .then( ({data:payload}) => {
        if(!payload || payload.length < 33) throw 'Ошибка предпросмотра. Данные не пришли.'
        commit('SET_DOC_PREV', payload)
        return { name: 'GET_FIRST_PAGE', status: 'success', payload }
      })
      .catch(err => {
        EventBus.$emit("echo_end_die", { err, msg: 'Ошибка загрузки превью'})
        console.warn('catch GET_FIRST_PAGE', err)
        return { name: 'GET_FIRST_PAGE', status: 'catch', payload: err }
      })
  },


  PODPISAT_2_FINAL({ state, commit, dispatch, getters }, data) {
    let url = `${state.BACKEND_URL}?action=sign&stage=2&stampGen=0&id=${getters.DOC_ID}`;
    return axios_instance
      .post(url, data)
      .then(res => {
        if(res.status == 201) return EventBus.$emit('set_signed') // успещно подписали ! ура!
        if (res.status == 208)
          return EventBus.$emit("echo_end_die", { msg: "Этот документ уже подписан!" });
        if (typeof res.data == 'object' && res.data.msg)
          return EventBus.$emit("echo_end_die", { msg: res.data.msg });

        if (res.headers["content-type"].match("text/plain"))
           return commit('SET_BASE64_BINARY', res.data)//ВСЕ! больше превьюшки не хотим .!.


        if (typeof res.length > 10) console.log("stage2=>", res);
        let d = res.data;
        try {
          d = eval(`[${d}]`)[0];
        } catch (err) {
          return EventBus.$emit("echo_end_die", {
            msg: "Ошибка при разборе ответа сервера во второй стадии",
            err
          });
        }

        if (typeof d != "object" || typeof d.stat == "undefined")
          return EventBus.$emit("echo_end_die", {
            msg: "Ошибка сервера во время второй стадии подписания",
            err
          });
        if (!d.stat) {
          const msg = parse_soap_error(d)
          EventBus.$emit("echo_end_die", { msg });
        }
      }) // удаляем предпросмотр дока!
      .catch(err => {
        const msg = parse_catch(err, 'Ошибка во второй стадии подписания')
        //debugger
        if (msg == state.LAST_ERROR) EventBus.$emit("echo_end_die", { msg });
        else {
          commit('SET_LAST_ERROR', msg)
          // dispatch('PODPISAT_2_FINAL')
        }
      })
      .finally(() => EventBus.$emit("loading", ""))
  }




}

export default actions;


function step2(HashValue, stamp_prev, commit, state) {
  EventBus.$emit("loading", "загрузка .... 2/4");
  var backend_HashValue_Base64 = HashValue; //"Bm74AuKzIhVMtdYtUuJEGn1PBz/JbcNZAnAB1tl/KBE=" //перекодпировать в бинарный 16х!
  const sHashValue = base64toHEX(backend_HashValue_Base64);
  //sHashValue = backend_HashValue_Base64;// врубили -> CADESCOM_BASE64_TO_BINARY!!!
  cadesplugin
    .CreateObjectAsync("CAdESCOM.HashedData")
    .then(oHashedData => {
      oHashedData
        .propset_Algorithm(cadesplugin.CADESCOM_HASH_ALGORITHM_CP_GOST_3411)
        // .then( () => oHashedData.propset_DataEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY))
        .then(() => {
          oHashedData
            .SetHashValue(sHashValue) // данные кодируются при задании или получении значения свойства
            .then(() => {
              cadesplugin
                .CreateObjectAsync("CAdESCOM.RawSignature")
                .then(oRawSignature => {
                  oRawSignature
                    .SignHash(oHashedData, oCertificate)
                    .then(e => {
                      store.commit("SET_CREATED_SIGN", e.replace(/\n/gim, ""));
                      commit('SET_LAST_ERROR', null)
                      const data = {
                        HashValue: state.HASH_VALUE,
                        pechat_pos: state.PECHAT_POS,
                        cacheObjectId: state.CACHE_OBJECT_ID,
                        createdSign: state.CREATED_SIGN,
                        selected_sert: state.SELECTED_CERT_INFO
                      };
                      EventBus.$emit("loading", "загрузка .... 3/4");

                      store.dispatch(stamp_prev == 1 ? "PODPISAT_2_PREVIEW" : "PODPISAT_2_FINAL", data)
                    }).catch(err => EventBus.$emit("echo_end_die", { msg: "Отменено пользователем (1)!", err }));
                }).catch(err => EventBus.$emit("echo_end_die", { msg: "Ошибка похожее на отмену пользователем (2)!", err: err.message }));
            }).catch(err => EventBus.$emit("echo_end_die", { msg: err.message }));
        }).catch(err => EventBus.$emit("echo_end_die", { msg: err.message }));
    }).catch(e => EventBus.$emit("loading", "Возникла ошибка на втором шаге"))
}

function base64toHEX(base64) {
  var raw = atob(base64);
  var HEX = "";
  for (let i = 0; i < raw.length; i++) {
    var _hex = raw.charCodeAt(i).toString(16);
    HEX += _hex.length == 2 ? _hex : "0" + _hex;
  }
  return HEX.toUpperCase();
}



function parse_soap_error(d) {
  return (d.ALL_ERROR &&
    d.ALL_ERROR.detail &&
    d.ALL_ERROR.detail.DssFault &&
    d.ALL_ERROR.detail.DssFault.Message) ||
    res.msg ||
    "Ошибка при разборе штампа предпросмотра";
}

function parse_catch(err, place) {
  let ret;
  if (typeof err !== 'object') return err
  const data = err.response && err.response && err.response.data
  if (data) {
    const div = document.createElement('div');
    div.innerHTML = data
    ret = div.querySelector('.error').innerText
  }
  ret = 'message' in err && err.message || 'Ошибка: ' + err
  console.warn(`[${place}][parse_catch] ret => `, ret)
  return ret
}

