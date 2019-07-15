<template>
  <div class="cert_list">

    <b-button v-if="CERT_LIST" variant="light " @click="getCertList"> <img src="../img/update.png" alt="list-icon" class="list-icon">
    Обновить список</b-button>

    <button v-if="!CERT_LIST"
              v-intro="step1_update"
              v-intro-step="1"
              data-step='1'
              v-intro-scroll-to="'tooltip'"
              v-intro-position="'bottom-left-aligned'"
              class="btn-3d-1"
              @click="getCertList"
    >
      <img src="../img/list-icon.png" alt="list-icon" class="list-icon">
      Получить список сертификатов
    </button>

    <div v-if="CERT_LIST">
      <h2>Выберите сертификат:</h2>
      <mySelect data-step='2' v-intro="step2" v-intro-step="2" v-model="sertSelectHandler" :options="CERT_LIST" />
    </div>

    <div v-if="selected_sert">

      <div data-step='3' v-intro="step3" v-intro-step="3" class="cert-list-rows">
        <h2>Информация о сертификате</h2>
          <div>Владелец:    <b>{{selected_sert.Name?selected_sert.Name:''}}</b></div>
          <div>Издатель:    <b>{{issuer_comp}}</b></div>

          <div>Выдан: <b>{{selected_sert.ValidFromDate | dateTimeFilter}}</b> </div>
          <div>Действителен до: <b>{{selected_sert.ValidToDate | dateTimeFilter}}</b> </div>

          <div>Статус:   <b>{{selected_sert.IsValid?'Действителен':'Не действителен'}}</b></div>
          <!--
            <div v-if="selected_sert.Version==3">Алгоритм ключа: <b>ГОСТ Р 34.10-2001</b></div>
            <div>Установлен в хранилище:    <b>{{selected_sert.HasPrivateKey?'Да':'Нет'}}</b></div>
          -->
          <!--<div><b>Имя:</b>  {{selected_sert.Subject.G?selected_sert.Subject.G:'' + " "
                            + selected_sert.Subject.SN?selected_sert.Subject.SN:''
                            + selected_sert.Subject.T?" (" + selected_sert.Subject.T + ")":''}}</div>
            <div><b>Адрес:</b>  {{selected_sert.Subject.STREET?selected_sert.Subject.STREET:''}}</div>
          -->
            <!--<p>Серийный №:    {{selected_sert.SerialNumber}}</p> "CN=ООО «НОВАГ-СЕРВИС», O=ООО «НОВАГ-СЕРВИС», STREET="ул. Комсомольская, д. 40, пом. 12", L=Краснодар, S=23 Краснодарский край, C=RU, ИНН=002315067718, ОГРН=1022302386028, E=NovAG@tax23.ru"
            <div>Отпечаток SHA1:    {{selected_sert.Thumbprint}}</div>
          -->
          <div style="color:red" v-if="sert_date_check">Сертификат просрочен</div>
      </div>
      <button data-step='4' v-intro="step4" v-intro-step="4" class="btn-3d-1" :class="{disabled: !IsValid_cert_comp}" @click="upsend_handler">Выбрать положение</button>
    </div>
  </div>
</template>

<script>
export default {
  components: { mySelect: () => import("../components/my-select") },
  name: "App-Get-Cert-List",
  data() {
    return {};
  },
  watch: {
    SELECTED_CERT_OBJ(neww) {
      this.$store.commit("SET_SELECTED_CERT_INFO", null);
      this.$store.commit("SET_SELECTED_CERT_BASE64", null);
      if (!neww) return;
      this.$store.dispatch("GET_SELECTED_CERT_INFO");
      this.$store.dispatch("GET_SELECTED_CERT_BASE64");
    }
  },
  filters: {
    dateTimeFilter(str) {
      const e = new Date(str);
      return `${e.getDate()}.${e.getMonth() +
        1}.${e.getFullYear()} ${e.toLocaleTimeString()}`;
    }
  },
  computed: {
    cert64() {
      return this.$store.state.SELECTED_CERT_BASE64;
    },
    selected_sert() {
      return this.$store.state.SELECTED_CERT_INFO;
    },
    SELECTED_CERT_OBJ() {
      return this.$store.state.SELECTED_CERT_OBJ;
    },

    sertSelectHandler: {
      get() {
        return this.SELECTED_CERT_OBJ;
      },
      set(obj) {
        $(".ui-draggable").remove();
        this.$store.commit("SET_SELECTED_CERT_OBJ", obj);
      }
    },

    issuer_comp() {
      let str = this.selected_sert.IssuerName;
      const regex = /CN=([^,]*)/gim;
      let m;

      while ((m = regex.exec(str)) !== null) {
        // This is necessary to avoid infinite loops with zero-width matches
        if (m.index === regex.lastIndex) regex.lastIndex++;
        return m && m.length > 0 ? m[1] : " - ";
      }
    },
    sert_date_check() {
      this.$nextTick(() => {
        let s = this.selected_sert;
        if (!s) return;
        let f1 = +s.ValidFromDate < +new Date();
        let f2 = +new Date() < +s.ValidToDate;
        return !(f1 && f2);
      });
    },
    CERT_LIST() {
      return this.$store.getters.CERT_LIST_GETTER;
    },
    IsValid_cert_comp() {
      let s = this.selected_sert;
      return (typeof s == "object" && s.IsValid) || false; //true = валидно
    }
  },
  methods: {
    upsend_handler() {
      if (this.IsValid_cert_comp) {
        this.$store.commit("SET_BASE64_BINARY", null);
        this.$store.dispatch("GET_PREVIEW", "preview"); //stamp_prev: 0-final, 1-prev;
      } else
        swal("Hello world!", {
          className: "red-bg",
          icon: "error",
          text:
            "Сертификат недействительный! Выберите действительный сертификат!"
        });
    },
    getCertList() {
      this.$store.dispatch("GET_CERT_LIST");
    }
  }
};
</script>

<style scoped lang="scss">
.cert_list {
  text-align: center;
  flex: 10;
  display: flex;
  flex-direction: column;
  .cert-list-rows {
    background-image: url(../img/cert.png); /*<=ПЕРЕНЕС В MOUNTED (в продакшене чеб мог менять урл картинок!) UPD 24.4.2019> ВЕРНУЛ НАХ обратнО.!.*/
    border: 5px double #5a9251;
    padding: 10px;
    margin: 15px 0px 22px 0px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 10px 50px;
    & > div {
      align-self: flex-start !important;
      text-align: left;
      padding-top: 5px;
    }
    h2 {
      text-align: center;
      width: 100%;
    }
  }
}
</style>

<style lang="scss">
.list-icon {
  width: 20px;
  vertical-align: middle;
}
</style>
