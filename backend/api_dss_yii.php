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

  $modelPdf = new CertificateShip();
  $modelPdf->idCert = $model->UID;
  $modelPdf->Sig = $base64;
  if (!isset($CertificateFile['idCert'])) $modelPdf->save();
  else {
    header('Content-Type: application/json');
    exit('{"stat": 0, "msg": "Этот документ уже подписали!"}');
  }
  return true;
}
