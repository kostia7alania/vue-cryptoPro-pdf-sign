<template>
  <div class="appMain">

    <div id="content-header">
      <h1>Сервис электронной подписи ИЦГПК </h1>
      <vue-support />
    </div>
   
  

      <div class="main-content">
      

      <div class="text-center" v-show="loading_text">
        <div>
          <img src="../img/animaatjes-rubiks-cube.gif">
        </div>
        <div>{{loading_text}}</div>
      </div>

      <div v-show="!loading_text">
        <div class="text-center">
          <app-get-cert-list
            v-show="!doc_prev && !base64Binary"
            @sel_cert="sel_cert_handler"
            @select-position="selectPosition"
            @loading="loading_handler"
          />
          <button v-show="doc_prev" class="btn-3d-1" @click="doc_prev=false">Выбрать другую подпись</button>
        </div>

        <transition name="slide-fade">
          <div class="pechat" v-if="doc_prev && !base64Binary">
            <h3>Положение видимой печати:</h3>

            <img width="595" height="842" :src="doc_prev" id="watermarked">

            <div class="text-center">
              <p>Подтвердить выставленное положение видимой печати</p>
              <button class="btn-3d-2" @click="podpisat(0)">Подписать</button>
            </div>
          </div>
        </transition>

        <transition name="slide-fade">
          <div class="text-center" v-if="base64Binary">
            <button
              @click="base64Binary=false"
              class="btn-3d-1"
              width="auto"
              target="_blank"
            >Подписать еще один документ</button>
            <br>
            <br>
            <iframe :src="base64Binary" width="790px" height="1290px" frameborder="0"></iframe>
            <br>
            <br>
            <button
              @click="openBase64"
              class="btn-3d-1"
              width="auto"
              target="_blank"
            >Открыть подписанный документ</button>
          </div>
        </transition>
        <!--<p class="text-center" v-if="stat==1 || (stat == 0 && stat !== '') ">
              Статус: <span :class="{green:stat==1, red:stat!=1}">{{stat==1?'Успех':'Ошибка'}}</span>
            </p>
            <p class="text-center"  v-if="msg">Сообщение сервера: {{msg}}</p>-->
      </div>
    </div>
  </div>
</template>

<script>
import AppGetCertList from "./AppGetCertList.vue";
import axios from "axios";
import { CryptoPro } from "ruscryptojs";
import vue_support from "./VueSupport";


