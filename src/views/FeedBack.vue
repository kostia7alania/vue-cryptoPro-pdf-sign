<template>
  <div>
    
    <my-checkbox :checked="showRate" @click="showRate=$event" text="Оценить работу приложения"/>
 
    <star-rating v-show="showRate" @rating-selected="ratingSelected" :rating="rating"/>
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
export default { 
  name: "FeedBack",
  components: { StarRating },
  data() {
    return {
      attachUserData: true,
      showRate: false,
      rating: null,
      feedback_text: null
    };
  },
  computed: { },
  methods: { 
    sendFeedBack() {
      console.log("sendFeedBack");
      this.$store.dispatch("sendFeedBack", {
        action: 'send',
        data: this._data
      });
    }, 
    ratingSelected(e) {
      this.rating = e;
    }
  }
};
</script>

 
<style scoped lang="scss">
  .comp-header {
    font-weight: 888;
    font-size: 15px;
  }
  label {
    margin-left: 4px;
  }
</style> 
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


textarea {
    margin: 0px;
    height: 29px;
    width: 293px;
    min-height: 35px;
    min-width: 274px;
    max-width: 274px;
}

    
</style>
