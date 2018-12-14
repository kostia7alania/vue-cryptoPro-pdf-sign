cadesplugin.CreateObjectAsync("CAdESCOM.Store")
	.then(oStore=>oStore.Open(cadesplugin.CAPICOM_CURRENT_USER_STORE, cadesplugin.CAPICOM_MY_STORE,cadesplugin.CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED)
		.then(e2 => {
			oStore.Certificates
			.then(oFnd => {
				oFnd.Find(cadesplugin.CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME,'ФГБУ ""АМП ПРИМОРСКОГО КРАЯ И ВОСТОЧНОЙ АРКТИКИ""')
			.then(oCertificates => {
			oCertificates.Count
			.then(count=>{
				if(count==0) {alert("Нет сертификатов с таким именем!!!"); throw 'Нет сертификатов с таким именем!';}
				oCertificates.Item(1)
					.then(oCertificate=>window.oCertificate=oCertificate)

            }) 
					}) 
				}
			)}
	)
) 

var backend_HashValue_Base64 = "xY7ypPDjfhaVn94xQRf+J2yFT3Z9Kve6ZoEAgfzQwZw=" //перекодпировать в бинарный 16х!
function base64toHEX(base64) { var raw = atob(base64); var HEX = ''; for ( i = 0; i < raw.length; i++ ) { var _hex = raw.charCodeAt(i).toString(16); HEX += (_hex.length==2?_hex:'0'+_hex); } return HEX.toUpperCase(); }
sHashValue = base64toHEX(backend_HashValue_Base64);

sHashValue = backend_HashValue_Base64;// врубили -> CADESCOM_BASE64_TO_BINARY!!!

cadesplugin.CreateObjectAsync("CAdESCOM.HashedData")
    .then(oHashedData=>{
        oHashedData.propset_Algorithm(cadesplugin.CADESCOM_HASH_ALGORITHM_CP_GOST_3411)
		.then( () => oHashedData.propset_DataEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY))
		.then( () => oHashedData.Hash(sHashValue) // данные кодируются при задании или получении значения свойства 
            .then(
                () => cadesplugin.CreateObjectAsync("CAdESCOM.RawSignature")
                .then( oRawSignature => oRawSignature.SignHash(oHashedData, oCertificate)
                .then(e=>{
                    cryptoVue.$children[0]._data.createdSign = e;
                    console.log(e);
                } ))

            )
        )
    }
)
      

 //cadesplugin.CreateObjectAsync("CAdESCOM.RawSignature").then(oRawSignature=>{oRawSignature.SignHash(oHashedData, oCertificate).then(e=>console.log(e))})
 
 

cryptoVue.$children[0]._data.cacheObjectId="c48e1e95-ad07-42eb-9bf5-fb5f1545c2bb"

cryptoVue.$children[0]._data.HashValue='f23102283be7b9e2c6bf0d3cc966538001a08ffc8349ca1461dac00e3b16e4ad'

cryptoVue.$children[0]._data.createdSign='670FA7911FE6D608CA8195B7EA81C20D7753E60AE857448BC243ADE9E58A95371D5C42E0D628660E649786773645845A49E0C03785058070493AF6FA70224DB3'



cryptoVue.$children[0].podpisat2(1)