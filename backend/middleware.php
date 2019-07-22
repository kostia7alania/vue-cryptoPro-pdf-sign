<?php   //echo   exec('whoami');
error_reporting(E_ALL); ini_set('error_reporting', E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Origin: *');
set_time_limit(0);
header('who_ami: ' . exec('whoami'));
ob_clean();
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Headers: token, Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With, Cache-Control, Pragma, Accept, Accept-Encoding');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
}
ob_clean();

/*
  header('Content-type: image/png');
  $image = new Imagick(__DIR__.'/processing/gerb_RF.png');
  $image->thumbnailImage(100, 0); // Если в качестве ширины или высоты передан 0,то сохраняется соотношение сторон
  echo $image;
  die;
*/
header('Content-Type: application/json;charset=utf-8');mb_internal_encoding('UTF-8');mb_http_output('UTF-8');mb_http_input('UTF-8');mb_regex_encoding('UTF-8');
$_POST = json_decode(file_get_contents("php://input"),true);
require('api_dss_classes.php'); require('api_dss_functions.php');
count($_GET)==0? echo_end_die(["stat"=>0,"msg"=>"Empty get params!"]):''; //отсекаем прямое открывание пхп-шки;
//exec("convert -size 230x130 -background lightblue -font verdana.ttf -pointsize 25 \\\-gravity NorthWest caption:\"The quick red foxjumped over thelazy brown dog.\" \\\-flatten caption4.jpg");
//$document = base64_encode(file_get_contents('./example.pdf', 1));     //$document 		=	'data:application/pdf;base64,JVBERi0xLjQKJcKlwrEKCgoKMSAwIG9iagogIDw8IC9UeXBlIC9DYXRhbG9nCiAgICAgL1BhZ2VzIDIgMCBSCiAgPj4KZW5kb2JqCgoyIDAgb2JqCiAgPDwgL1R5cGUgL1BhZ2VzCiAgICAgL0tpZHMgWzMgMCBSXQogICAgIC9Db3VudCAxCiAgICAgL01lZGlhQm94IFswIDAgMzAwIDE0NF0KICA+PgplbmRvYmoKCjMgMCBvYmoKICA8PCAgL1R5cGUgL1BhZ2UKICAgICAgL1BhcmVudCAyIDAgUgogICAgICAvUmVzb3VyY2VzCiAgICAgICA8PCAvRm9udAogICAgICAgICAgIDw8IC9GMQogICAgICAgICAgICAgICA8PCAvVHlwZSAvRm9udAogICAgICAgICAgICAgICAgICAvU3VidHlwZSAvVHlwZTEKICAgICAgICAgICAgICAgICAgL0Jhc2VGb250IC9UaW1lcy1Sb21hbgogICAgICAgICAgICAgICA+PgogICAgICAgICAgID4+CiAgICAgICA+PgogICAgICAvQ29udGVudHMgNCAwIFIKICA+PgplbmRvYmoKCjQgMCBvYmoKICA8PCAvTGVuZ3RoIDU1ID4+CnN0cmVhbQogIEJUCiAgICAvRjEgMTggVGYKICAgIDAgMCBUZAogICAgKEhlbGxvIFdvcmxkKSBUagogIEVUCmVuZHN0cmVhbQplbmRvYmoKCnhyZWYKMCA1CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDAxOCAwMDAwMCBuIAowMDAwMDAwMDc3IDAwMDAwIG4gCjAwMDAwMDAxNzggMDAwMDAgbiAKMDAwMDAwMDQ1NyAwMDAwMCBuIAp0cmFpbGVyCiAgPDwgIC9Sb290IDEgMCBSCiAgICAgIC9TaXplIDUKICA+PgpzdGFydHhyZWYKNTY1CiUlRU9GCg==';
//$path = 'C:\\inetpub\\wwwroot\\crypto-pro\\example';
/*если с общепринятыми бумажными форматами и их размерами народ знаком (А4 — это лист 297×210 мм), то с переводом их в пиксели возникают сложности.
Количество пикселей для листа А4 зависит от параметра DPI, который вы применяете:
при dpi=75, А4 имеет 877×620 px. при dpi=150, А4 имеет 1754×1240 px.*/
//А4 =>>> width:595pt; height:  842pt;// точно в ПТ ? -> х-з..
//А4 =>>> width:793px; height: 1123px;
/*
var pt = 1;
var px = (4/3)*pt ///1.(3)
var em = pt/12 ////0.08(3)
var percent = pt*100/12 ///8.(3)
!!!! ! !  !  ! ==>> A4 =>> 595x842 <= .PDF <==== ! ! ! ! !
А4 — 297×210 мм:
297*0,3528 = 104,7816 pt
210*0,3528 = 74,08800 pt
=> real A4 in PoinTs: 104,7816 pt x 74,08800 pt
=> real A4 in PiXels: 139.709 px x 98.784 px
*/
/*Параметры шаблонов подписи.
-- Размеры.
Размеры всех элементов в представлении подписи и в шаблоне указываются в единицах
измерения – типографских пунктах Adobe (points). 1 пункт = 1/72 дюйма = 0,3528 мм.
-- Система координат.
Положение представления подписи на странице задаётся в системе координат PDF документа.
Точка с координатами (0, 0) соответствует левому нижнему углу страницы
*/

