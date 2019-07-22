
const state = {
  production: 1, // 0 тест, 1 - продакшн
  saveState: 0,//как изначально работать с сохранением состояния (после обновления стр);
  helpStatus: 'main', ////main,feedback,interactive
  tour: false, //включен ли режим тура;

  IMG_URL: 'http://192.168.201.118:8080/api',
  BACKEND_URL: './api',//'http://localhost:8383/rest-api',
  CERT_LIST: [],
  SELECTED_CERT_OBJ: null,
  SELECTED_CERT_INFO: null,//из промисов // selected_sert
  SELECTED_CERT_BASE64: null, // вычисляется плагином промисом;
  HASH_VALUE: null,
  CACHE_OBJECT_ID: null,
  CREATED_SIGN: null,
  BASE64_BINARY: null,

  LAST_ERROR: null,

  STAMP_IMG: null,
  DOC_PREV: null,

  PECHAT_POS: { x: 0, y: 0, w: 0, h: 0, opacity: 0 },

  GET_PREVIEW_POST_DATA: null /* {
      cert_base64: this.cert64,
  pechat_pos: this.pechat_pos,
  selected_sert: this.selected_sert
} */
};
export default state
