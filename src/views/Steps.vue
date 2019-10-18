<template>
  <nav class="steps">
    <ul class="progressbar">
      <li
        class="menu__item"
        v-for="(item, i) of menu__items"
        :key="i"
        :class=" step==i && 'active' || i > step  && 'menu__item-disabled' "
        @click="steps_click(item)"
      >
      <span>{{item}}</span>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "steps",
  props: {
    step: {
      type: [String, Number],
      default: () => 1
    }
  },
  data() {
    return {
      menu__items: ["Сертификат", "Положение", "Результат"]
    };
  },
  methods: {
    steps_click(item) {
      const com = e => this.$store.commit(e, null);
      // console.log(item);
      if (item == "Сертификат") {
        com("SET_DOC_PREV");
        com("SET_BASE64_BINARY");
      }
      item == "Положение" && com("SET_BASE64_BINARY");
    }
  }
};

</script>


<style lang="scss" scope>

nav.steps {
    /* margin: auto !important; */
    /* width: 420px; */
    /*transform: translateX(100px);*/
}

.menu__item-disabled {
  cursor: not-allowed !important;
}
li.menu__item {
  cursor: pointer;
  &:hover {
    font-weight: 555;
  }
}

.progressbar {
  counter-reset: step;
}
.progressbar li {
  list-style-type: none;
  width: 33%;
  float: left;
  font-size: 12px;
  position: relative;
  text-align: center;
  text-transform: uppercase;
  color: #7d7d7d;
}
.progressbar li:before {
  width: 30px;
  height: 30px;
  content: counter(step);
  counter-increment: step;
  line-height: 30px;
  border: 2px solid #7d7d7d;
  display: block;
  text-align: center;
  margin: 0 auto 10px auto;
  border-radius: 50%;
  background-color: white;
}
.progressbar li:after {
  width: 100%;
  height: 2px;
  content: "";
  position: absolute;
  background-color: #7d7d7d;
  top: 15px;
  left: -50%;
  z-index: -1;
}
.progressbar li:first-child:after {
  content: none;
}
.progressbar li.active {
  color: green;
}
.progressbar li.active:before {
  border-color: #55b776;
}
.progressbar li.active + li:after {
  background-color: #55b776;
}
</style>
