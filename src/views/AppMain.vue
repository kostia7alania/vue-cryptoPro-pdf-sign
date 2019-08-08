<template>
  <div class="appMain">

    <div id="content-header">
      <h1 class="my-logo">Сервис электронной подписи ИЦГПК </h1>
      <vue-support  :img_url="img_url"/>
    </div>


      <Steps :step="step"/>

      <div class="main-content">
        <Spinner v-if="loading_text" :loading_text="loading_text"/>

        <div v-else>
          <div class="text-center">
            <app-get-cert-list
              :img_url="img_url"
              v-show="!doc_prev && !base64Binary"
              @sel_cert="sel_cert_handler"
              @select-position="selectPosition"
              @loading="loading_handler"
            />
            <button v-show="doc_prev" class="btn-3d-1" @click="doc_prev=false">Выбрать другую подпись</button>
          </div>

          <choose-position v-if="doc_prev && !base64Binary" :doc_prev="doc_prev" @podpisat="podpisat($event)" />

          <Preview-signed v-if="base64Binary" :base64Binary="base64Binary" @cliar="base64Binary=false"/>

        </div>
    </div>
  </div>
</template>

<script>
import AppGetCertList from "./AppGetCertList.vue";

import { CryptoPro } from "ruscryptojs";

import '../libs/jquery-1.4.2.min.js'
import '../libs/jquery-ui-1.8.6.custom.min.js'
import '../libs/jquery-watermarker-0.3.js' //пародию начали пилить на ету тему -> https://codepen.io/anon/pen/YMRLdp


