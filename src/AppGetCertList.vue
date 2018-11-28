<template> 
    <div>
        <button class="btn btn-danger" v-if="!certList" @click="getCertList">Получить список сертификатов</button>

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
            <button @click="$emit('podpisat-confirm')">Подписать</button>
        </div>
    
    </div>
</template>

<script>
export default {
  props: ['doc_id'],
  name: 'app',
  data () {
    return {
        certList: null, 
        selected_sert: null }
  },
    methods: {
        getCertList(){ // получение списка сертификатов
            var that = this;
            window.CryptoPro.call('getCertsList')
            .then( list => { 
                console.log(list); 
                that.certList = list; //that.selected_sert = list[0];that.$emit('selecting_sert',list[0]);
            }, 
            err => {
                console.log( this, err );
                alert( err );
            }); 
        },
        getCertBase64(){
            var that = this;
            this.selected_sert._cert.Export(0)
            .then(function(cert64) {
                cert64 = cert64.replace(/\n/gim,'').replace(/\r/gim,'').replace(' ','').trim()
                that.$emit ('cert-base64', cert64);
                console.log('cert64=>',   cert64);
            })
           // .then( () => that.$emit('podpisat-confirm',1) )//1-preview,0-final
        },
        sertSelectHandler(e) {
            e = e.target.value;
            $('.ui-draggable').remove();
            this.selected_sert = this.certList[e];
            this.$emit('selecting-sert', this.certList[e]);
            this.getCertBase64(); 
        }
      }
}
</script>

<style lang="scss"> 
 
</style>
