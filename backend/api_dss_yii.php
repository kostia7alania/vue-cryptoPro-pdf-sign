<?php // YII framework functions

function getSigningDoc_Base64($id) {//берет id документа из URL

  $model = RightSailM::model()->findByAttributes(["UID"=>$id]);
    $base64 = Yii::app()->db->createCommand()
    ->select('File')
    ->from('CertificateFile')
    ->where('idCert=:idCert', array(':idCert'=>$model->UID))
    ->queryRow();
    ///var_dump($id);die;
  return $base64['File']; 
}


function saveSigningDoc_Base64($base64, $id) {//берет id документа из URL //v СОХРАНЕНИЕ В YII БД

   $model = RightSailM::model()->findByAttributes(["UID"=>$id]);

   $CertificateFile = Yii::app()->db->createCommand()
    ->select('idCert')
    ->from('CertificateShip')
    ->where('idCert=:idCert', array(':idCert'=>$model->UID))
    ->queryRow();

   $modelShipFile = ShipFile::model()->findbyPk(["idFile"=>$model->idFile]);


  $modelPdf = new CertificateShip();
  $modelPdf->idCert = $model->UID;
  $modelPdf->idFile = $model->idFile;
  $modelPdf->Final = 0;
  $modelPdf->Sig = $base64;
  if (!isset($CertificateFile['idCert'])) $modelPdf->save();
  else {
    header('Content-Type: application/json');
    header($_SERVER['SERVER_PROTOCOL'] . ' 208 Already Reported', true, 208);
    ob_start();ob_end_clean();
    echo '{"stat": "0", "msg": "Этот документ уже подписали!"}';
    die;
  }
  return true;
}
