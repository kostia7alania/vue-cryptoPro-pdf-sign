﻿<?php

 function getStamp ($path, $RectLowerLeftX=33, $RectLowerLeftY = 22, $fixX = 215, $fixY = 110) {

    $a = $_POST['selected_sert'];
    /*//old branch:
        $thumbprint  = $a['thumbprint'];//->thumbprint;  // ='4B6EF3934B373724AA818A6F8EA36FF400273EBC';
        $subjectName = $a['subjectName']; //"CN="ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""", SN=Осинцев, G=Сергей Васильевич, O="ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""", OU="Филиал ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""", T=Заместитель директора ИЦГПК, STREET="ул. Нижнепортовая, д. 3", L=Владивосток, S=25 Приморский край, C=RU, СНИЛС=15154407237, ИНН=002540035227, ОГРН=1022502262925, E=OSINCEV@MARINET.RU"
        $issuerName  = $a['issuerName']; //$kemVydan = R'CN=CRYPTO-PRO Test Center 2, O=CRYPTO-PRO LLC'; // 'УЦ Минкомсвязи',
        $validFrom   = preg_split('/ /',$a['validFrom'])[0];
        $validTo     = preg_split('/ /',$a['validTo'])[0];
        $name   = $a['name'];//кому выдан - 'Иванов Иван Иванович'
        $label       = $a['label'];
    */
    $thumbprint  = $a['Thumbprint'];//->thumbprint;  // ='4B6EF3934B373724AA818A6F8EA36FF400273EBC';
    $subjectName = $a['SubjectName']; //"CN="ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""", SN=Осинцев, G=Сергей Васильевич, O="ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""", OU="Филиал ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""", T=Заместитель директора ИЦГПК, STREET="ул. Нижнепортовая, д. 3", L=Владивосток, S=25 Приморский край, C=RU, СНИЛС=15154407237, ИНН=002540035227, ОГРН=1022502262925, E=OSINCEV@MARINET.RU"
    $issuerName  = $a['IssuerName']; //$kemVydan = R'CN=CRYPTO-PRO Test Center 2, O=CRYPTO-PRO LLC'; // 'УЦ Минкомсвязи',
    $validFrom   = preg_split('/ /',$a['ValidFromDate'])[0];
    $validTo     = preg_split('/ /',$a['ValidToDate'])[0];
    $name   = $a['Name'];//кому выдан - 'Иванов Иван Иванович'
    //$label       = $a['Label'];
    preg_match('/^CN="(.*)", SN=(.*), G=(.*), O=/', $subjectName, $output_array);
    try { //  $stamp_label = $name . ' ' . $output_array[2].' '.$output_array[3];
    } catch (Exception $e) {  echo 'Выброшено исключение: ',  $e->getMessage(), "\n";  }
    $stamp_label = $name;
    //var_dump($output_array);die; echo $stamp_label;die;
    //echo $path;die;
    $img_stamp      = base64_encode(file_get_contents(__DIR__."./processing/gerb_RF.png", 1));
    $deystvitelen   = "c $validFrom по $validTo";  //'с 12.12.2017 по 12.12.2018',

    function space($e=1) {return '{"Text":" ","Font": {"FontSize":'.$e.'} },';}
    if(strlen($issuerName)>45){ $spaceDesc = space(1);}
    else{$spaceDesc = space(3);}

    $fontSertDesc  = '"Font": { "FontSize": 8, "FontFamily": "times", "FontStyle": 0, "FontColor": {"Red": 0,"Green": 0,"Blue": 0}}';
        $stamp = '{
        "Content": [
            {"Text": "ДОКУМЕНТ ПОДПИСАН","Margin": 65,"Font": {"FontSize": 9,"FontFamily": "times","FontStyle": 1,"FontColor": {"Red": 0,"Green": 0,"Blue": 0}}},
            {"Text": "ЭЛЕКТРОННОЙ ПОДПИСЬЮ","Margin": 55,"Font": {"FontSize": 9,"FontFamily": "times","FontStyle": 1,"FontColor": {"Red": 0,"Green": 0,"Blue": 0}}},
            {"Text":" ","Font": {"FontSize":2}},
            {"Text": "СВЕДЕНИЯ О СЕРТИФИКАТЕ ЭП","Margin": 53,"Font": {"FontSize": 8,"FontFamily": "times","FontStyle": 5,"FontColor": {"Red": 0,"Green": 0,"Blue": 0}}
            },'
            .space(5).
            //'{"Text": "Сертификат: '.$thumbprint.'",      '.$fontSertDesc.'},    '.$spaceDesc.
            //'{"Text": "Кому выдан: '.$name.'",       '.$fontSertDesc.'},    '.$spaceDesc.
            '{"Text": "Выдан: '.str_replace('"', "", $stamp_label).'",       '.$fontSertDesc.'},    '.$spaceDesc
            //{"Text": "Кем выдан: '.$issuerName.'",         '.$fontSertDesc.'},    '.$spaceDesc.'
            // {"Text": "Действителен: '.$deystvitelen.'",  '.$fontSertDesc.'}
        .'],
        "TemplateId": 2,
        "Icon": {"Image": "'.$img_stamp.'","LowerLeftX": 15,"LowerLeftY": 52,"Scale": 15},
        "Rect": {
            "LowerLeftX": '.$RectLowerLeftX.',
            "LowerLeftY": '.$RectLowerLeftY.',
            "UpperRightX": '.($RectLowerLeftX+$fixX).',
            "UpperRightY": '.($RectLowerLeftY+$fixY).',
            "BorderRadius": 7,
            "BorderWeight": 2,
            "BorderColor": { "Red": 0, "Green": 0, "Blue": 0 },
            "BackgroundColor": null,
            "ContentMargin": 6
        },
        "Page": 1
    }';

    //var_dump($stamp);die;
    return base64_encode($stamp);
}

