<template>
        <transition name="slide-fade">
           <!--<div class="text-center">
            <button
              v-if="false"
              @click="clear_base64Binary"
              class="btn-3d-1"
              width="auto"
              target="_blank"
            >Изменить положение подписи</button>
            <h4 v-if="timmer > -1">Вы будете переадресованы из этого приложения через {{timmer}} сек.</h4>
           <img :src="base64Binary" width="595" height="842"> 
            <div v-html="base64Binary"></div>
            <br>
             <button @click="openBase64" class="btn-3d-1" width="auto" target="_blank" >Открыть подписанный документ</button>
          </div>-->

        <app-modal v-show="showModal" @close="goBack">

          <div slot="header">
            <div></div> 
            <div><strong>Статус подписания</strong></div>
            <my-button class="close" @click="goBack" text="x"/>
          </div>

          <div slot="body" class="status__text">
              <h3>Документ успешно подписан!</h3>
              <button @click="goBack" class="sexy-button button-green"> OK </button>
          </div>
          <div slot="footer"></div>


         </app-modal>

        </transition>
</template>

<script>
export default {
  name: "success-signed",
  components: {
    appModal: () => import('./Modal'), 
  },
  mounted() {},
  data() {
    return {
      timmer: 5,
      showModal: true
    };
  },
  methods: {
    goBack() {
      history.back()
    },
    openBase64() {
      const BACKEND_URL = this.$store.state.BACKEND_URL;
      var w = window.open("about:blank");
      setTimeout(() => {
        //FireFox seems to require a setTimeout for this to work.
        const iframe = w.document.createElement("iframe");
        window.iframe = iframe;
        iframe.style.width = "100%";
        iframe.style.height = "100%";
        w.document.body.appendChild(iframe).src =
          /*location.host +
          BACKEND_URL +*/
          //"http://192.168.201.118:8080/api?action=sign&stage&stampGen=0&get-signed-doc";
          `${this.$store.state.BACKEND_URL}action=sign&stage&stampGen=0&get-signed-doc&id=${this.$store.getters.DOC_ID}`;
      }, 0);
    },
    clear_base64Binary() {
      this.$store.commit("SET_BASE64_BINARY", null);
    }
  },
  computed: {
    base64Binary() {
      //"data:image/png;base64," +
      return this.$store.state.BASE64_BINARY;
    }
  },
  mounted() {
    // setTimeout(this.goBack, 5000);
    // setInterval(() => this.timmer--, 1000);
  }
};
</script>

<style scoped lang="scss">
.pechat {
  p,
  h3,
  button {
    text-align: center;
  }
}
.status__text {
  text-align: center;
}
</style>
