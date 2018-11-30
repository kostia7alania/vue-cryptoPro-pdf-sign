<template>
   <div class="appMain">  
            <div class="text-center" v-show="loading_text"> <div> <img src="./animaatjes-rubiks-cube.gif"> </div> <div>{{loading_text}}</div>  </div>
     
        <div v-show="!loading_text"> 
            <div class="text-center">
              <app-get-cert-list v-show="!doc_prev && !base64Binary" @select-position = "selectPosition" @loading = "loading_handler"/>
                <button v-show="doc_prev" class="btn-3d-1" @click="doc_prev=false">Выбрать другую подпись</button> 
            </div>
            
            
            <transition name="slide-fade">
                <div class="pechat" v-if="doc_prev && !base64Binary">

                    <h3>Положение видимой печати:</h3>  

                    <img width="595" height="842" :src="doc_prev" id="watermarked" /> 
                    
                    <div class="text-center">
                        <p>Подтвердить выставленное положение видимой печати</p>
                        <button class="btn-3d-2" @click="podpisat(0)">Подписать</button> 
                    </div>
                </div> 
            </transition>
            
  <transition name="slide-fade">
            <div class="text-center" v-if="base64Binary" >
                <button @click="base64Binary=false" class="btn-3d-1" width="auto"  target="_blank">Подписать еще один документ</button>
                <br><br><iframe :src="base64Binary" width="790px" height="1290px" frameborder="0"></iframe>
                <br><br><button @click="openBase64" class="btn-3d-1" width="auto"  target="_blank">Открыть подписанный документ</button>
            </div>

  </transition>
            <p class="text-center" v-if="stat">
                Статус: 
                <span :class="{green:stat==1, red:stat!=1}">{{stat==1?'Успех':'Поражение.!.'}}</span>
            </p>
            <p v-if="msg" >Сообщение: {{msg}}  </p>
        </div>
    </div>
</template>

<script>
import AppGetCertList from './AppGetCertList.vue';  
import axios from 'axios';