// сохраняем ПДФ из БЕЙС64  на диск!
function base64save($document, $savePath) {
    $source = fopen($document, 'r');  //$randName = uniqid();
    $destination = fopen($savePath, 'w');
    stream_copy_to_stream($source, $destination);
}
/*
    var_dump($output); fclose($source);    fclose($destination);
    echo "<br><img class='bordered' src='data:image/jpg;base64,".base64_encode(file_get_contents("$path\\$randName.jpg"))."'/>";
    echo '<style>.bordered { border: 2px black dashed}</style>';
    unlink("$path\\$randName.pdf"); unlink("$path\\$randName.jpg")
*/
    //exec("convert  inline:data:application/pdf;base64,$document $path\\pdf_doc.jpg  2>&1", $output); var_dump($output); die;
/*
    [0]=> string(68) "PreSignDocumentResponse PreSignDocument(PreSignDocument $parameters)"
    [1]=> string(71) "PostSignDocumentResponse PostSignDocument(PostSignDocument $parameters)"
    [2]=> string(71) "EnhanceSignatureResponse EnhanceSignature(EnhanceSignature $parameters)"
    [3]=> string(50) "GetPolicyResponse GetPolicy(GetPolicy $parameters)"
    [4]=> string(68) "PreSignDocumentResponse PreSignDocument(PreSignDocument $parameters)"
    [5]=> string(71) "PostSignDocumentResponse PostSignDocument(PostSignDocument $parameters)"
    [6]=> string(71) "EnhanceSignatureResponse EnhanceSignature(EnhanceSignature $parameters)"
    [7]=> string(50) "GetPolicyResponse GetPolicy(GetPolicy $parameters)" }
*/

function throw_err($msg){echo json_encode(["stat"=>0,"msg"=>$msg]);die;}
function echo_end_die($data) {
  header('Content-Type: application/json');
  echo json_encode($data);
  die;
}
function VerifyCertificate($rawCertificate){
    try {           $urlVerify = 'http://ssd.marinet.ru/ssd_verif/service.svc?wsdl';
                    $soap_service = new SoapClient ( $urlVerify, ['cache_wsdl'     => WSDL_CACHE_NONE] );
                    $res = $soap_service->VerifyCertificate( ['certificate'=>$rawCertificate] );
                    $Message               = $res->VerifyCertificateResult->Message;
                    $Result                = $res->VerifyCertificateResult->Result;
                    //$SignerCertificate   = $res->VerifyCertificateResult->SignerCertificate;
                    $SignerCertificateInfo = $res->VerifyCertificateResult->SignerCertificateInfo;
                    return ["stat" => 1, "msg"=>["Message"=>$Message,"Result"=>$Result,/*"SignerCertificate" => $SignerCertificate,*/"SignerCertificateInfo" => $SignerCertificateInfo ] ];
                    /*echo "Message >>  ";           print($Message);
                    echo 'Result >> ';                var_dump($Result);
                    echo 'SignerCertificate >> ';     var_dump($SignerCertificate);
                    echo 'SignerCertificateInfo >> '; var_dump($SignerCertificateInfo);*/
                   // echo json_encode($data);
    } catch(Exception $e) {

        return ['stat' => 0, 'msg' => 'Err => VerifyCertificate(); __getLastRequest()=>'.$soap_service->__getLastRequest().'<br><br> getMessage()=>'.$e->getMessage().'ALL_ERROR=>'.$e];

    }
} /*test:*/ //$verif = VerifyCertificate($urlVerify, $rawCertificate); echo json_encode($verif["Result"]); /*true|false*/ die;