if(!isset($_GET['action']) && $_GET['action'] != 'sign')  echo_end_die(["stat"=>0,"msg"=> 'Unknown action']);
if(!isset($_GET['stage']) && ($_GET['stage'] == 1 || $_GET['stage']==2)) echo_end_die(["stat"=>0,"msg"=> 'Stage required']);


if(isset($_POST['cert_base64'])) $_SESSION['cert_base64'] = $_POST['cert_base64'];
elseif (!isset($_SESSION['cert_base64'])) { echo json_encode ( ['stat'=>0, 'msg'=>'Cert not found!'] ); die;} // чувак вручную открыл ссылку
$rawCertificate = $_SESSION['cert_base64'];
//$rawCertificate	=	'MIIDTTCCAvygAwIBAgITEgAo/xmgmogykwRyHAAAACj/GTAIBgYqhQMCAgMwfzEjMCEGCSqGSIb3DQEJARYUc3VwcG9ydEBjcnlwdG9wcm8ucnUxCzAJBgNVBAYTAlJVMQ8wDQYDVQQHEwZNb3Njb3cxFzAVBgNVBAoTDkNSWVBUTy1QUk8gTExDMSEwHwYDVQQDExhDUllQVE8tUFJPIFRlc3QgQ2VudGVyIDIwHhcNMTgwNTIyMDU1MDA2WhcNMTgwODIyMDYwMDA2WjAUMRIwEAYDVQQDDAlTVFJPTkdHZ2cwgaowIQYIKoUDBwEBAQIwFQYJKoUDBwECAQIBBggqhQMHAQECAwOBhAAEgYC6r8fqmZVixavsbN5jwzJwDC99v+ULJsqrvckJio2BDx9kXXtX/K78isuQcC2eTxkL78Hth01j4F3MPuWszokQw1UCO3u+pxB6ixbU0e6EB9FV5FniFqyj7sfVykR2jGJlG96GN1zio5ySBUuoNjOauArA2Pi8wuM3wtO/Ie2q6aOCAXAwggFsMA4GA1UdDwEB/wQEAwIE8DATBgNVHSUEDDAKBggrBgEFBQcDAjAdBgNVHQ4EFgQUJPtZJdx2r7uH7CSlxg3csra0fPkwHwYDVR0jBBgwFoAUFTF8sI0a3mbXFZxJUpcXJLkBeoMwWQYDVR0fBFIwUDBOoEygSoZIaHR0cDovL3Rlc3RjYS5jcnlwdG9wcm8ucnUvQ2VydEVucm9sbC9DUllQVE8tUFJPJTIwVGVzdCUyMENlbnRlciUyMDIuY3JsMIGpBggrBgEFBQcBAQSBnDCBmTBhBggrBgEFBQcwAoZVaHR0cDovL3Rlc3RjYS5jcnlwdG9wcm8ucnUvQ2VydEVucm9sbC90ZXN0LWNhLTIwMTRfQ1JZUFRPLVBSTyUyMFRlc3QlMjBDZW50ZXIlMjAyLmNydDA0BggrBgEFBQcwAYYoaHR0cDovL3Rlc3RjYS5jcnlwdG9wcm8ucnUvb2NzcC9vY3NwLnNyZjAIBgYqhQMCAgMDQQB+Ty/WjMuVPiNsy1liF5tYHW4sJvIiM8Nhl0iKX0HWl2jCUKHSqiM+2QuP28dnuETGLPdDKWDlV/5teoyS7/F9';
$verifyingCert = VerifyCertificate($rawCertificate)["msg"]["Result"];            // проверка сертификата на валидность!
if(!$rawCertificate) echo_end_die(["stat"=>0,"msg"=> 'Cert is undefined!']);
if($verifyingCert) echo_end_die(["stat"=>0,"msg"=> $rawCertificate]); // поправить на продакшене!  (Походу -тут надо добавить отрицание условия )
sleep(1);

?>