export default {
  components: {
    "app-get-cert-list": AppGetCertList,
    "vue-support": vue_support
  },
  name:"app-main",
  props: ["doc_id", "backend_url"],
  data() {
    return {
      loading_text: "",
      doc_prev: "", //"http://www.edou.ru/upload/learning/3/res26/AU0gg.XoPWc.Image19.jpg",
      stamp_img: "", //"http://stamp-pro.ru/assets/cache_image/products/161/variant-i1_280x280_5db.png",
      // url:        'https://localhost/wp_simple/cryptopro-pdf-sign-in-browser-with-vuejs/api_dss.php',
      //url:        '../backend/api_dss.php',
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

  window.cryptopro = new CryptoPro();
  cryptopro
    .init().then(e => console.warn("[Inited] ruscryptojs->", e))
    .catch(e=>console.log('Ошибка при cryptopro.init()=>',e));

    window.axios_instance = axios.create({
      headers: {
        crossDomain: true,
        "Access-Control-Allow-Origin": "*",
        Accept: "application/json",
        "Content-Type": "application/json;charset=UTF-8",
        responseType: "json"
      } /*,responseType: 'json'*/
    });
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
      this.loading_text = "загрузка .... 0/3";
      let that = this;
      let post_data = {
        cert_base64: this.cert64,
        pechat_pos: this.pechat_pos,
        selected_sert: this.selected_sert
      };
      let url = `${
        this.backend_url
      }?action=sign&stage=1&stampGen=${stamp_prev}`;
      if (!stamp_prev) url = `${url}&ssid=${this.ssid}`; //если финальная стадия штампа
      axios_instance
        .post(url, post_data)
        .then(res => {
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
            this.echo_end_die({ stat: 0, msg: err });
        });
    },
    createSign(stamp_prev) {
      console.log("[createSign] [HashValue] => " + this.HashValue);
      let that = this;
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
				oFnd.Find(cadesplugin.CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME,'ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""')
			.then(oCertificates => {
			oCertificates.Count
			.then(count=>{
				if(count==0) {alert("Нет сертификатов с таким именем!!!"); throw 'Нет сертификатов с таким именем!';}
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
      .then( () => oHashedData.SetHashValue(sHashValue) // данные кодируются при задании или получении значения свойства 
              .then(
                  () => cadesplugin.CreateObjectAsync("CAdESCOM.RawSignature")
                  .then( oRawSignature => oRawSignature.SignHash(oHashedData, oCertificate)
                    .then(e=>{ console.log(e);
                        cryptoVue.$children[0]._data.createdSign = e;
                        that.podpisat2(stamp_prev);
                    } )
                    .catch(e=>that.echo_end_die({ stat: 3, msg: 'Отменено пользователем (1)' }))
                ).catch(e=>that.echo_end_die({ stat: 3, msg: 'Отменено пользователем (2)'}))

              ).catch(e=>console.log('bb 22'))
          ).catch(e=>console.log('bb 11111111111'))
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
      let url = `${
        this.backend_url
      }?action=sign&stage=2&stampGen=${stamp_prev}&ssid=${this.ssid}`;
      axios_instance
        .post(url, data)
        .then(res => {
          console.log("stage2=>", res);
          let d = res.data;
          if (!d)
            this.echo_end_die({ stat: 0, msg: "Данные не пришли от сервера!" });
          try {
            d = eval(`[${d}]`)[0];
          } catch (e) {
            this.echo_end_die({
              stat: 0,
              msg: "Ошибка в ответе сервера во второй стадии."
            });
          }
          if (!d.base64Binary) {
            console.log("=>Залез в !d.base64Binary");
            this.echo_end_die({ stat: 0, msg: "base64Binary не пришел от сервера!" });
          }
          if (stamp_prev == 1) {
            try {
              // echo_end_die(["stat"=>1,"base64Binary"=> $base64Binary,"doc_prev"=>$doc_prev]);
              //that.stamp_img = "data:image/png;base64,"+res.data.split('[BREAK]')[0];   //stamp_img
              //that.doc_prev =  "data:image/jpg;base64,"+res.data.split('[BREAK]')[1];   //doc_prev
              if (!d.doc_prev) {
                console.log("=>Залез в !d.doc_prev");
                this.echo_end_die({
                  stat: 0,
                  msg: "doc_prev не пришел от сервера!"
                });
              }
              this.stamp_img = "data:image/png;base64," + d.base64Binary; //split('[BREAK]')[0];
              this.doc_prev = "data:image/jpg;base64," + d.doc_prev; //split('[BREAK]')[1];
            } catch (err) {
                console.log("podpisat2 try catch err=>" + err);
                this.echo_end_die({
                    stat: 0,
                    msg: "Ошибка при разборе штампа предпросмотра!"
                }); 
            }
          } else {
            this.base64Binary = d.base64Binary;
            this.loading_text = "";
          }
        })
        .then(() => {
          if (stamp_prev == 1) {
            this.loading_text = ""; //надо отобразить рисунок до нанесения печати (а то JQUERY не пашет!)
            let that = this;
            $("#watermarked").Watermarker({
              watermark_img: that.stamp_img,
              opacity: 1,
              x: 227,
              y: 37,
              w: 236,
              h: 98,
              onChange: function(e) {
                that.previewPechat(e);
              }.bind(that)
            });
          } else this.doc_prev = false; // удаляем предпросмотр дока!
        })
        .catch(err => {
          console.log("podpisat2 catch err=>" + err);
          this.echo_end_die({ stat: 0, msg: 'Ошибка во второй стадии подписания!' });
        });
    },

    echo_end_die({ stat, msg }) {
        if(!stat) swal("Ошибка!!", { className: "red-bg", icon: "error", text: msg });

        if(stat==3) swal("Ошибка!!", { className: "red-bg", icon: "error", text: msg });
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

.swal-overlay {
  background-color: rgba(64, 95, 88, 0.45); /*rgba(43, 165, 137, 0.45);*/
}
.swal-modal {
  /*background-color: rgba(63,255,106,0.69);*/
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
.swal-button:hover {
  background-color: #1e2b55 !important;
}
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

.text-center {
  text-align: center;
}
button { cursor: pointer; }

.red {
  color: red;
}
.green {
  color: green;
}
img.watermark {
  cursor: pointer;
  transition: 1s;
  &:hover {
    transform: scale(1.05);
  }
}

/* Анимации появления и исчезновения могут иметь */
/* различные продолжительности и динамику.       */
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
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

#content-header h1 {
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

</style>
