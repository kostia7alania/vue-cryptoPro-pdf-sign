<template>
  <main class="appMain">
    <section id="content-header">
      <h1 class="my-logo">Сервис электронной подписи ИЦГПК </h1>
      <vue-support/>
    </section>

      <Steps :step="step"/>

      <section class="main-content">
        <Spinner v-if="loading_text" :loading_text="loading_text"/>
        <div v-else>
          <div class="text-center">
            <app-get-cert-list v-show="!doc_prev && !base64Binary" />
            <my-button-anim-left
              v-show="doc_prev"
              text="Выбрать другой сертификат"
              @click="clear_doc_prev"
            />
          </div>

          <choose-position
            v-if="doc_prev && !base64Binary"
          />

          <Preview-signed-image
            v-if="base64Binary"
          />

        </div>
    </section>

  </main>
</template>

<script>
import { CryptoPro } from "ruscryptojs";
import "../libs/jquery-1.4.2.min.js";
import "../libs/jquery-ui-1.8.6.custom.min.js";
import "../libs/jquery-watermarker-0.3.js"; //пародию начали пилить на ету тему -> https://codepen.io/anon/pen/YMRLdp

import choosePosition from "./Choose-position"

export default {
  components: {
    "my-button-anim-left": () => import("../components/my-button-anim-left"),
    "app-get-cert-list": () => import("./AppGetCertList"),
    "vue-support": () => import("./VueSupport"),
    choosePosition,
    "Preview-signed-image": () => import("./Preview-signed-image"),
    Spinner: () => import("./Spinner"),
    Steps: () => import("./Steps")
  },
  name: "app-main",
  props: [],
  data() {
    return {
      loading_text: null
      // doc_prev: "", //"http://www.edou.ru/upload/learning/3/res26/AU0gg.XoPWc.Image19.jpg",
      // stamp_img: "", //"http://stamp-pro.ru/assets/cache_image/products/161/variant-i1_280x280_5db.png",
    };
  },
  mounted() {
    this.$store.commit("SET_PECHAT_POS", { x: 227, y: 37, w: 236, h: 98 });

    EventBus.$on("loading", e => {
      console.log("[APP MAIN] loading_handler", e);
      this.loading_text = e || "";
    });
    EventBus.$on("echo_end_die", ({ success = 0, msg, err }) => {
      EventBus.$emit("loading", "");
      const text =
        (typeof err == "object" && err.message && err.message) || msg;
      if (!success)
        swal("Ошибка!!", { className: "red-bg", icon: "error", text });
      else swal(msg || Успешно, { className: "green-bg", icon: "info", text });
      throw msg;
    });

    window.cryptopro = new CryptoPro();
    cryptopro
      .init()
      .then(e => console.warn("[Inited] ruscryptojs->", e))
      .catch(e => console.log("Ошибка при cryptopro.init()=>", e));
  },
  computed: {
    doc_prev() {
      return this.$store.state.DOC_PREV;
    },
    base64Binary() {
      return this.$store.state.BASE64_BINARY;
    },
    pechat_pos() {
      return this.$store.state.PECHAT_POS;
    },
    selected_sert() {
      return this.$store.state.SELECTED_CERT_INFO;
    },
    cert64() {
      return this.$store.state.SELECTED_CERT_BASE64;
    },
    step() {
      if (!this.doc_prev) return 0;
      if (this.doc_prev && !this.base64Binary) return 1;
      if (this.base64Binary) return 2;
    }
  },
  methods: {
    clear_doc_prev() {
      this.$store.commit("SET_DOC_PREV", null);
      this.$store.commit("SET_BASE64_BINARY", null);
    },
    openBase64() {
      var w = window.open("about:blank");
      setTimeout(() => {
        //FireFox seems to require a setTimeout for this to work.
        w.document.body.appendChild(
          w.document.createElement("iframe")
        ).src = this.base64Binary;
      }, 0);
    }
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
button {
  cursor: pointer;
}
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
.appMain {
  display: flex;
  flex-direction: column;
  .main-content {
    /*flex: 9 1 100%;*/
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
  background-image: -webkit-gradient(
    linear,
    left 0%,
    left 100%,
    from(#304c73),
    to(#2b4569)
  );
  background-image: -webkit-linear-gradient(top, #304c73, 0%, #2b4569, 100%);
  background-image: -moz-linear-gradient(top, #304c73 0%, #2b4569 100%);
  background-image: linear-gradient(to bottom, #304c73 0%, #2b4569 100%);
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
  font-family: Helvetica, arial, sans-serif;
  padding-bottom: 28px;
}

@media (max-width: 455px) {
  .my-logo {
    font-size: 11px;
  }
}

.pt-20 {
  padding-top: 20px;
}
</style>
