<template> 
    <div class="cert_list">
        <button class="btn-3d-1" v-if="!certList" @click="getCertList">Получить список сертификатов</button>

        <div v-if="certList"> 
            <h2>Выберите сертификат:</h2>  
            <select  multiple @click="sertSelectHandler">
                <option v-for="(crt,ind) in certList" :value="ind" :key="ind">{{crt.label}}</option> 
            </select>
        </div>

        <div v-if="selected_sert">
        <h2>Выбран сертификат: </h2>
            issuerName:     {{selected_sert.issuerName}} <br>
            label:          {{selected_sert.label}}  <br>
            name:           {{selected_sert.name}}  <br>
            subjectName:    {{selected_sert.subjectName}} <br> 
            Действителен: с {{selected_sert.validFrom}} по {{selected_sert.validTo}} <br>
            <button class="btn-3d-1" @click="$emit ('select-position', {cert64: cert64, selected_sert: selected_sert })">Выбрать положение</button>
        </div>
    </div>
</template>

<script>
export default {
  props: ['doc_id'],
  name: 'app',
  data () {
    return { certList: null, selected_sert: null, cert64:null }
  },
    methods: {
        getCertList(){ // получение списка сертификатов
            let that = this;
            that.$emit('loading','Загрузка...'); 
                window.CryptoPro.call('getCertsList')
                .then ( list => {
                    console.log('getCertsList',list,this); 
                    that.certList = list; //that.selected_sert = list[0];that.$emit('selecting_sert',list[0]);
                    that.$emit('loading','');
                }, 
                err => {
                    console.log( this, err ); alert( err );
                    that.$emit('loading','');
                }) 
        },
        sertSelectHandler(e) {
            this.$emit('sel_cert');
            $('.ui-draggable').remove();
            this.selected_sert = this.certList[e.target.value]; 
            var that = this
            this.selected_sert._cert.Export(0)  //getCertBase64 
            .then ( cert64 => {  
                cert64 = cert64.replace(/\n/gim,'').replace(/\r/gim,'').replace(' ','').trim();
                console.log('cert64=>',   cert64);
                this.cert64=cert64;
            })
           // .then( () => that.$emit('podpisat-confirm',1) )//1-preview,0-final
           .catch ( e => { throw('error'); });
        }
      }
}
</script>
<style scoped>
.cert_list { text-align: center; }
</style>


<style lang="scss"> 








/* ==========================

  3D Button 1

  ========================== */

.btn-3d-1 {
  position:     relative;
  background:   orangered;
  border:       none;
  color:        white;
  padding:      15px 24px;
  font-size:    1.4rem;
  box-shadow:   -6px 6px 0px hsl(16, 100%, 30%);
  outline:      none;
  
}

.btn-3d-1:hover {
  background: hsl(16, 100% , 45%);
  
}

.btn-3d-1:active {
  background: hsl(16, 100% , 40%);
  top:        3px;
  left:       -3px;
  box-shadow: -3px 3px 0 hsl(16, 100%, 30%);
  
}

.btn-3d-1::before {
  position: absolute;
  display:  block;
  content: "";
  height:   0;
  width:    0;
  
  border:           solid 6px transparent;
  border-right:     solid 6px hsl(16, 100%, 30%);
  border-left-width:0;
  top:              0;
  left:             -6px;
}

.btn-3d-1::after {
  position: absolute;
  display:  block;
  content: "";
  height:   0;
  width:    0;
  
  border:             solid 6px transparent;
  border-top:         solid 6px hsl(16, 100%, 30%);
  border-bottom-width:0;
  right:              0;
  bottom:             -6px;
}

.btn-3d-1:active::before {
  border:           solid 3px transparent;
  border-right:     solid 3px hsl(16, 100%, 30%);
  border-left-width: 0;
  left:             -3px;
  
}

.btn-3d-1:active::after {
  border:              solid 3px transparent;
  border-top:          solid 3px hsl(16, 100%, 30%);
  border-bottom-width: 0px;
  bottom:              -3px;
}

/* ==========================

  3D Button 2

  ========================== */

.btn-3d-2 {
  position: relative;
  
/* /*   background:  #ecd300; */
  background:  -webkit-radial-gradient( hsl(54, 100%, 50%), hsl(54, 100%, 40%) );
  background:  -o-radial-gradient( hsl(54, 100%, 50%), hsl(54, 100%, 40%) );
  background:  -moz-radial-gradient( hsl(54, 100%, 50%), hsl(54, 100%, 40%) );
  background:  radial-gradient( hsl(54, 100%, 50%), hsl(54, 100%, 40%) ); 
  background:  radial-gradient(#906b6b, #000000);
  font-size: 1.4rem;
  text-shadow:0 -1px 0 #c3af07;
  color: white;
  border: solid 1px hsl(54, 100%, 20%);
  border-radius: 100%;
  height: 120px;
  width: 120px;
  z-index:4;
  
  outline:none;
  box-shadow: inset 0 1px 0 hsl(54,100%,50%),
              0 2px 0 hsl(54,100%,20%),
              0 3px 0 hsl(54,100%,18%),
              0 4px 0 hsl(54,100%,16%),
              0 5px 0 hsl(54,100%,14%),
              0 6px 0 hsl(54,100%,12%),
              0 7px 0 hsl(54,100%,10%),
              0 8px 0 hsl(54,100%,8%),
              0 9px 0 hsl(54,100%,6%);
}

.btn-3d-2:hover {
  background:  -webkit-radial-gradient( hsl(54, 100%, 45%), hsl(54, 100%, 35%) );
  background:  -o-radial-gradient( hsl(54, 100%, 45%), hsl(54, 100%, 35%)  );
  background:  -moz-radial-gradient( hsl(54, 100%, 45%), hsl(54, 100%, 35%)  );
  background:  radial-gradient( hsl(54, 100%, 45%), hsl(54, 100%, 35%) );
}

.btn-3d-2:active {
  background:  -webkit-radial-gradient( hsl(54, 100%, 43%), hsl(54, 100%, 33%) );
  background:  -o-radial-gradient( hsl(54, 100%, 43%), hsl(54, 100%, 33%)  );
  background:  -moz-radial-gradient( hsl(54, 100%, 43%), hsl(54, 100%, 33%)  );
  background:  radial-gradient( hsl(54, 100%, 43%), hsl(54, 100%, 33%) );
  
  top:  2px;
  
    box-shadow: inset 0 1px 0 hsl(54,100%,50%),
              0 2px 0 hsl(54,100%,20%),
              0 3px 0 hsl(54,100%,18%),
              0 4px 0 hsl(54,100%,16%),
              0 5px 0 hsl(54,100%,14%),
              0 6px 0 hsl(54,100%,12%),
              0 7px 0 hsl(54,100%,10%);
}




</style>