export default {
  components: {
    "app-get-cert-list": AppGetCertList,
    "vue-support":  () => import('./VueSupport'),
    "choose-position": () => import('./Choose-position'),
    "Spinner": ()=> import('./Spinner'),
    "Steps": ()=> import('./Steps'),
    "Preview-signed": ()=> import('./Preview-signed')
  },
  name:"app-main",
  props: ["backend_url", "img_url"],
  data() {
    return {
      loading_text: null,
      doc_prev: "", //"http://www.edou.ru/upload/learning/3/res26/AU0gg.XoPWc.Image19.jpg",
      stamp_img: "", //"http://stamp-pro.ru/assets/cache_image/products/161/variant-i1_280x280_5db.png",
      HashValue: "",
      cacheObjectId: "",
      createdSign: "",
      msg: "",
      stat: "",
      base64Binary: null,
      pechat_background: "",
      pechat_pos: { x: 0, y: 0, w: 0, h: 0, opacity: 0 },
      ssid: "",
      cert64: null,
      selected_sert: null
    };
  },
  mounted() {
    this.saveToStoreAndLocalStorage('saveState'); //без второго аргумента - значит,что берем из локалсторага значение

    window.cryptopro = new CryptoPro();
    cryptopro
    .init().then(e => console.warn("[Inited] ruscryptojs->", e))
    .catch(e=>console.log('Ошибка при cryptopro.init()=>',e));




  },
  computed: {
  step () {
    if(!this.doc_prev) return 0;
    if(this.doc_prev && !this.base64Binary) return 1;
    if(this.base64Binary) return 2;


  },
    id() {
        var params =  new URLSearchParams(window.location.search)
        var id  = params.get('id');
        return id
//      return document.location.search.replace('?','').split('&').reduce( (ac, next) => {const spl = next.split('='); ac[spl[0]] = spl[1]; return ac}, {}).id
    }
  },
  methods: {
    sel_cert_handler(){
        this.msg='';
        this.stat='';
    },
    loading_handler(e) {
      console.log("load_handl", e);
      this.loading_text = e;
    },
    selectPosition(obj) {
      this.base64Binary = null;
      this.cert64 = obj.cert64;
      this.selected_sert = obj.selected_sert;
      this.podpisat(1);
    },
    selecting_sert(e) {
      console.log("ВЫБРАН=>>>>>>>>>", e);
      this.base64Binary = "";
      this.doc_prev = "";
      this.stamp_img = ""; /*флаг на перезагрузку печати*/
    },
    previewPechat(e) {
      console.log("[previewPechat] =>", e);
      console.log("[842-98-parseInt(e.y)] => " + (842 - 98 - parseInt(e.y)));
      this.pechat_pos = e;
      this.pechat_pos.y = parseInt(842 - 98 - parseInt(e.y)); //дроби не любит ПДФ !онли целое!
      this.pechat_pos.x = parseInt(e.x);
    },
    openBase64() {
      var w = window.open("about:blank");
      setTimeout(() => {
        //FireFox seems to require a setTimeout for this to work.
        w.document.body.appendChild(
          w.document.createElement("iframe")
        ).src = this.base64Binary;
      }, 0);
    },

    podpisat(stamp_prev) {
      //stamp_prev: 0-final, 1-prev;
      this.loading_text = "загрузка .... 0/3"
      const that = this

      let post_data = {
        cert_base64: this.cert64,
        pechat_pos: this.pechat_pos,
        selected_sert: this.selected_sert ,
      };

    if(!this.id) {
      swal("id документа не передан. Попробуйте перезайти.")
      return;
    }
      let url = `${this.backend_url}?action=sign&stage=1&stampGen=${stamp_prev}&id=${this.id}`;
      if (!stamp_prev) url = `${url}&ssid=${this.ssid}`; //если финальная стадия штампа
      axios_instance
        .post(url, post_data)
        .then( res => {
          console.log("stage1=>", res);
          try { res = eval(`[${res.data}]`)[0]; } catch (e) { this.echo_end_die({ stat: 0, msg: "Ошибка в ответе сервера на первой стадии!" }); }
          if (!res.stat) { throw res.msg; }
          this.HashValue = res.HashValue;
          this.cacheObjectId = res.СacheObjectId;

          this.ssid = res.ssid;
          this.loading_text = "загрузка .... 1/3";
          this.createSign(stamp_prev);
        })
        .catch(err => {
            console.log("podpisat catch err=>" + err);
            this.echo_end_die({ stat: 0, msg: 'Сетевая ошибка!', err });
            this.loading_text=''
        })

    },
    createSign(stamp_prev) {
      console.log("[createSign] [HashValue] => " + this.HashValue);
      const that = this;
      let Thumbprint = this.selected_sert.Thumbprint;
      let HashValue = this.HashValue;
      let cert64 = this.cert64;
      if (!cert64) {
        throw "В сертификате отсутствует cert64!";
      }
      if (!Thumbprint) { throw "В сертификате отсутствует Thumbprint!"; } //уходит в кетч,откуда была вызвана етот метод (т.е из createSign -> в вызвавший его podpisat().catch)
      console.log("thumbprint=>" + Thumbprint, "HashValue=>" + HashValue);

  cadesplugin.CreateObjectAsync("CAdESCOM.Store")
	.then(oStore=>oStore.Open(cadesplugin.CAPICOM_CURRENT_USER_STORE, cadesplugin.CAPICOM_MY_STORE,cadesplugin.CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED)
		.then(e2 => {
			oStore.Certificates
			.then(oFnd => {
        const Thumbprint =  this.selected_sert.Thumbprint;
       /*window.oFnd = oFnd;
        window.cadesplugin = cadesplugin;*/
				//oFnd.Find(cadesplugin.CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME,'ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""')
        oFnd.Find(cadesplugin.CAPICOM_CERTIFICATE_FIND_SHA1_HASH, Thumbprint)
			.then(oCertificates => {
			oCertificates.Count
			.then(count=>{
				if(!count && (this.loading_text='') ) throw 'Нет сертификатов с таким именем!'
				oCertificates.Item(1)
					.then(oCertificate=>window.oCertificate=oCertificate)
              .then( () => step2(HashValue) ).catch(e=>console.log('5'))
            }).catch(e=>console.log('44444'))
					}).catch(e=>console.log('33333'))
				}).catch(e=>console.log('222'))
      }).catch(e=>console.log('11111111111'))
  )
  .catch(e=>{
    console.log('ОШИБКА ПРИ STEP2 =>',e);
    this.loading_text = "ОШИБКА ПРИ STEP1";

  })

function step2 (HashValue){
  var backend_HashValue_Base64 = HashValue //"Bm74AuKzIhVMtdYtUuJEGn1PBz/JbcNZAnAB1tl/KBE=" //перекодпировать в бинарный 16х!
  let sHashValue = base64toHEX(backend_HashValue_Base64);
  //sHashValue = backend_HashValue_Base64;// врубили -> CADESCOM_BASE64_TO_BINARY!!!
  cadesplugin
    .CreateObjectAsync("CAdESCOM.HashedData")
      .then(oHashedData=>{
          oHashedData.propset_Algorithm(cadesplugin.CADESCOM_HASH_ALGORITHM_CP_GOST_3411)
  //		.then( () => oHashedData.propset_DataEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY))
      .then(
        () => {
          //debugger;
          oHashedData.SetHashValue(sHashValue) // данные кодируются при задании или получении значения свойства
              .then(
                  () =>{
                    //debugger;
                    cadesplugin.CreateObjectAsync("CAdESCOM.RawSignature")
                  .then( oRawSignature => {
                  //  debugger
                    oRawSignature.SignHash(oHashedData, oCertificate)
                    .then(e=>{ console.log('last then=>'+e);
                        cryptoVue.$children[0]._data.createdSign = e;
                        that.podpisat2(stamp_prev);
                    }).catch(e=>that.echo_end_die({ stat: 3, msg: 'Отменено пользователем (1)',err:e}))
                  }
                ).catch(e=>that.echo_end_die({ stat: 3, msg: 'Ошибка похожее на отмену пользователем (2) ',err:e}))

        }).catch(err=>that.echo_end_die({ stat: 3, msg: e.message,err }))
      }).catch(e=>console.log('bb 11111111111',e))
      })
      .catch(e=>{
        console.log('ОШИБКА ПРИ STEP2 =>',e);
        this.loading_text = "ОШИБКА ПРИ STEP2";
      })
}
    },

    podpisat2(stamp_prev) {

      let data = {
        HashValue: this.HashValue,
        pechat_pos: this.pechat_pos,
        cacheObjectId: this.cacheObjectId,
        createdSign: this.createdSign.replace(/\n/gim,''),
        selected_sert: this.selected_sert
      };
      this.loading_text = "загрузка .... 2/3";
      let url = `${this.backend_url}?action=sign&stage=2&stampGen=${stamp_prev}&ssid=${this.ssid}&id=${this.id}`;


      axios_instance
        .post(url, data)
        .then(res => {
          console.log("stage2=>", res);
          let d = res.data;
          try { d = eval(`[${d}]`)[0]; }
          catch (err) { return this.echo_end_die({stat: 0, msg: "Ошибка при разборе ответа сервера во второй стадии", err }); }

          if(typeof d != 'object' || typeof d.stat == 'undefined' ) return this.echo_end_die({ stat: 0, msg: "Ошибка сервера во время второй стадии подписания!" })

          if(!d.stat) {
            const err = d.ALL_ERROR && d.ALL_ERROR.detail && d.ALL_ERROR.detail.DssFault && d.ALL_ERROR.detail.DssFault.Message || res.msg || 'Ошибка при разборе штампа предпросмотра'
            this.echo_end_die({ stat: 0,msg: err, });
          }

            if(d.stat) {
              if (stamp_prev == 1) {
                try {
                  // echo_end_die(["stat"=>1,"base64Binary"=> $base64Binary,"doc_prev"=>$doc_prev]);
                  //that.stamp_img = "data:image/png;base64,"+res.data.split('[BREAK]')[0];   //stamp_img
                  //that.doc_prev =  "data:image/jpg;base64,"+res.data.split('[BREAK]')[1];   //doc_prev
                  this.stamp_img = "data:image/png;base64," + d.base64Binary; //split('[BREAK]')[0];
                  this.doc_prev = "data:image/jpg;base64," + d.doc_prev; //split('[BREAK]')[1];
                } catch (err) {
                    console.log("podpisat2 try catch err=>" + err);
                    this.echo_end_die({
                        stat: 0,
                        msg: "Ошибка при разборе штампа предпросмотра!",
                        err
                    });
                }
              } else {
                this.base64Binary = d.base64Binary;
                this.loading_text = "";
              }

              if (stamp_prev == 1 && !d.doc_prev) return this.echo_end_die({ stat: 0, msg: "doc_prev не пришел от сервера!" })
              if (!d.base64Binary) return this.echo_end_die({ stat: 0, msg: "base64Binary не пришел от сервера!" })

            }

        })

        .then(() => {
          if (stamp_prev == 1) {
            this.loading_text = ""; //надо отобразить рисунок до нанесения печати (а то JQUERY не пашет!)
            const that = this;
            $("#watermarked").Watermarker({
              watermark_img: that.stamp_img,
              opacity: 1,
              x: 227,
              y: 37,
              w: 236,
              h: 98,
              onChange: e => that.previewPechat(e)
            });
          } else this.doc_prev = false; // удаляем предпросмотр дока!
        })
        .catch(err => {
          console.log("podpisat2 catch err=>" + err);
          const msg = err || 'Ошибка во второй стадии подписания!';
          this.echo_end_die({ stat: 0, msg, err });
        });
    },

    echo_end_die({ stat, msg, err }) {
        const text = typeof err == 'object' && err.message && err.message || msg
        console.warn('echo_end_die=>',arguments);
        if(!stat) swal("Ошибка!!", { className: "red-bg", icon: "error", text });
        else if(stat==3) swal("Ошибка!!", { className: "red-bg", icon: "error", text });
        this.stat = stat;
        this.msg = msg;
        this.loading_text = "";
        throw msg;
    }
  }
};