export default {
    components: { 'app-get-cert-list': AppGetCertList},
    props: ["doc_id"],
    data() {
        return { 
            loading_text: '', 
            doc_prev:   '', //"http://www.edou.ru/upload/learning/3/res26/AU0gg.XoPWc.Image19.jpg",
            stamp_img:  '',//"http://stamp-pro.ru/assets/cache_image/products/161/variant-i1_280x280_5db.png",
            url:        'https://localhost/wp_simple/cryptopro-pdf-sign-in-browser-with-vuejs/api_dss.php',
            HashValue:  '',
            cacheObjectId:'',
            createdSign: '', 
            msg: '',
            stat:'',
            base64Binary: null, 
            pechat_background:'',
            pechat_pos: {x:0,y:0,w:0,h:0,opacity:0}, 
            ssid: '',
            cert64:null,
            selected_sert:null
        }
    },
    mounted(){ window.axios_instance = axios.create({ headers: { 'crossDomain': true, "Access-Control-Allow-Origin": "*", 'Accept': 'application/json', 'Content-Type': 'application/json;charset=UTF-8', 'responseType': 'json' }/*,responseType: 'json'*/});
    },
    methods: {
        loading_handler(e){ console.log('load_handl',e); this.loading_text = e; },

        selectPosition(obj) {
            this.base64Binary = null; 
            this.cert64       = obj.cert64
            this.selected_sert= obj.selected_sert
            this.podpisat(1);
        },

        selecting_sert(e){
             console.log('ВЫБРАН=>>>>>>>>>',e);  
            this.base64Binary="";
            this.doc_prev="";
            this.stamp_img=""; /*флаг на перезагрузку печати*/},
        previewPechat(e){    console.log('[previewPechat] =>',e); console.log("[842-98-parseInt(e.y)] => "+(842-98-parseInt(e.y)) );
            this.pechat_pos=e;
            this.pechat_pos.y = parseInt(842-98-parseInt(e.y));    //дроби не любит ПДФ !онли целое!
            this.pechat_pos.x = parseInt(e.x);
        },
        openBase64(){
            var w = window.open('about:blank'); 
            setTimeout( () => { //FireFox seems to require a setTimeout for this to work.
                w.document.body.appendChild(w.document.createElement('iframe')).src = this.base64Binary; 
            }, 0);
        }, 

        podpisat(stamp_prev) {//stamp_prev: 0-final, 1-prev;
        this.loading_text='загрузка .... 0/3';
            let that = this;  
            let post_data = {
                cert_base64:   this.cert64,
                pechat_pos:    this.pechat_pos,
                selected_sert: this.selected_sert
            }  
            let url = `${this.url}?action=sign&stage=1&stampGen=${stamp_prev}`;
            if(!stamp_prev)  url = `${url}&ssid=${this.ssid}`; //если финальная стадия штампа
            axios_instance 
            .post(url, post_data)
            .then( res => {
                console.log("stage1=>", res); 
                try{ res = eval(`[${res.data}]`)[0]; } catch (e) { this.echo_end_die( { stat:0, msg:'Ошибка в ответе сервера на первой стадии.'} ); }
                this.stat = res.stat; this.msg = res.msg;
                this.HashValue = res.HashValue;
                this.cacheObjectId = res.СacheObjectId;
                this.ssid = res.ssid; 
                this.loading_text='загрузка .... 1/3'; 
                this.createSign(stamp_prev);
            })
            .catch( err => {that.loading_text=''; console.log("podpisat catch err=>"+err); that.stat = 0; that.msg = "Error while processing in stage1!";  });
        },
        createSign(stamp_prev){     console.log("[createSign] [HashValue] => " + this.HashValue)
                let that = this;
                let thumbprint = this.selected_sert.thumbprint; 
                let HashValue = this.HashValue; 
                console.log('thumbprint=>'+thumbprint, 'HashValue=>'+HashValue);

                window.CryptoPro.call('signData', thumbprint, HashValue)
                .then( createdSign => { 
                        console.log('signature=>',createdSign);
                        that.createdSign = createdSign;
                        that.podpisat2(stamp_prev);
                }, 
                err => { console.error('CryptoPro.call(signData) => ', err ); that.loading_text =''; }) 
        },

        podpisat2 (stamp_prev) {
            let data = {
                HashValue:      this.HashValue,
                pechat_pos:     this.pechat_pos,
                cacheObjectId:  this.cacheObjectId,
                createdSign:    this.createdSign,
                selected_sert:  this.selected_sert
            } 
           this.loading_text='загрузка .... 2/3'; 
           let url = `${this.url}?action=sign&stage=2&stampGen=${stamp_prev}&ssid=${this.ssid}`;
            axios_instance
            .post(url, data)
            .then( res => {
                console.log("stage2=>",  res );
                let d = res.data;
                if(!d) this.echo_end_die({stat:0,msg:'Данные не пришли от сервера!'});
                try{ d = eval(`[${d}]`)[0]; } catch (e) { this.echo_end_die({stat:0,msg:'Ошибка в ответе сервера во второй стадии.'}); }
                if(!d.base64Binary){ console.log('=>Залез в !d.base64Binary'); this.echo_end_die({stat:0,msg:'base64Binary не пришел от сервера!'}); }
                if ( stamp_prev==1) {
                    try { // echo_end_die(["stat"=>1,"base64Binary"=> $base64Binary,"doc_prev"=>$doc_prev]);
                        //that.stamp_img = "data:image/png;base64,"+res.data.split('[BREAK]')[0];   //stamp_img
                        //that.doc_prev =  "data:image/jpg;base64,"+res.data.split('[BREAK]')[1];   //doc_prev
                        if(!d.doc_prev){ console.log('=>Залез в !d.doc_prev'); this.echo_end_die({stat:0,msg:'doc_prev не пришел от сервера!'}); }
                        this.stamp_img = "data:image/png;base64,"+d.base64Binary //split('[BREAK]')[0]; 
                        this.doc_prev = "data:image/jpg;base64,"+d.doc_prev  //split('[BREAK]')[1]; 
                    } catch(err) {
                        console.log("podpisat2 try catch err=>" + err);
                        this.stat = 0; this.msg = "Error while parsing preview stamp!";
                        this.loading_text='';
                    }
                } else { 
                    this.base64Binary = d.base64Binary;
                    this.loading_text='';
                }
            })
            .then( () => {
                if ( stamp_prev==1 ) { 
                    this.loading_text=''//надо отобразить рисунок до нанесения печати (а то JQUERY не пашет!)
                   let that = this;
                    $('#watermarked').Watermarker({
                        watermark_img: that.stamp_img,  opacity: 1, x:227, y:37, w:236, h:98,
                        onChange: function(e){that.previewPechat(e)}.bind(that) 
                    });
                } else this.doc_prev = false; // удаляем предпросмотр дока!
            })
            .catch( err => { this.loading_text='';  console.log("podpisat2 catch err=>"+err); this.stat = 0; this.msg = "Error while processing in stage2!"; });
        },

        echo_end_die ( { stat, msg } ) { this.stat = stat; this.msg = msg; throw msg; }
    }
}
</script>
<style scoped lang="scss">
.pechat {
    p,h3,button{text-align:center;}
}
</style>


<style lang="scss">

.text-center{
    text-align:center;
}
button {cursor: pointer;}

.red { color:red; }
.green { color:green; }
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
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
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
        border:1px solid black;
        box-shadow: 0px 3px 5px 5px #a59292;
    }
}
.appMain{
    /*text-align: center;*/
    display: flex;
    flex-direction: column;
    align-items: center;
}

$font-stack:    Helvetica, sans-serif;
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
input { @include border-radius(10px); }
</style>
