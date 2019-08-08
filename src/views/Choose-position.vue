<template>
        <transition name="slide-fade">
          <div class="pechat">
            <h3>Положение видимой печати:</h3>

            <img width="595" height="842" :src="doc_prev" id="watermarked">

            <div class="text-center pt-20">
              <p>Подтвердить выставленное положение видимой печати</p>
              <button class="btn-3d-2" @click="podpisat(0)">Подписать</button>
            </div>
          </div>
        </transition>
</template>

<script>


export default {
  name:"choose-position",
  props: [ "doc_prev"],
  data() {
    return {
    };
  },
  methods: {
    podpisat(state) {
      this.$emit('podpisat', state)
    }
  }
};

</script>


<style lang="scss">
.pechat {p,h3,button {text-align: center;}}
/* Анимации появления и исчезновения могут иметь */
/* различные продолжительности и динамику.       */
.slide-fade-enter-active { transition: all 0.3s ease; }
.slide-fade-leave-active { transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1); }
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active до версии 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>

