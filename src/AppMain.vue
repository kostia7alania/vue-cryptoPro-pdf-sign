<template>
   <div> 
        <div is="app-get-cert-list"  
            @podpisat-confirm="podpisat_1"
            @get-cert-list="getCertList" 
            @selecting-sert="selecting_sert" 
            @cert-base64="certing_base64"
        ></div>

        <div v-show="stampPrevShow" is="app-stamp-prev" class="pechat"
             @podpisat-confirm="podpisat_2"
             :doc_prev="doc_prev" 
             :stamp_img="stamp_img"
        ></div>
        <hr> 
        <p>stat: {{stat}}</p> 
        <p>msg: {{msg}}</p>  
        <hr>

        <iframe-prev v-if="iframe_show" :base64Binary="base64Binary"></iframe-prev>

        <br>
        <button class="btn btn-warning"  v-if="base64Binary.length>1" width="auto"  target="_blank" @click="openBase64">Открыть подписанный документ</button>
    </div>
</template>

<script>
import AppGetCertList from './AppGetCertList.vue';
import AppStampPrev   from './AppStampPrev.vue';
import iframe_prev   from './iframe_prev.vue';
import axios from 'axios'

export default {
    components: {
        'app-get-cert-list': AppGetCertList,
        'app-stamp-prev': AppStampPrev,
        'iframe-prev': iframe_prev
    },
    props: ["doc_id"],
    data() {
        return {
            stampPrevShow: 0,
            doc_prev:  '', //"http://www.edou.ru/upload/learning/3/res26/AU0gg.XoPWc.Image19.jpg",
            stamp_img: '',//"http://stamp-pro.ru/assets/cache_image/products/161/variant-i1_280x280_5db.png",
            url:    'https://localhost/wp_simple/cryptopro-pdf-sign-in-browser-with-vuejs/api_dss.php',
            HashValue: '',
            cacheObjectId:'',
            createdSign: '', 
            msg: '',
            stat:'',
            base64Binary:'',
            cert_base64:'',
            selected_sert:false, 
            pechat_background:'',
            pechat_pos: {x:0,y:0,w:0,h:0,opacity:0},
            iframe_show: 0,
            need_reload: 1,
            ssid: ''
        }
    },
    mounted(){
        window.axios_instance = axios.create({headers: { 'crossDomain': true, 'Accept': 'application/json', 'Content-Type': 'application/json' }});
    },
    methods: {
        podpisat_1(){ this.podpisat(this, 1)},
        podpisat_2(){this.base64Binary='';},
        certing_base64(e){this.cert_base64 = e;},
        selecting_sert(e){this.selected_sert=e; console.log('ВЫБРАН=>>>>>>>>>',e); this.need_reload=1; this.base64Binary="";this.doc_prev="";this.stamp_img=""; /*флаг на перезагрузку печати*/},
        getCertList(e){console.log('getCertList handler ==>',e)},
        previewPechat(e){    
            console.log('previewPechat=>',e,'<='); this.pechat_pos=e;
            console.log("842-98-parseInt(e.y)  => "+(842-98-parseInt(e.y)) );
            this.pechat_pos.y = parseInt(842-98-parseInt(e.y));      //дроби не любит ПДФ !онли целое!
            this.pechat_pos.x = parseInt(e.x);
        },
        openBase64(){
            var that = this;
            var w = window.open('about:blank'); 
            setTimeout(function(){ //FireFox seems to require a setTimeout for this to work.
                w.document.body.appendChild(w.document.createElement('iframe')).src = that.base64Binary;
            }, 0);
        },
        hashValueHandler(e){this.HashValue=e.target.value.trim();},
        createSign(stamp_prev){

                var that = this; 
                
                var thumbprint = that.selected_sert.thumbprint;
                
                var HashValue = that.HashValue;
                
                console.log('thumbprint=>'+thumbprint, 'HashValue=>'+HashValue);

                window.CryptoPro.call('signData', thumbprint, HashValue)
                .then(function (createdSign) { 
                        console.log('signature=>',createdSign);
                        that.createdSign = createdSign;
                        that.podpisat2(that,stamp_prev);
                })
        },
        podpisat(that,stamp_prev) {
            if(!stamp_prev){that.stampPrevShow=0;that.iframe_show = 1 }   //отобразить спиннер
            if(stamp_prev){that.stampPrevShow=1; that.iframe_show = 0;}   //скрыть спиннер (отображается iframe)
                            
            let post_data = {
                cert_base64:   this.cert_base64,
                pechat_pos:    this.pechat_pos,
                selected_sert: this.selected_sert
            } 

            axios_instance 
            .post(`${that.url}?action=sign&stage=1&stampGen=${stamp_prev}`, post_data)
            .then( res => {
                console.log("stage1=>", res);
                console.log("stage1 data=>", res.data);
                window.r=res;
                res = eval(`[${res.data}]`)[0]; that.stat = res.stat; that.msg = res.msg;
                that.HashValue = res.HashValue;
                that.cacheObjectId = res.СacheObjectId;
                that.ssid = res.ssid;
                that.createSign(stamp_prev);
            })
            .catch( err => {
                console.log("podpisat catch err=>"+err);
                that.stat = 0;
                that.msg = "Error while processing in stage1!"; 
            });
        },
        podpisat2 (that,stamp_prev) {
            let data = {
                HashValue:      that.HashValue,
                pechat_pos:     that.pechat_pos,
                cacheObjectId:  that.cacheObjectId,
                createdSign:    that.createdSign,
                selected_sert:  that.selected_sert
            }
            /*
            formdata.append('HashValue',    that.HashValue);
            formdata.append('pechat_pos',  JSON.stringify(that.pechat_pos)); 
            formdata.append('cacheObjectId',that.cacheObjectId);
            formdata.append('createdSign',  that.createdSign);    
            formdata.append('selected_sert',JSON.stringify(that.selected_sert));
            */  
            axios.post(`${that.url}?action=sign&stage=2&stampGen=${stamp_prev}&ssid=${that.ssid}`, data)
            .then( res => {
                console.log("stage2=>",  res );
                if  ( stamp_prev==1 && that.need_reload==1 ) {
                    try {
                        that.stamp_img = "data:image/png;base64,"+res.data.split('[BREAK]')[0];   //stamp_img
                        that.doc_prev =  "data:image/jpg;base64,"+res.data.split('[BREAK]')[1];   //doc_prev
                    } catch(err){console.log("podpisat2 try catch err=>"+err); that.stat = 0; that.msg = "Error while parsing preview stamp!";}
                }
                else{ that.base64Binary = res.data.base64Binary;}
            })
            .then( () => {
                if  ( stamp_prev==1 && that.need_reload==1 ) { 
                    $('#watermarked').Watermarker({
                        watermark_img: that.stamp_img,  opacity: 1, x:227, y:37, w:236, h:98,
                        onChange: function(e){that.previewPechat(e)}.bind(that) 
                    });
                }
                that.need_reload=0;
                if(!stamp_prev){that.stampPrevShow=0;that.iframe_show = 1 }   //отобразить спиннер
                if(stamp_prev){that.stampPrevShow=1; that.iframe_show = 0;}   //скрыть спиннер (отображается iframe)
            })
            .catch( err => { console.log("podpisat2 catch err=>"+err); that.stat = 0; that.msg = "Error while processing in stage2!"; });
        }
    }
}
</script>


<style lang="scss"> 
$font-stack:    Helvetica, sans-serif;
$primary-color: #333;
body {
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
