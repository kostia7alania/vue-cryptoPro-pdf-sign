<?
  $re = '/(\/\w*\d)\/\w*\/rightSailM/m'; // Ищем префикс подпапки (на тестах так) "/And2"
  $str = $_SERVER['REQUEST_URI'];
  preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
  $f = isset($matches[0][1]) ? $matches[0][1] : '';
?>

    <link href=<?=$f?>/vue-cryptoPro-pdf-sign/dist/css/app.css rel=preload as=style>
    <link href=<?=$f?>/vue-cryptoPro-pdf-sign/dist/css/chunk-vendors.css rel=preload as=style>
    <link href=<?=$f?>/vue-cryptoPro-pdf-sign/dist/js/app.js rel=preload as=script>
    <link href=<?=$f?>/vue-cryptoPro-pdf-sign/dist/js/chunk-vendors.js rel=preload as=script>
    <link href=<?=$f?>/vue-cryptoPro-pdf-sign/dist/css/chunk-vendors.css rel=stylesheet>
    <link href=<?=$f?>/vue-cryptoPro-pdf-sign/dist/css/app.css rel=stylesheet>
  
    <noscript><strong>We're sorry but vue-cryptopro-pdf-sign doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript>
    
    <div id=app1> </div>

    <script src=https://www.cryptopro.ru/sites/default/files/products/cades/cadesplugin_api.js> </script> 
    <script>
      window.onload = params => {
        //'https://srs.marinet.ru/testk/registrations/rightSailM/back'
        //'https://srs.marinet.ru/testk/js/vue-cryptoPro-pdf-sign/backend/api_dss.php'
        var props1 = {
          el: "#app1",
          backend_url:location.protocol +
              "//" +
              location.hostname +
              location.pathname +
              "/api", // "https://srs.marinet.ru/And2/registrations/rightSailM/back",
        };
        init_Vue(props1);
      };
    </script>
    <script
      src=<?=$f?>/vue-cryptoPro-pdf-sign/dist/js/chunk-vendors.js
    ></script>
    <script
      src=<?=$f?>/vue-cryptoPro-pdf-sign/dist/js/app.js
    > </script>

