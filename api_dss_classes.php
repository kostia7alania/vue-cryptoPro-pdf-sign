<?php
/* //заброшенные настройки SOAP :)
    ini_set('soap.wsdl_cache_enabled', 0);ini_set('soap.wsdl_cache_ttl', 900);ini_set('default_socket_timeout', 15);
        $soap_params = ['soap_version' => SOAP_1_2,'trace' => 1, 'exceptions'     => true,    'verifypeer' => false,'verifyhost' => false,'cache_wsdl'=> WSDL_CACHE_NONE,'encoding'=>'UTF-8','connection_timeout'=>15000,'style'=>SOAP_RPC,'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',   //   "urn:xmethods-delayed-quotes",  // Пространство имен SOAP-метода 
                            "soapaction" => "urn:xmethods-delayed-quotes#getQuote",  // HTTP-заголовок SOAPAction  для SOAP-метода 
                            "use"      => SOAP_ENCODED,"location" => $url]; 
*/ 
$classmap = array('ArrayOfKeyValueOfSignatureParamsstring1Iy7z97I'=>'ArrayOfKeyValueOfSignatureParamsstring1Iy7z97I'
    ,'KeyValueOfSignatureParamsstring1Iy7z97I'=>'KeyValueOfSignatureParamsstring1Iy7z97I'
    ,'DSSPreSignResponse'=>'DSSPreSignResponse'
    ,'DssFault'=>'DssFault'
    ,'DSSLitePolicy'=>'DSSLitePolicy'
    ,'ArrayOfSignatureType'=>'ArrayOfSignatureType'
    ,'ArrayOfTspService'=>'ArrayOfTspService'
    ,'TspService'=>'TspService'
    ,'PreSignArgs'=>'PreSignArgs'
    ,'PostSignArgs'=>'PostSignArgs'
    ,'EnhanceSignatureArgs'=>'EnhanceSignatureArgs'
    ,'PreSignDocument'=>'PreSignDocument'
    ,'PreSignDocumentResponse'=>'PreSignDocumentResponse'
    ,'PostSignDocument'=>'PostSignDocument'
    ,'PostSignDocumentResponse'=>'PostSignDocumentResponse'
    ,'EnhanceSignature'=>'EnhanceSignature'
    ,'EnhanceSignatureResponse'=>'EnhanceSignatureResponse'
    ,'GetPolicy'=>'GetPolicy'
    ,'GetPolicyResponse'=>'GetPolicyResponse'
    ,'PreSignDocumentRest'=>'PreSignDocumentRest'
    ,'PreSignDocumentRestResponse'=>'PreSignDocumentRestResponse'
    ,'PostSignDocumentRest'=>'PostSignDocumentRest'
    ,'PostSignDocumentRestResponse'=>'PostSignDocumentRestResponse'
    ,'EnhanceSignatureRest'=>'EnhanceSignatureRest'
    ,'EnhanceSignatureRestResponse'=>'EnhanceSignatureRestResponse'
    ,'GetPolicyRest'=>'GetPolicyRest'
    ,'GetPolicyRestResponse'=>'GetPolicyRestResponse',
    'VerifyCertificate'=>'VerifyCertificate',
    'VerifyCertificateResponse'=>'VerifyCertificateResponse'
    );
    class DSSPreSignResponse{ var $HashValue; /*base64Binary*/ var $СacheObjectId; /*string*/} 
    class PreSignDocumentResponse{ var $PreSignDocumentResult; /*DSSPreSignResponse*/ }
    class PostSignDocumentResponse{ var $PostSignDocumentResult;/*base64Binary*/  } 
    class VerifyCertificateResponse{ var $VerifyCertificateResult;/*base64Binary*/  }  
?>