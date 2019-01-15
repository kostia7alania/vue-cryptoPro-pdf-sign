export let computeds_store = {
  methods: {
    goTour(e){ 
        this.$nextTick( () => { if(this.tour) {
          if( e == 3 ) {
           // this.$intro('.cert-list-rows');
            introJs().goToStep(3).start();
          }
          else{this.$intro().start();}
        }
      }); //если включен режим тура,то врубаем тур
    }
  },
  
  computed: {
    helpStatus() {
      return this.$store.getters.helpStatus
    },
    tour() {
      return this.$store.getters.tour
    },
  },
  beforeMount() {
    
    introJs().setOption("doneLabel", "Закрыть");
    introJs().setOption("skipLabel", "Пропустить");
    introJs().setOption("prevLabel", " << ");
    introJs().setOption("nextLabel", " > ");
  },
  mounted() {
    this.$intro()._options.doneLabel = 'Закрыть'
    this.$intro()._options.skipLabel = 'Пропустить'
    this.$intro()._options.prevLabel = '&larr; Назад'
    this.$intro()._options.nextLabel = 'Далее &rarr;'

    
    window.t = this;
    if (this.tour) {
      ///this.$intro().start();
    }
  },
  data() {
    return {
      step1: `Первым делом, чтобы подписать документ, мы должны получить список сертификатов,
    установленных на компьетере. Пожалуйста, убедитесь, что флешка с подписью вставлена в компьютер. 
    Затем нажмите "Получить список сертификатов"`,
    step1_update: `Данная кнопка обновит список сертификатов`,
      step2: `
Выберите сертификат
`,
      step3: `
Убедитесь, что Вы выбрали правильный сертификат
`,
step4: `
      Коли все верно, можете смело нажимать "Выбрать положение" и Вы перейдете на шаг выбора положения "видимой печати", которая будет видна при просмотре через Adobe Reader
`
    }
  }
}
