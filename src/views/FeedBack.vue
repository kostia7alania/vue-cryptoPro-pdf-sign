<template>
  <div>
    <p class="header">Оценить работу приложения</p>
    <h5>Ваша оценка:</h5>
    <star-rating @rating-selected="ratingSelected" :rating="rating"/>
    <h5>Ваш комментарий:</h5>
    <textarea v-model="feedback_text"></textarea>
    <my-checkbox :checked="attachUserData" @click="attachUserData=$event" text="Прикрепить данные"/>
    <p>
      <my-button @click="sendFeedBack" :status="helpStatus" cls="btn-send" text="Отправить"/>
    </p>


    
  </div>
</template>

<script>
import { mapMutations } from 'vuex'; 
import StarRating from "vue-star-rating";
import myButton from "../components/my-button.vue";
import myCheckbox from "../components/my-checkbox.vue";
export default {
  name: "FeedBack",
  components: { StarRating, myButton, myCheckbox },
  data() {
    return {
      attachUserData: true,
      
      rating: null,
      feedback_text: null
    };
  },
  computed: { },
  methods: { 
    sendFeedBack() {
      this.$store.commit('sendFeedBack', { action: 'send', data: this._data } );
      console.log("sendFeedBack");
    }, 
    ratingSelected(e) {
      this.rating = e;
    }
  }
};
</script>


<style lang="scss">
.custom-control-input:checked ~ .custom-control-label::before {
    color: #fff;
    border-color: #3e7e61;
    background-color: #3ba676 !important; 
}
 .custom-control input, .custom-control input:label {
    cursor:pointer !important;
 }

.vue-star-rating{
  padding-bottom:4px;
}
.vue-star-rating-star {
  transition: 0.3s;
  &:hover {
    transform: scale(1.1);
  }
}
</style>

<style scoped lang="scss">
.header {
  font-weight: 888;
  font-size: 15px;
}
</style>

<style lang="scss">

textarea {
    margin: 0px;
    height: 29px;
    width: 293px;
    min-height: 35px;
    min-width: 274px;
    max-width: 274px;
}

    
</style>
