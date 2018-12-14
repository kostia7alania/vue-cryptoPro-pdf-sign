<template>
  <div @click="click_handler" @keydown.esc="keyup_handler" class="vueSupport">
    <div class="get-help-row" @click="showModal = true">
      <span>
        <img src="../img/help-icon.png" alt="help-icon">
      </span>
      <span>Помощь</span>
    </div>
    <!-- используй компонент modal, чтобы спустить туда пропсы --> 
    <modal v-if="showModal" @close="showModal = false">  
      <h3 slot="header">Помощь</h3>
      <div slot="body">
        <div>
            <div class="get-help-row" @click="showModal = true">
                <span>
                    <img src="../img/help-icon.png" alt="help-icon">
                </span>
                <span class="link">Интерактивная помощь</span>
            </div>
          
        </div>     
        <div>
            <h4>Оценить работу приложения</h4>
            <star-rating @rating-selected="rating = $event" :rating="rating"/>
            <br>Ваш отзыв:
            <p>
            <textarea v-model="feedback_text"></textarea>
            </p>
        </div>
      </div> 
      <template slot="footer"> 
        <button class="btn-3d-1" @click="showModal = false">Отправить</button>
      </template>
    </modal>
  </div>
</template>

<script>
let modal = {
  template: `  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header"> default header </slot>
          </div>

          <div class="modal-body">
            <slot name="body"> default body </slot>
          </div>

          <div class="modal-footer">
            <slot name="footer"> <!--default footer--> <button class="modal-default-button" @click="$emit('close')"> OK </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>`
};

import axios from "axios";
import StarRating from "vue-star-rating";
export default {
  components: { StarRating, modal },
  props: [],
  data() {
    return {
      rating: 0,
      showModal: false,
      feedback_text:null
    };
  },
  mounted() {
// подписываемся на событие keydown
    if (typeof document !== 'undefined') {
      document.body.addEventListener('keydown', this.handleTabKey)
    }
    if(this.$el.focus) {
      this.$el.focus() // фокус переводим на окно, при монтировании
    }
},
 destroyed() {
// отписываемся
    if (typeof document !== 'undefined') {
      document.body.removeEventListener('keydown', this.handleTabKey)
    }
},
  methods: {
    handleTabKey(e) {
        console.log('handleTabKey(e)=>>>',e)
       if (e.keyCode === 9 && this.modals.length) {
         e.preventDefault() // если есть окна, глушим Tab/Shift-Tab
       } // пока полностью отключаю Tab. Надо подумать, как лучше его глушить только вне активного окна. 
    },
      keyup_handler(e){
          this.showModal = false;
      },
      click_handler(e){
          console.log(e);
          window.e = e;
          if(e.path[0].classList.value == 'modal-wrapper') {
              console.log('клик во враппер модального!');
              this.showModal = false;
          }
          
      },
    ratingSelected(e) {
      console.log("rating-selected>>>>", e);
    }
  }
};
</script>
<style   lang="scss">

textarea {
    width: 100%;
    max-width: 300px;
}


.link {
    color:green;  
}
.get-help-row {
  display: flex;
  flex-direction: column;
  transition: .4s; 
  align-items: center;
  cursor:pointer;


  padding: 7px 19px;
  border-radius: 2px;
  background-color: white;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);

  &:hover {
    background-color: #264b6c30;
    transform: scale(1.1);
  }
}

.modal-mask {
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

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container { 

    display: flex;
    flex-direction: column;
    align-items: center;
  
  width: 300px;
  margin: 0px auto;
  padding: 20px 20px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header {
        border-bottom: 1px solid #2f9d6b;
        width: 100%;
        text-align: center;
    h3 {
        margin: 0;
        color: #42b983;
    }
}

.modal-body {
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

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
