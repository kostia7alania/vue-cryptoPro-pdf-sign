<template>
        <transition name="slide-fade">
          <div class="text-center">
            <button
              @click="$emit('clear')"
              class="btn-3d-1"
              width="auto"
              target="_blank"
            >Подписать еще один документ</button>
            <br>
            <br>
            <iframe :src="base64Binary" width="790px" height="1290px" frameborder="0"></iframe>
            <br>
            <br>
            <button @click="openBase64" class="btn-3d-1" width="auto" target="_blank" >Открыть подписанный документ</button>
          </div>
        </transition>
</template>

<script>

export default {
  name:"Preview-signed",
  props: ["base64Binary"],
  methods: {
    openBase64() {
      var w = window.open("about:blank");
      setTimeout(() => {
        //FireFox seems to require a setTimeout for this to work.
        w.document.body.appendChild(
          w.document.createElement("iframe")
        ).src = this.base64Binary;
      }, 0);
    },

  }
};


</script>

<style scoped lang="scss">
.pechat {p,h3,button {text-align: center;}}
</style>
