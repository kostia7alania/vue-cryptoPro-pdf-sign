
<template>
  <div @click="clickHandler" class="my-button" :class="cls">
    <p>{{text}}
      <img v-if="status=='loading'" src="../img/spinner.gif"/>
      <img v-if="status=='feedback'" src="../img/send.png"/>
    </p>
  </div>
</template>

<script>
import {TweenMax} from "gsap/TweenMax";
import vSelect from 'vue-select';
export default {
  props: {
    cls: null,
    text: {default: ()=>"Нажми на меня"},
    status:{default: () => false},
  }, // text,value
  name: "my-button",
  data() {
    return {};
  },
  mounted() {},  //  this.$nextTick(()=>this.localVal = null);
  computed: {},
  watch: {
  },
  methods: {
    clickHandler(e) {
      let $button = e.target;
      let duration = 0.3,
      delay = 0.08;
      TweenMax.to($button, duration, {scaleY: 1.6, ease: Expo.easeOut});
      TweenMax.to($button, duration, {scaleX: 1.2, scaleY: 1, ease: Back.easeOut, easeParams: [3], delay: delay});
      TweenMax.to($button, duration * 1.25, {scaleX: 1, scaleY: 1, ease: Back.easeOut, easeParams: [6], delay: delay * 3 });
      this.$nextTick( () => this.$emit('click') );
    }
  }
};
</script>

<style scoped lang="scss">
img { height: 15px; }

.my-button {
  /*background: #3498db;*/
  /*padding: 4px 15px;*/
  /*position: absolute;*/
  /*left: 50%;
  top: 50%;*/
  /*transform: translateX(-50%) translateY(-50%);*/
  border-radius: 5px;
  p {
    margin:0px;
    font-family: 'Roboto';
    text-align: center;
    text-transform: uppercase;
    /*color: #FFF;*/
    user-select: none;
  }
 &:hover {
      cursor: pointer;
      transform: scale(1.5);
 }
  transition: 1s;

  &.btn-send{


    color:white;
    padding: 10px 0;
    background-color: darken(#42b983, 5%);
    &:hover {
      background-color: darken(#42b983, 20%);
      transform: scale(1.1);
    }

    &>p {
      display: flex;
      justify-content: initial;
      align-items: center;
      justify-content: center;
      img{padding-left:4px;}
  }

  }



  /*тень*/
  /*&:after {
    content: "";
    display: block;
    position: absolute;
    width: 100%;
    height: 10%;
    border-radius: 50%;
    background-color: darken(#f1c40f, 20%);
    opacity: 0.4;
    bottom: -30px;
  }*/
}
</style>
