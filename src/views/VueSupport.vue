<template>
  <div @click="click_handler" @keydown.esc="closeModal" class="vueSupport">
    <div class="get-help-row" @click="showModal = true">
      <img src="../img/help-icon.png" alt="help-icon">
      <span>Помощь</span>
    </div>


    <!-- используй компонент modal, чтобы спустить туда пропсы -->
    <app-modal v-show="showModal" @close="closeModal">

      <div slot="header">
        <div v-if="helpStatus == 'main'"></div>
        <my-button v-if="helpStatus == 'feedback'" class="back" @click="changeState('main')" text="<" />
        <div><strong>Помощь</strong></div>
        <my-button class="close" @click="closeModal" text="x"/>
      </div>



      <div slot="body">

        <div v-if="helpStatus == 'main'">



            <div class="get-help-row" @click="goTour">
              <img src="../img/help-icon.png" alt="help-icon">
              <span class="link">Помощь с интерфейсом</span>
            </div>

            <div class="get-help-row" @click="changeState('feedback')">
              <img src="../img/feedback.png" alt="help-icon">
              <span class="link"  v-b-tooltip title="Отправить отчет об ошибке">Обратная связь</span>
            </div>
            <div class="check">
              <my-checkbox :checked="saveState" @click="saveStateChange" text="Запоминать состояние"/>
            </div>


        </div>
        <FeedBack :helpStatus="helpStatus" v-if="helpStatus == 'feedback'"/>


      </div>

     <!--<template slot="footer"> <!--<button class="btn-3d-1" @click="showModal = false">Закрыть</button>-- >  </template>-->
    </app-modal>

  </div>
</template>

<script>  
export default {
  name: "vue-support",
  components: {
    appModal: () => import('./Modal'), 
    FeedBack: () => import('./FeedBack.vue'),
    myButton : () => import('../components/my-button.vue')
  },
  data() {
    return {
      rating: null,
      showModal: false,
      feedback_text: null
    };
  },
  watch: {},
  mounted() {
    // подписываемся на событие keydown
    if (typeof document !== "undefined") {
      document.body.addEventListener("keydown", this.handleTabKey);
    }
    if (this.$el.focus) {
      this.$el.focus(); // фокус переводим на окно, при монтировании
    }
  },
  destroyed() {
    // отписываемся
    if (typeof document !== "undefined") {
      document.body.removeEventListener("keydown", this.handleTabKey);
    }
  }, 
  methods: {
    saveStateChange(val) {},
    goTour() {
      this.$store.commit("changeData", { prop: "tour", state: true });
      introJs.start();
      //
      //this.$intro().start();
      /*
        document.querySelector('.introjs-skipbutton').innerText = 'Пропустить';
        document.querySelector('.introjs-prevbutton').innerText = '← Назад';
        document.querySelector('.introjs-nextbutton').innerText = 'Далее →';
        document.querySelector('.introjs-donebutton').innerText = 'Закрыть';
      */

      this.showModal = false;
    },
    closeModal() {
      this.showModal = false;
      this.changeState("main");
    },
    changeState(state) {
      this.$store.commit("changeData", { prop: "helpStatus", state: state });
    },
    handleTabKey(e) {
      console.log("handleTabKey(e)=>>>", e);
      if (e.keyCode === 9 && this.modals.length) {
        e.preventDefault(); // если есть окна, глушим Tab/Shift-Tab
      } // пока полностью отключаю Tab. Надо подумать, как лучше его глушить только вне активного окна.
    },
    click_handler(e) {
      if (e.path[0].classList.value == "my-modal-wrapper") {
        console.log("клик во враппер модального!");
        this.closeModal();
      }
    }
  }
};
</script>

<style scoped lang="scss">
.check {
  padding-top: 10px;
}
.header {
  font-weight: 888;
  font-size: 15px;
}
</style>

<style lang="scss">
.back {
  font-size: 30px;
}

.vueSupport {
  display: flex;
}

.get-help-row {
  display: flex;
  flex-direction: column;
  transition: 0.4s;
  align-items: center;
  cursor: pointer;
  padding: 7px 19px;
  /*border-radius: 2px;*/
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
  color: white;
  &:hover {
    background-color: #264b6c30;
    transform: scale(1.1);
  }
  img {
    width: 21px;
  }
}

.my-modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.my-modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.my-modal-container {
  display: flex;
  flex-direction: column;
  align-items: center;


  width: 300px;
  margin: 0px auto;
  padding: 20px 20px 20px 20px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.my-modal-header {
  border-bottom: 1px solid #2f9d6b;
  width: 100%;
  text-align: center;
  padding-bottom: 10px;
  margin-bottom: 10px;
  & > div {
    margin: 0;
    color: #42b983;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
}

.my-modal-body {
  margin: 10px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .my-modal-container,
.modal-leave-active .my-modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
