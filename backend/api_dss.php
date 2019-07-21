<?php // ===> MAIN SCRIPT <===
  require_once('middleware.php');

const convert = 'C:\ImageMagick-7.0.8-Q16\convert';
$url            =   'https://ssd.marinet.ru/ssd/LiteService.svc?wsdl';
$fixX=236;  //189; размер печати
$fixY=99;   //100
$path = __DIR__.'./processing';
$test_doc_pdf = __DIR__."/processing/example.pdf";

if(isset ($_GET['get-signed-doc'])) {
  ob_start();ob_end_clean();
  header('Content-type: application/pdf');
  //header('Content-Disposition: attachment; filename="my.pdf"');
  echo $_SESSION['signed-doc'];
  die;
}

if($_GET['stampGen']==1) { //!!!! ! ПРЕДПРОСМОТР  !!!!!  PREVIEW doc SIGN:
    $RectLowerLeftX = 1;
    $RectLowerLeftY = 1;
    $template = getStamp ($path, $RectLowerLeftX, $RectLowerLeftY, $fixX, $fixY);
            if( $_GET['stage'] == 1 ) {
              $document__white = get_blank_pdf_base64 ($fixX+2, $fixY+2);
              stage1($url, $rawCertificate, $template, $classmap, $document__white); //<--die -подписываем БЕЛЫЙ PDF => [HashValue, СacheObjectId]
            }
            elseif ( $_GET['stage'] == 2 ) {
              if(isset($_GET['get-first-page'])) { // PDF -> 1st page -> JPG
                ob_start();ob_end_clean();
                $jpg_base64 = pdf_to_jpg_first_page($test_doc_pdf);
                ob_start();ob_end_clean();
                header('Content-Type: text/plain');
                echo "<img width=595 height=842 id=watermarked src='data:image/jpeg;base64,$jpg_base64'>";
                die;
              }
              elseif(isset($_GET['get-stamp-img'])) {
                $document__white = get_blank_pdf_base64 ($fixX+2, $fixY+2);
                $binary = stage2($url, $rawCertificate, $template, $classmap, $document__white );
                $png = binary_pdf_to_png($binary);// делаем ИЗ pdf -> прозрачный PNG:
                echo 'data:image/png;base64,'.$png; //echo_end_die([ "stat" =>  1, "base64Binary"=> "$base64Binary", "doc_prev"    => "$doc_prev" ]);
                die;
              }
            }
            echo '{stat: 0, msg: "Unknown dirrection"}';
            die;
}  // !!!!! ПОДПИСЫВАНИЕ  !!!!!! финальная стадия проставления печати
$RectLowerLeftX = $_POST['pechat_pos']['x'];//правый край max -> 595 //дроби не любит ПДФ !онли целое!//json_decode($_POST['pechat_pos'])->x;
$RectLowerLeftY = $_POST['pechat_pos']['y'];//верхний край max-> 842 //дроби не любит ПДФ  !онли целое!//json_decode($_POST['pechat_pos'])->y;
$template = getStamp ($path, $RectLowerLeftX, $RectLowerLeftY, $fixX, $fixY);
$document = base64_encode ( file_get_contents($test_doc_pdf, 1) ); // <-- SIGN;
if($_GET['stage']==1) stage1($url, $rawCertificate, $template, $classmap, $document);//<-- die
elseif($_GET['stage']==2) {
    $binary = stage2($url, $rawCertificate, $template, $classmap, $document); //<--die
    $_SESSION['signed-doc'] = $binary; //$base64Binary = 'data:application/pdf;base64,'.base64_encode($binary);
    $jpg_base64 = binary_pdf_first_page_to_jpg($binary);// делаем ИЗ pdf -> прозрачный PNG:
    ob_start();ob_end_clean();
    header('Content-Type: text/plain');
    echo "<img width=595 height=842 id=watermarked src='data:image/jpeg;base64,$jpg_base64'>";
    die;
}
?>
