export let computeds_store = {
  methods: {
    getArrayLocalStorage(name){
      if (localStorage.getItem(name)) {
        try { this[name] = JSON.parse(localStorage.getItem(name)); } catch(e) { localStorage.removeItem(name); }
      }
    },
    saveArrayToLocalStorage(name, val){
      const ls = JSON.stringify(val);
      localStorage.setItem(name, ls);//пишем в любом случае в локал стораг!
    },


    saveToStoreAndLocalStorage(name, val){
      if( typeof val == 'undefined' ) val = localStorage[name]; //если не пришло значение, то ищем в локалстораге
      else localStorage.setItem(name, val);//если пришло значение, то пишем в локалстораг 
      this.$store.commit('changeData', { prop: name, state: val } ); //и сгружаем все в стор vueEX
    },
    
    getParamLocalStorage(e){
      let local = localStorage[e];
      if (local) this[e] = local 
    },
    saveParamToLocalStorage(name,val){
      localStorage.setItem(name, val);
    },
    goTour(e){ 
        this.$nextTick( () => { 
          if(this.tour) {
            introJs.start();  //this.$intro.start();
            if( e == 3 )  introJs.goToStep(3).start();// this.$intro('.cert-list-rows'); 
        }
      }); //если включен режим тура,то врубаем тур
    }
  },
  
  computed: {
    saveState() {
      return this.$store.state.saveState
    },
    helpStatus() {
      return this.$store.getters.helpStatus
    },
    tour() {
      return this.$store.getters.tour
    },
  },
  created() {
    
    introJs.setOption("doneLabel", "Закрыть");
    introJs.setOption("skipLabel", "Пропустить");
    introJs.setOption("prevLabel", "&larr; Назад");
    introJs.setOption("nextLabel", "Далее &rarr;");
  //  introJs._options.nextLabel = 'sex'

  },
  mounted() {

  /*  this.$intro()._options.doneLabel = 'Закрыть'
    this.$intro()._options.skipLabel = 'Пропустить'
    this.$intro()._options.prevLabel = '&larr; Назад'
    this.$intro()._options.nextLabel = 'Далее &rarr;'
*/
    
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
Здесь можно выбрать сертификат, которым хотите подписать документ
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
