
<template>
  <button class="button" @click="$emit('click')" style="vertical-align:middle"><span>{{text}} </span></button>
</template>

<script>
export default {
  name: "my-button-anim",
  props: {
    text: {
      default: () => "Click"
    }
  },
  data() {
    return {};
  }
};
</script>

<style scoped lang="scss">
.button {
  display: inline-block;
  border-radius: 4px;
  background-color: gray;
  border: none;
  color: #ffffff;
  text-align: center;
  font-size: 16px;
  padding: 1 20px;
  width: "100%";
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: "\00ab";
  position: absolute;
  opacity: 0;
  top: 0;
  left: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-left: 25px;
}

.button:hover span:after {
  opacity: 1;
  left: 0;
}
</style>