function soapVarGen($name, $val) {
    $ns2 = "http://schemas.microsoft.com/2003/10/Serialization/Arrays";
    $parmKey = [new SoapVar($name, XSD_STRING, null, null, 'Key', $ns2 )];
    $parmKey[] = new SoapVar($val/*$PDFFormat*/, XSD_STRING, null, null, 'Value', $ns2);
    return new SoapVar($parmKey, SOAP_ENC_OBJECT, null, null, 'KeyValueOfSignatureParamsstring1Iy7z97I', $ns2);
}

function parmGen($name, $val){
    $ns1 = 'http://dss.cryptopro.ru/services/2016/01/';
    return new SoapVar($name, XSD_STRING, null, null, $val, $ns1 );
}

function stage1($ssid, $url, $rawCertificate, $template, $classmap, $document){

    if ( strlen($_POST['cert_base64']) > 30 && strlen($_POST['cert_base64']) < 4000) {
        try {
            $soap_service = new SoapClient( $url, array("classmap"=>$classmap,"trace" => true,"exceptions" => true) );  $soap_service->__setSoapHeaders();
            $res = new PreSignDocumentResponse();
            /*
                $parm = [new SoapVar($document,        XSD_STRING, null, null, 'document',          $ns1 )];
                $parm[] = new SoapVar('PDF',            XSD_STRING, null, null, 'signatureType',    $ns1 );
                $parm[] = new SoapVar($rawCertificate,  XSD_STRING, null, null, 'rawCertificate',   $ns1 );
           */

            $parm   = [parmGen($document,'document')];
            $parm[] = parmGen('PDF','signatureType');
            $parm[] = parmGen($rawCertificate,'rawCertificate');
             //echo $document;

            $parmKeyVal   = [soapVarGen('PDFFormat','CAdES')];
            $parmKeyVal[] = soapVarGen('CADESType','XLT1');
            $parmKeyVal[] = soapVarGen('TSPAddress','http://tsp.ncarf.ru/tsp/tsp.srf');
            $parmKeyVal[] = soapVarGen('PDFReason','1');
            $parmKeyVal[] = soapVarGen('PDFLocation','2');
            //https://localhost/TSP
            //https://ssd.marinet.ru/TSP/
            //http://testca2.cryptopro.ru/tsp/tsp.srf
          /*  $parmKeyVal[] = soapVarGen('PdfSignatureTemplateId',2);
            $parmKeyVal[] = soapVarGen('PdfSignatureAppearance',$template);*/

            $ns1 = 'http://dss.cryptopro.ru/services/2016/01/';
            $parm[] = new SoapVar($parmKeyVal, SOAP_ENC_OBJECT,  null, null, 'signatureParams' , $ns1);

            $res = $soap_service->PreSignDocument( new SoapVar($parm, 301) );


            $HashValue      = base64_encode($res->PreSignDocumentResult->HashValue);
            $СacheObjectId  = $res->PreSignDocumentResult->СacheObjectId;

            /*echo $soap_service->__getLastRequest();
            die;*/
            echo_end_die([
                "stat"           =>  1,
                "HashValue"      =>  $HashValue,
                "СacheObjectId"  =>  $СacheObjectId,
                "ssid"           =>  $ssid
            ]);


        } catch (Exception $e) {

          var_dump($e);die;

            echo json_encode(['stat'=>0,'msg'=>'Ошибка Soap Service','getLastRequest()'=>$soap_service->__getLastRequest(),'getMessage()'=>$e->getMessage(),'ALL_ERROR'=>$e,'addition'=>$soap_service]);
            die;
            //throw_err('ОШИБКА сопы:__getLastRequest()=>'.$soap_service->__getLastRequest().'getMessage()=>'.$e->getMessage().'; ALL_ERROR=>'.$e);
        }
    } else {
        echo 'cert_base64->'.strlen($_POST['cert_base64']); //die;
        throw_err('Сертификат не прошел проверку SVS!');
    }
}


