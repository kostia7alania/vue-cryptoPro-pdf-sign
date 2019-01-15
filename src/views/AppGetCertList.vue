<template> 
    <div class="cert_list">

      <b-button  v-if="certList" variant="light " @click="getCertList"> <img src="../img/update.png" alt="list-icon" class="list-icon"> Обновить</b-button>
      <button v-if="!certList" 
                v-intro="step1_update"
                v-intro-step="1" 
                v-intro-scroll-to="'tooltip'" 
                v-intro-position="'bottom-left-aligned'"
                class="btn-3d-1" 
                @click="getCertList">
          <img src="../img/list-icon.png" alt="list-icon" class="list-icon">Получить список сертификатов</button>

        <div v-if="certList"> 
            <h2>Выберите сертификат:</h2>  
            <!--<select  multiple @click="sertSelectHandler()">
                <option v-for="(crt,ind) in certList" :value="ind" :key="ind">{{crt.name}}</option> 
            </select>-->
            <mySelect v-intro="step2" v-intro-step="2" @input="sertSelectHandler" :options="certList_COMP" />
        </div>

        <div v-if="selected_sert">
          <h2>Выбран <span style="color:red" v-if="sert_date_check">просроченный</span> сертификат: </h2> 
          <div v-intro="step3" v-intro-step="3" class="cert-list-rows">
              <div><b>Имя:</b>  {{selected_sert.Subject.G?selected_sert.Subject.G:'' + " " 
                                + selected_sert.Subject.SN?selected_sert.Subject.SN:'' 
                                + selected_sert.Subject.T?" (" + selected_sert.Subject.T + ")":''}}</div>
              <div><b>Организация:</b>  {{selected_sert.Subject.OU?selected_sert.Subject.OU:''}}</div>
              <div><b>Адрес:</b>  {{selected_sert.Subject.STREET?selected_sert.Subject.STREET:''}}</div>
              
              <!--<div><b>Издатель:</b>    {{selected_sert.SubjectName}}</div> -->
              <!--<p>Серийный №:    {{selected_sert.SerialNumber}}</p> "CN=ООО «НОВАГ-СЕРВИС», O=ООО «НОВАГ-СЕРВИС», STREET="ул. Комсомольская, д. 40, пом. 12", L=Краснодар, S=23 Краснодарский край, C=RU, ИНН=002315067718, ОГРН=1022302386028, E=NovAG@tax23.ru"
              <div>Отпечаток SHA1:    {{selected_sert.Thumbprint}}</div>-->
              <div><b>Действителен с</b> {{selected_sert.ValidFromDate | dateTimeFilter}} <b>по</b> {{selected_sert.ValidToDate | dateTimeFilter}}</div>
              <!--
              <div><b>Версия:</b>    {{selected_sert.Version}}</div>
              <div><b>Приватный ключ:</b>    {{selected_sert.HasPrivateKey?'Есть':'Нету'}}</div>
              <div><b>Валидный:</b>    {{selected_sert.IsValid?'Да':'Нет'}}</div>-->
          </div>
          <button v-intro="step4" v-intro-step="4" class="btn-3d-1" :class="{disabled: !IsValid_cert_comp}" @click="upsend_handler">Выбрать положение</button>
        </div>
    </div>
</template>

<script>

import mySelect from "../components/my-select.vue"; 

export default {
  components: { mySelect },
  props: ['doc_id'],
  name: 'App-Get-Cert-List',
  data () {
    return { 
      certList: null, 
      selected_sert: null,
      cert64: null
    }
  }, 
  filters:{
    dateTimeFilter (e) {
      return `${e.getDate()}.${e.getMonth()+1}.${e.getFullYear()} ${e.toLocaleTimeString()}`;
    }
  }, 
  computed:{
    sert_date_check(){
      let s = this.selected_sert;
      if(!s) return false;
      let f1 = +s.ValidFromDate < +new Date();
      let f2 = +new Date() < +s.ValidToDate;
      return !(f1 && f2);
    },
      certList_COMP(){
        return this.certList.map(e=>{
          return {text:e.name, value: e.id}
        })
      },
        IsValid_cert_comp() { 
            let s = this.selected_sert; 
            return typeof s == 'object' && s.IsValid ? s.IsValid:false;//true = валидно
        }
  },
  methods: {
    upsend_handler(){

      if(this.IsValid_cert_comp)
        this.$emit ('select-position', {cert64: this.cert64, selected_sert: this.selected_sert })
      else  swal("Hello world!", { className: "red-bg", icon: "error", text: 'Сертификат недействительный! Выберите действительный сертификат!'});
      
    },
        getCertList(){ // получение списка сертификатов
            let that = this;
            that.$emit('loading','Загрузка...');
              cryptopro.listCertificates() //window.CryptoPro.call('getCertsList')
                .then ( list => {
                    console.log('getCertsList',list,this); 
                    that.certList = list;
                    that.$emit('loading','');
                    this.goTour();
                    
                }, 
                err => {
                    console.log( this, err ); alert( err );
                    that.$emit('loading','');
                });
                
        },
        sertSelectHandler(e) {  console.log('eee====',e);
          let that = this;
            this.$emit('sel_cert');
            $('.ui-draggable').remove();
             // console.log('e.target.value=>',this.certList[e.target.value].id);
            this.$emit('loading','Загрузка...');
       //   throw ('kk');
            let sertId = e// this.certList[e.target.value].id;
          cryptopro
          .certificateInfo (sertId)
              .then ( sert => {
                  console.log('certificateInfo=>',sert)
                  that.selected_sert = sert; 
                cryptopro
                .readCertificate(sertId) //this.selected_sert._cert.Export(0)  //getCertBase64 
                  .then ( cert64 => {
                      cert64 = cert64//.replace(/\n/gim,'').replace(/\r/gim,'').replace(' ','').trim(); //вроде бы не треба более! -> и так и так пашет!;
                      console.log('cert64=>', cert64);
                      this.cert64=cert64;
                      that.$emit('loading','');
                      
                      this.goTour(3);
                      

                  })
                // .then( () => that.$emit('podpisat-confirm',1) )//1-preview,0-final
                .catch ( e => {  that.$emit('loading',''); throw('Ошибка->',e); });
              })
            .catch ( e => {  that.$emit('loading',''); throw('Ошибка->', e); });
        }
      }
}
</script>

<style scoped lang="scss">
/*select { flex:10;}*/

.cert_list { 
  text-align: center;
  
    flex: 10;
    display: flex;
    flex-direction: column;

  .cert-list-rows { 
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 10px 50px;
    &>div { 
      align-self: flex-start !important;
      text-align: left;
      padding-top: 5px; 
    }
  }
}

</style>



<style lang="scss">  
.list-icon{
  width: 20px;
  vertical-align: middle;
}
</style>
