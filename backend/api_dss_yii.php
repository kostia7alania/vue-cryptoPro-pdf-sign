<?php

function getSigningDoc_Base64($id) {
    $base64 = Yii::app()->db->createCommand()
    ->select('File')
    ->from('CertificateFile')
    ->where('idCert=:idCert', [ ':idCert' => $id])
    ->queryRow();

  return $base64['File']; 
}


function saveSigningDoc_Base64($base64, $id, $idFile) {
  $modelPdf = new CertificateShip();
  $modelPdf->idCert = $id;
  $modelPdf->Final = 0;
  $modelPdf->Sig = $base64;
  if (!$modelPdf->save()) 
	{
	    header('Content-Type: application/json');
	    header($_SERVER['SERVER_PROTOCOL'] . ' 208 Already Reported', true, 208);
	    ob_start();ob_end_clean();
	    echo '{"stat": "0", "msg": "Этот документ уже подписали!"}';
	    die;
	 }
  return true;
}