function stage2($url, $rawCertificate, $template, $classmap, $document){
/***
    $string1 = 'V2h5IEkgY2FuJ3QgZG8gdGhpcyEhISEh';
    $binary = base64_decode($string1);
    $hex = bin2hex($binary);
***/
    $signedHashValue = $_POST['HashValue'];
    $cacheObjectId   = $_POST['cacheObjectId'];
    $signatureValue  = $_POST['createdSign'];

    try {
        $soap_service = new SoapClient( $url, array("classmap"=>$classmap,"trace" => true,"exceptions" => true) ); $soap_service->__setSoapHeaders();
        $post = new PostSignDocumentResponse();
            /*
                $parm = [new SoapVar($cacheObjectId,  XSD_STRING, null, null, 'cacheObjectId', $ns1 )];
                $parm[] = new SoapVar($signedHashValue,XSD_STRING, null, null, 'signedHashValue', $ns1 );
                $parm[] = new SoapVar($signatureValue, XSD_STRING, null, null, 'signatureValue', $ns1 );
                $parm[] = new SoapVar('PDF',           XSD_STRING, null, null, 'signatureType', $ns1 );
            */
        $parm   = [parmGen($cacheObjectId,'cacheObjectId')];
        $parm[] = parmGen($signedHashValue,'signedHashValue');

        $byte_array = pack('H*', $signatureValue);



        $signatureValue = base64_encode($byte_array);
        $parm[] = parmGen($signatureValue,'signatureValue');

        //echo $signatureValue;

        $parm[] = parmGen('PDF','signatureType');

        $parmKeyVal   = [soapVarGen('PDFFormat','CAdES')];
        $parmKeyVal[] = soapVarGen('TSPAddress','http://tsp.ncarf.ru/tsp/tsp.srf');

          /*  $parmKeyVal[] = soapVarGen('PdfSignatureTemplateId',2);
            $parmKeyVal[] = soapVarGen('PdfSignatureAppearance',$template);*/

        $ns1 = 'http://dss.cryptopro.ru/services/2016/01/';
        $parm[] = new SoapVar($parmKeyVal, SOAP_ENC_OBJECT, null, null, 'signatureParams' , $ns1);
/*      var_dump([
            "cacheObjectId"=>$cacheObjectId,
            "signedHashValue"=>$signedHashValue,
            "signatureValue"=>$signatureValue
        ]);  die;
*/
        $post = $soap_service->PostSignDocument( new SoapVar($parm, 301) );      //==тут ошибка вылетает!;(
        $base64Binary      = $post->PostSignDocumentResult;



        //echo json_encode(['stat'=>0,'msg'=>'Ошибка Soap Service','getLastRequest()'=>$soap_service->__getLastRequest()]);
         /*echo $soap_service->__getLastRequest();
         die;*/
       // base64save($base64Binary, "./processing/sex.txt");
        return  [
                    "stat" => 1,
                    "base64Binary" => 'data:application/pdf;base64,'.base64_encode($base64Binary)
                ];


    } catch (Exception $e) {
        echo echo_end_die(['stat'=>0,'msg'=>'Ошибка Soap Service',
          //'getLastRequest()'=> mysql_real_escape_string($soap_service->__getLastRequest()),
          //'getMessage()'=> mysql_real_escape_string($e->getMessage()),
          'ALL_ERROR'=> $e
        ]);
        die;
        //throw_err('Err:__getLastResponse()=>'.$soap_service->__getLastResponse().'   Err:__getLastRequest()=>'.$soap_service->__getLastRequest().'; getMessage()=>'.$e->getMessage().'; ALL_ERROR=>'.$e);
    }
 }
?>
