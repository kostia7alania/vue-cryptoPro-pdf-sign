var CADESCOM_CADES_BES = 1;
var CAPICOM_CURRENT_USER_STORE = 2;
var CAPICOM_MY_STORE = "My";
var CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED = 2;
var CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME = 1;

function SignCreate(certSubjectName, dataToSign) {
    return new Promise(function(resolve, reject){
        cadesplugin.async_spawn(function *(args) {
            try {
                var oStore = yield cadesplugin.CreateObjectAsync("CAdESCOM.Store");
                yield oStore.Open(CAPICOM_CURRENT_USER_STORE, CAPICOM_MY_STORE,
                    CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED);

                var CertificatesObj = yield oStore.Certificates;
                var oCertificates = yield CertificatesObj.Find(
                    CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME, certSubjectName);

                var Count = yield oCertificates.Count;
                if (Count == 0) {
                    throw("Certificate not found: " + args[0]);
                }
                var oCertificate = yield oCertificates.Item(1);
                var oSigner = yield cadesplugin.CreateObjectAsync("CAdESCOM.CPSigner");
                yield oSigner.propset_Certificate(oCertificate);

                var oSignedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");
                yield oSignedData.propset_Content(dataToSign);

                var sSignedMessage = yield oSignedData.SignCades(oSigner);

                yield oStore.Close();

                args[2](sSignedMessage);
            }
            catch (e)
            {
                args[3]("Failed to create signature. Error: " + cadesplugin.getLastError(err));
            }
        }, certSubjectName, dataToSign, resolve, reject);
    });
}

function run() {
    
    var sCertName = 'ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""';
    var backend_HashValue_Base64 = "4BfEVPd/v2zQGQ2CDFYTJKQ1bOStO23OkUHE3AWB/kU=" //перекодпировать в бинарный 16х!
    function base64toHEX(base64) { var raw = atob(base64); var HEX = ''; for ( i = 0; i < raw.length; i++ ) { var _hex = raw.charCodeAt(i).toString(16); HEX += (_hex.length==2?_hex:'0'+_hex); } return HEX.toUpperCase(); }
    sHashValue = base64toHEX(backend_HashValue_Base64);


    var thenable = SignCreate(sCertName, sHashValue);

    thenable.then(
        function (result){
            document.getElementById("signature").innerHTML = result;
        },
        function (result){
            document.getElementById("signature").innerHTML = result;
        });
}