function base64toHEX(base64) { var raw = atob(base64); var HEX = ''; for (let i = 0; i < raw.length; i++ ) { var _hex = raw.charCodeAt(i).toString(16); HEX += (_hex.length==2?_hex:'0'+_hex); } return HEX.toUpperCase(); }

</script>

<style scoped lang="scss">
.pechat {p,h3,button {text-align: center;}}
</style>


<style lang="scss">
@import url(../styles/btn.scss);
@import url(../styles/global.scss);

.swal-overlay { background-color: rgba(64, 95, 88, 0.45); /*rgba(43, 165, 137, 0.45);*/
}
.swal-modal { /*background-color: rgba(63,255,106,0.69);*/
  border: 3px solid white;
}

.swal-button {
  padding: 7px 19px;
  border-radius: 2px;
  background-color: #4962b3;
  font-size: 12px;
  border: 1px solid #3e549a;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
}
.swal-button:hover { background-color: #1e2b55 !important; }
.disabled {
  border: 1px solid #999999 !important;
  background-color: #cccccc !important;
  color: #666666 !important;
  &:hover,
  &:active {
    border: 1px solid #999999 !important;
    background-color: #cccccc !important;
    color: #666666 !important;
  }
}

.text-center { text-align: center; }
button { cursor: pointer; }
.red { color: red; }
.green { color: green; }
img.watermark {
  cursor: pointer;
  transition: 1s;
  &:hover { transform: scale(1.05); }
}

/* Анимации появления и исчезновения могут иметь */
/* различные продолжительности и динамику.       */
.slide-fade-enter-active { transition: all 0.3s ease; }
.slide-fade-leave-active { transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1); }
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active до версии 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>


