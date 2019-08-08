<template>
  <div class="spinner">
    <div class="spinner__figure"></div>
    <div>{{loading_text}}</div>
  </div>
</template>

<script>
export default {
  name: "spinner-loading",
  props: ["loading_text"]
};
</script>

<style lang="scss">
.spinner {
  text-align: center;
}
.spinner__figure {
  width: 100px;
  height: 100px;
  background-repeat: no-repeat;
  background-position: center;
  background-image: url("data:image/svg+xml,%3Csvg class='lds-microsoft' width='80px' height='80px' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid'%3E%3Cg transform='rotate(0)'%3E%3Ccircle cx='81.73413361164941' cy='74.35045716034882' fill='%23e15b64' r='5' transform='rotate(340.001 49.9999 50)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='0s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='74.35045716034882' cy='81.73413361164941' fill='%23f47e60' r='5' transform='rotate(348.352 50.0001 50.0001)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.0625s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='65.3073372946036' cy='86.95518130045147' fill='%23f8b26a' r='5' transform='rotate(354.236 50 50)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.125s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='55.22104768880207' cy='89.65779445495241' fill='%23abbd81' r='5' transform='rotate(357.958 50.0002 50.0002)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.1875s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='44.77895231119793' cy='89.65779445495241' fill='%23849b87' r='5' transform='rotate(359.76 50.0064 50.0064)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.25s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='34.692662705396415' cy='86.95518130045147' fill='%23e15b64' r='5' transform='rotate(0.183552 50 50)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.3125s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='25.649542839651176' cy='81.73413361164941' fill='%23f47e60' r='5' transform='rotate(1.86457 50 50)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.375s'%3E%3C/animateTransform%3E%3C/circle%3E%3Ccircle cx='18.2658663883506' cy='74.35045716034884' fill='%23f8b26a' r='5' transform='rotate(5.45126 50 50)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;360 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s' begin='-0.4375s'%3E%3C/animateTransform%3E%3C/circle%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='spline' values='0 50 50;0 50 50' times='0;1' keySplines='0.5 0 0.5 1' repeatCount='indefinite' dur='1.5s'%3E%3C/animateTransform%3E%3C/g%3E%3C/svg%3E");
}
</style>
