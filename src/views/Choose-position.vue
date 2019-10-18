<template>
        <transition name="slide-fade">
          <div class="pechat">
            <h3>Положение видимой печати:</h3>

            <!--
              <img width="595" height="842" v-if="doc_prev" :src="doc_prev" id="watermarked">
            -->
              <div v-html="doc_prev"></div>

            <div class="confirm-section">
              <p>Подтвердить выставленное положение видимой печати</p>
              <button class="btn-3d-2" @click="podpisat">Подписать</button>
            </div>
          </div>
        </transition>
</template>

<script>
import { setInterval, clearInterval } from "timers";
export default {
  name: "choose-position",
  data() {
    return {
      interval: null
    };
  },
  mounted() {
    //this.$nextTick(() => this.init_watermark());
    this.interval = setInterval(this.init_watermark, 1000);
  },
  computed: {
    doc_prev() {
      //"data:image/jpg;base64," +
      return this.$store.state.DOC_PREV;
    },
    STAMP_IMG() {
      return this.$store.state.STAMP_IMG;
      //return this.$store.state.STAMP_IMG; //http://192.168.201.118:8080/api?action=sign&get-stamp-jquery&stage
    }
  },
  destroyed() {
    clearInterval(this.interval);
  },
  methods: {
    podpisat() {
      this.$store.dispatch("GET_PREVIEW", "final");
    },
    init_watermark() {
      if (!this.STAMP_IMG || !$("#watermarked").length) return;
      if ($(".watermark").length) return clearInterval(this.interval);
      const that = this;
      const options = {
        //надо отобразить рисунок до нанесения печати (а то JQUERY не пашет!)
        watermark_img: that.STAMP_IMG,
        opacity: 1,
        x: 227,
        y: 37,
        w: 236,
        h: 98,
        onChange: e => {
          // console.log(  "[842-98-parseInt(e.y)] => " + (842 - 98 - parseInt(e.y))          );
          const pechat_pos = e;
          pechat_pos.y = parseInt(842 - 98 - parseInt(e.y)); //дроби не любит ПДФ !онли целое!
          pechat_pos.x = parseInt(e.x);
          that.$store.commit("SET_PECHAT_POS", pechat_pos);
        }
      };
      const PECHAT_POS = this.$store.state.PECHAT_POS; 
      $("#watermarked").Watermarker({ ...options, ...PECHAT_POS });
      clearInterval(this.interval);
    }
  }
};
</script>


<style lang="scss">
.pechat {
  p,
  h3,
  button {
    text-align: center;
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
img#watermarked {
    border: 1px solid;
}
.confirm-section {
  text-align: center;
  button {
    margin: auto;
  }
}
</style>

