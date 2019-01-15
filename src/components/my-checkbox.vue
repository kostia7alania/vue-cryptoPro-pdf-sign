
<template>
<label class="check-container">
  <span class="check-text">{{text}}</span>
  <input :value="checked" @click="clickHandler" type="checkbox">
  <span class="checkmark"></span>
</label>
</template>

<script>
export default { 
  props: {
    checked: {
      default: () => false
    },
    cls: null,
    text: {default: ()=>""},
    status:{default: () => false},
  }, // text,value
  name: "my-button",
  data() {
    return { 
    };
  },
  mounted() {},  //  this.$nextTick(()=>this.localVal = null);
  computed: {},
  watch: { 
  },
  methods: {
    clickHandler(e) {
       this.$emit('click', e.target.checked)
    }
  }
};
</script>

<style scoped lang="scss"> 
.check-text{
  vertical-align: sub;
}
/* The container */
.check-container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 16px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.check-container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 22px;
  width: 22px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.check-container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.check-container input:checked ~ .checkmark {
  background-color: #42b983;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.check-container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.check-container .checkmark:after {
    left: 7px;
    top: 3px;
    width: 8px;
    height: 13px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
