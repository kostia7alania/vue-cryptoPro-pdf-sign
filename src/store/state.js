
const state = {
  production: 1, // 0 тест, 1 - продакшн
  backend_url: '',//'http://localhost:8383/rest-api',
  saveState: 0,//как изначально работать с сохранением состояния (после обновления стр);
  helpStatus: 'main', ////main,feedback,interactive
  tour: false //включен ли режим тура;
};
export default state