<style lang="scss" scoped>
.pechat {
  /*display: flex;
    flex-direction: column;
    align-items: center;*/
  img {
    border: 1px solid black;
    box-shadow: 0px 3px 5px 5px #a59292;
  }
}
.appMain{
    display: flex;
    flex-direction: column;
    .main-content {
        flex:9 1 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
}
$font-stack: Helvetica, sans-serif;
$primary-color: #333;
.appMain {
  font: 100% $font-stack;
  color: $primary-color;
}
@mixin border-radius($radius) {
  -webkit-border-radius: $radius;
  -moz-border-radius: $radius;
  -ms-border-radius: $radius;
  border-radius: $radius;
}
input {
  @include border-radius(10px);
}


</style>
<style>
#content-header {
  display: flex;
  justify-content: space-between;
    /*position: relative;*/
    top: -47px;
    width: 100%;
    height: 47px;
    margin-bottom: 1.5em;
    background-image: -webkit-gradient(linear,left 0%,left 100%,from(#304c73),to(#2b4569));
    background-image: -webkit-linear-gradient(top,#304c73,0%,#2b4569,100%);
    background-image: -moz-linear-gradient(top,#304c73 0%,#2b4569 100%);
    background-image: linear-gradient(to bottom,#304c73 0%,#2b4569 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#304c73',endColorstr='#2b4569',GradientType=0);
    border-bottom: 1px solid #304c73;
    border-top-right-radius: 4px;
    border-top-left-radius: 4px;
}

.my-logo {
    position: relative;
    left: 20px;
    margin: 0;
    color: #fff;
    font-size: 18px;
    font-weight: lighter;
    line-height: 47px;
    font-family: Helvetica,arial,sans-serif;
    padding-bottom: 28px;
}


@media (max-width: 455px) {
  .my-logo {
    font-size: 11px;
  }
}

.pt-20 {
  padding-top:20px;
}
</style>
