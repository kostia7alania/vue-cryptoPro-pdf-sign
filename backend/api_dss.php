<?php   //echo   exec('whoami'); 
ini_set('error_reporting', E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With, Cache-Control, Pragma, Accept, Accept-Encoding');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    } 
 
//header('Content-Type: application/x-www-form-urlencoded');
 
 header("Content-type: text/xml;charset=window-1251");
 header('Content-Type:text/html;charset=UTF-8');
 
header('Content-Type: application/json;charset=utf-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8');
$_POST = json_decode(file_get_contents("php://input"),true);
// echo '_POST: '; var_dump($_POST); //echo '<br>GET: '; var_dump($_GET); //echo '<hr>';die; 
require('api_dss_classes.php');
require('api_dss_functions.php');
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
 
if(isset($_GET['ssid'])){   session_id($_GET['ssid']);  session_start();  $ssid = $_GET['ssid'];     }
if(!isset($_SESSION)){      session_start();            $ssid = session_id();   }

if(isset($_POST['cert_base64'])){
                $_SESSION['cert_base64'] = $_POST['cert_base64'];    
                $rawCertificate = $_POST['cert_base64'];
} elseif (isset($_SESSION['cert_base64']))                           
                $rawCertificate = $_SESSION['cert_base64'];

//$rawCertificate	=	'MIIDTTCCAvygAwIBAgITEgAo/xmgmogykwRyHAAAACj/GTAIBgYqhQMCAgMwfzEjMCEGCSqGSIb3DQEJARYUc3VwcG9ydEBjcnlwdG9wcm8ucnUxCzAJBgNVBAYTAlJVMQ8wDQYDVQQHEwZNb3Njb3cxFzAVBgNVBAoTDkNSWVBUTy1QUk8gTExDMSEwHwYDVQQDExhDUllQVE8tUFJPIFRlc3QgQ2VudGVyIDIwHhcNMTgwNTIyMDU1MDA2WhcNMTgwODIyMDYwMDA2WjAUMRIwEAYDVQQDDAlTVFJPTkdHZ2cwgaowIQYIKoUDBwEBAQIwFQYJKoUDBwECAQIBBggqhQMHAQECAwOBhAAEgYC6r8fqmZVixavsbN5jwzJwDC99v+ULJsqrvckJio2BDx9kXXtX/K78isuQcC2eTxkL78Hth01j4F3MPuWszokQw1UCO3u+pxB6ixbU0e6EB9FV5FniFqyj7sfVykR2jGJlG96GN1zio5ySBUuoNjOauArA2Pi8wuM3wtO/Ie2q6aOCAXAwggFsMA4GA1UdDwEB/wQEAwIE8DATBgNVHSUEDDAKBggrBgEFBQcDAjAdBgNVHQ4EFgQUJPtZJdx2r7uH7CSlxg3csra0fPkwHwYDVR0jBBgwFoAUFTF8sI0a3mbXFZxJUpcXJLkBeoMwWQYDVR0fBFIwUDBOoEygSoZIaHR0cDovL3Rlc3RjYS5jcnlwdG9wcm8ucnUvQ2VydEVucm9sbC9DUllQVE8tUFJPJTIwVGVzdCUyMENlbnRlciUyMDIuY3JsMIGpBggrBgEFBQcBAQSBnDCBmTBhBggrBgEFBQcwAoZVaHR0cDovL3Rlc3RjYS5jcnlwdG9wcm8ucnUvQ2VydEVucm9sbC90ZXN0LWNhLTIwMTRfQ1JZUFRPLVBSTyUyMFRlc3QlMjBDZW50ZXIlMjAyLmNydDA0BggrBgEFBQcwAYYoaHR0cDovL3Rlc3RjYS5jcnlwdG9wcm8ucnUvb2NzcC9vY3NwLnNyZjAIBgYqhQMCAgMDQQB+Ty/WjMuVPiNsy1liF5tYHW4sJvIiM8Nhl0iKX0HWl2jCUKHSqiM+2QuP28dnuETGLPdDKWDlV/5teoyS7/F9';
$verifyingCert = VerifyCertificate($rawCertificate)["msg"]["Result"];            // проверка сертификата на валидность!

if(!$rawCertificate) echo_end_die(["stat"=>0,"msg"=> 'Cert is undefined!']);
if($verifyingCert) echo_end_die(["stat"=>0,"msg"=> $rawCertificate]); // поправить на продакшене!  (Походу -тут надо добавить отрицание условия )

sleep(1);
$url            =   'https://ssd.marinet.ru/ssd/LiteService.svc?wsdl'; 
$fixX=236;  //189; размер печати
$fixY=99;   //100  

//$path = 'C:/inetpub/wwwroot/wp_simple/cryptopro-pdf-sign-in-browser-with-vuejs/processing';
$path = './processing';
$example_doc = "$path/example.pdf"; 

if($_GET['stampGen']==1) { //PREVIEW doc SIGN:
    $RectLowerLeftX = 1;
    $RectLowerLeftY = 1;
    
    $template = getStamp ($path, $RectLowerLeftX, $RectLowerLeftY, $fixX, $fixY);
    if( $_GET['action'] == 'sign' ) {
            $doc_name = "stamp_white_templ";
            if( $_GET['stage'] == 1 ) { 
                exec('convert -size '.($fixX+2).'x'.($fixY+2)." xc:white $path/$doc_name.pdf"); // создаем белый PDF  189x100   
                $document = base64_encode(file_get_contents("$path/$doc_name.pdf", 1));         // читаем его из диска;
                stage1($ssid, $url, $rawCertificate, $template, $classmap, $document);//<--die            // <------------------- подписываем его <-------------------
            } elseif ( $_GET['stage'] == 2 ) {
                $document = base64_encode(file_get_contents("$path/$doc_name.pdf", 1));
                $a = stage2($url, $rawCertificate, $template, $classmap, $document); 
                    $document = $a["base64Binary"];
                    $docName_stage2 = $doc_name."_stage2";
                    base64save($document, "$path/$docName_stage2.pdf"); //base64  .pdf  -> .pdf
                    //pdf -> transparent PNG:
                    exec("convert -density 200 -alpha remove -alpha off +antialias -transparent white $path/$docName_stage2.pdf -transparent white $path/$docName_stage2.png");
                    $base64Binary = base64_encode(file_get_contents("$path/$docName_stage2.png"));  
                                 
                    exec("convert -density 200 $example_doc"."[0] $example_doc.jpg");
                    $doc_prev = base64_encode(file_get_contents("$example_doc.jpg"));

                    echo_end_die([
                        "stat"       =>  1,
                        "base64Binary"=> $base64Binary,
                        "doc_prev"    => $doc_prev,
                        "ssid"        => $ssid
                    ]);
                    //echo $base64Binary."[BREAK]".$doc_prev; die;   //stamp_img
            } else { echo_end_die(["stat"=>0,"msg"=> 'Stage required']); }
    }    else    {  echo_end_die(["stat"=>0,"msg"=> 'Unknown stampGen-action']); } 

        
} else {//финальная стадия проставления печати

    $RectLowerLeftX = $_POST['pechat_pos']['x'];//правый край max -> 595 //дроби не любит ПДФ !онли целое!//json_decode($_POST['pechat_pos'])->x;
    $RectLowerLeftY = $_POST['pechat_pos']['y'];//верхний край max-> 842 //дроби не любит ПДФ  !онли целое!//json_decode($_POST['pechat_pos'])->y;

    $template = getStamp ($path, $RectLowerLeftX, $RectLowerLeftY, $fixX, $fixY);
    $document = base64_encode ( file_get_contents($example_doc, 1) ); // <-- SIGN;
    if($_GET['action']=='sign')  {
        if(    $_GET['stage']==1){
            stage1($ssid, $url, $rawCertificate, $template, $classmap, $document);//<--тут die; // <------------------- подписываем его <-------------------
        }
        elseif($_GET['stage']==2){
            $a = stage2($url, $rawCertificate, $template, $classmap, $document); //<--тут die; // <------------------- подписываем его <-------------------         
            echo_end_die(["stat"=>1,"base64Binary"=> $a['base64Binary']]);
        }
        else  { echo_end_die(["stat"=>0,"msg"=> 'Stage required']);}
    } else {  echo_end_die(["stat"=>0,"msg"=> 'Unknown stampGen-action']);  } 
}

?>