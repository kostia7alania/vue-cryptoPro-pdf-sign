<template> 
         <div>
            <p v-if="base64Binary.length<1" style="text-align:center">
                <i class="fa fa-spinner fa-spin" style="font-size:33px"></i>
            </p>
            <iframe v-else="base64Binary.length>1" :src="base64Binary" width="790px" height="1290px" frameborder="0"></iframe>
        </div>
</template>

<script>
export default {
  props: ['base64Binary'], 
  data () {
    return { }
  },
    methods: {
        getCertList(){ // получение списка сертификатов
            var that = this;
            window.CryptoPro.call('getCertsList')
            .then(function (list) { 
                console.log(list); 
                that.certList = list;
                //that.selected_sert = list[0];that.$emit('selecting_sert',list[0]);
            }, function (err) {console.log( this, err ); alert( err );}); 
        },
        getCertBase64(){
            var that = this;
            this.selected_sert._cert.Export(0)
            .then(function(cert64) {
                cert64 = cert64.replace(/\n/gim,'').replace(/\r/gim,'').replace(' ','').trim()
                that.$emit ('cert-base64', cert64);
                console.log('cert64=>',   cert64);
            })
            .then(
                function(){
                    that.$emit('podpisat-confirm',1)//1-preview,0-final
                }
            )
        },
        sertSelectHandler(e){
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
