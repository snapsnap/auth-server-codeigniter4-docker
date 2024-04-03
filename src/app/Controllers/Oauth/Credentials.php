<?php

namespace App\Controllers\Oauth;

use App\Controllers\BaseController;
use App\Controllers\Resstate;
use App\Controllers\MafConfig;
use App\Models\Oauth\MdlCredentials;
use App\Models\Partner\MdlPartner;
use CodeIgniter\API\ResponseTrait;

class Credentials extends BaseController
{
    use ResponseTrait;
    protected $format = "json";
    // protected $mdlCredentials = 'App\Models\Oauth\MdlCredentials';

    public function __construct()
    {
        $this->mdlCredentials = new mdlCredentials();
        $this->mdl_partner = new MdlPartner();
        $this->validation =  \Config\Services::validation();
        
        /**
         * CORS checking
         */
        // if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
        //     $origin = $_SERVER['HTTP_ORIGIN'];
        // } else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
        //     $origin = $_SERVER['HTTP_REFERER'];
        // } else {
        //     $origin = $_SERVER['REMOTE_ADDR'];
        // }
        // $allowed_domains = array(
        //     'https://auth.multindo.co.id',
        //     'https://apidevportal.aspi-indonesia.or.id',
        //     '*'
        // );


        // if (in_array($origin, $allowed_domains)) {
        //     header('Access-Control-Allow-Origin: ' . $origin);
        // }

        // header("Access-Control-Allow-Headers: CONTENT-TYPE, Content-Type, content-type");
        // // header("Access-Control-Allow-Headers: CONTENT-TYPE, Content-Type, content-type, X-TIMESTAMP, X-SIGNATURE,x-signature,Origin, X-Requested-With, Accept, Authorization,X-Signature,x-timestamp, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Access-Control-Request-Method, Access-Control-Request-Headers");
        // header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS");
        // header("Access-Control-Allow-Credentials: true");
        // header("Access-Control-Max-Age: 3600");
        // header('content-type: application/json; charset=utf-8');
        // $method = $_SERVER['REQUEST_METHOD'];
        // if ($method == "OPTIONS") {
        //     header("HTTP/1.1 200 OK CORS");
        //     header("Access-Control-Allow-Headers: CONTENT-TYPE, Content-Type, content-type");
        //     // die();
        // }
        /**
         * END CORS checking
         */
    }

    /**
     * Checking Available Resource Data (Resource Code)
     */
    public function checkResCode($code) {
        $base = base64_decode($code);
        if(!str_contains($base, "|")){
            return false;
        }
        $string = explode("|", $base);
        $resCode = $string[0];
        $secret = $string[1];

        if($secret != MafConfig::MAF_RES_KEY){
            return false;
        }

        $data = $this->mdlCredentials->getResourcesData($resCode);
        if($data["sukses"] != true){
            return false;
        }

        return true;
    }

    /**
     * Generate Credential For Partner
     */
    public function generateCredential($appId)
    {
        $error = 0;
        $message = "";

        /** Get Data App */
        $appData = $this->mdlCredentials->getPartnerApp($appId);
        if($appData["sukses"] != true){
            $reply = [
                "success" => false,
                "message" => "No App Data"
            ];
            return $reply;
        }
        $active = $appData['data'][0]['active'];
        $appStatus = $appData['data'][0]['appStatus'];
        if($active != 1){
            $reply = [
                "success" => false,
                "message" => "No App is Active"
            ];
            return $reply;
        }
        if($appStatus != 1){
            $reply = [
                "success" => false,
                "message" => "No App is Active"
            ];
            return $reply;
        }

        $prefix = $appData['data'][0]['appId'];
        $privKeyName = $prefix."_mPrivKey.pem";
        $pubKeyName = $prefix."_mPubKey.pem";

        /** Proses Generate Key */
        $this->functionapi->generateRSAKey($privKeyName, $pubKeyName);
        /** Proses Generate Client ID / Client Key */
        $clientId = $this->functionapi->generate_clientid($prefix);
        /** Proses Generate Client Secret */
        $clientSecret = $this->functionapi->generate_clientSecret($prefix);

        /** Update DB */
        $arrData = [
            "appId" => $appId,
            "clientId" => $clientId,
            "clientSecret" => $clientSecret,
            "mafPrivKey" => $privKeyName,
            "mafPubKey" => $pubKeyName
        ];
        $insertCred = $this->mdlCredentials->insertCredential($arrData);
        if($insertCred["sukses"] != true){
            $reply = [
                "success" => false,
                "message" => "No App Data"
            ];
            return $reply;
        }
        $reply = $insertCred;
        return $reply;
    }

    /**
     * Generate Auth Signature
     */
    public function generateAuthSign()
    {
        $timeStamp = null;
        if ($this->request->hasHeader('X-TIMESTAMP')) {
            $timeStamp = $this->request->getHeaderLine('X-TIMESTAMP');
            $timeStamp = isset($timeStamp) ? $timeStamp : null;
        }
        $clientKey = null;
        if ($this->request->hasHeader('X-CLIENT-KEY')) {
            $clientKey = $this->request->getHeaderLine('X-CLIENT-KEY');
            $clientKey = isset($clientKey) ? $clientKey : null;
        }
        if($timeStamp == null){
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "X-TIMESTAMP"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        if($clientKey == null){
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "X-CLIENT-KEY"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        $isValidTimeStamp = $this->functionapi->assertISO8601Date((string)$timeStamp);
        if(!$isValidTimeStamp){
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "X-TIMESTAMP format"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }

        /** Ambil Key Data dari DB */
        $credKeyData = $this->mdlCredentials->getCredentialKey($clientKey);
        if($credKeyData["sukses"] != true){
            $responseData = [
                "status" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }

        $privKeyName    = $credKeyData['data'][0]['mafPrivKey'];
        if(!isset($privKeyName)){
            $responseData = [
                "status" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }

        $privateKeypath   = $this->functionapi->getKeyPath($privKeyName);
        $privateKey = $this->functionapi->loadPrivateKey($privateKeypath);

        /** GENERATE STRINGTOSIGN & SIGNATURE*/
        $getStringToSign = $this->functionapi->generateStringToSignToken($clientKey, $timeStamp);
        $signature        = $this->functionapi->generateSign($getStringToSign, $privateKey);

        $callback = [
            "stringToSign" => $getStringToSign,
            "signature" => $signature
        ];
        $responseData = [
            "status" => Resstate::HTTP_200.Resstate::TOKEN.Resstate::CASE_00,
            "success" => true,
            "message" => Resstate::getName(Resstate::HTTP_200,Resstate::TOKEN,Resstate::CASE_00, "en"),
            "data" => $callback
        ];
        return $this->respond($responseData,Resstate::HTTP_200);
    }

    /**
     * Generate Token For Partner
     */
    public function generateToken()
    {

        $clientKey = null;
        if ($this->request->hasHeader('X-CLIENT-KEY')) {
            $clientKey = $this->request->getHeaderLine('X-CLIENT-KEY');
            $clientKey = isset($clientKey) ? $clientKey : null;
        }
        if($clientKey == null){
            $responseData = [
                "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "responseMessage" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "clientkey", "X-CLIENT-KEY"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        $timeStamp = null;
        if ($this->request->hasHeader('X-TIMESTAMP')) {
            $timeStamp = $this->request->getHeaderLine('X-TIMESTAMP');
            $timeStamp = isset($timeStamp) ? $timeStamp : null;
        }
        $signature = null;
        if ($this->request->hasHeader('X-SIGNATURE')) {
            $signature = $this->request->getHeaderLine('X-SIGNATURE');
            $signature = isset($signature) ? $signature : null;
        }
        if ($this->request->hasHeader('x-signature')) {
            $signature = $this->request->getHeaderLine('x-signature');
            $signature = isset($signature) ? $signature : null;
        }
        if($timeStamp == null){
            $responseData = [
                "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "responseMessage" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "Timestamp", "X-TIMESTAMP"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        if($signature == null){
            $responseData = [
                "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "responseMessage" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "signature", "X-SIGNATURE"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        $isValidTimeStamp = $this->functionapi->assertISO8601Date((string)$timeStamp);
        if(!$isValidTimeStamp){
            $responseData = [
                "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "responseMessage" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "Timestamp", "X-TIMESTAMP"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }

        $isValidClientKey = $this->mdlCredentials->getClientKey($clientKey);
        if($isValidClientKey["sukses"] != true){
            $responseData = [
                "responseCode" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "responseMessage" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Unknown Client"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }

        $postparam  = $this->request->getJSON(true);
        $this->validation->setRules([
            'grantType' => 'required',
        ]);
        if(!$this->validation->run($postparam)){
            $errorBody = $this->validation->getErrors();
            $responseData = [
                "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_00,
                "responseMessage" => $errorBody,
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        if($postparam["grantType"] != "client_credentials"){
            $responseData = [
                "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "responseMessage" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "grantType"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }

        /** Pengambilan Data Credential Partner dari DB */
        $dataForJwt = $this->mdlCredentials->getDataForJwt($clientKey);
        if($dataForJwt["sukses"] != true){
            $responseData = [
                "responseCode" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "responseMessage" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }

        $sub           = $dataForJwt['data'][0]['jwtSub'];
        $aud           = $dataForJwt['data'][0]['jwtAud'];
        /** END */
        
        /** MAF Key untuk mengenkripsi JWT */
        $credKeyData = $this->mdlCredentials->getCredentialKey($clientKey);
        if($credKeyData["sukses"] != true){
            $responseData = [
                "responseCode" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "responseMessage" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }

        $privKeyName    = $credKeyData['data'][0]['mafPrivKey'];
        $pubKeyName     = $credKeyData['data'][0]['clientPubKey'];
        if(!isset($privKeyName) || !isset($pubKeyName)){
            $responseData = [
                "responseCode" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "responseMessage" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }

        $privateKeypath   = $this->functionapi->getKeyPath($privKeyName);
        $publicKeypath    = $this->functionapi->getKeyPath($pubKeyName);

        $publicKey        = $this->functionapi->loadPublicKey($publicKeypath);
        $privateKey       = $this->functionapi->loadPrivateKey($privateKeypath);
        /** End */

        /** CEK SIGNATURE SUDAH COCOK */
        $getStringToSign = $this->functionapi->generateStringToSignToken($clientKey, $timeStamp);
        $validSign =  $this->functionapi->verifySign($getStringToSign, $signature, $publicKey);
        if ($validSign != 'VALID') {
            $responseData = [
                "responseCode" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "responseMessage" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Signature"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        /** End */

        /** Get App Date from DB */
        $getAppDate = $this->mdlCredentials->getAppDate(MAFConfig::MAF_EXPIRED_TOKEN);
        if($getAppDate["sukses"] != true){
            $responseData = [
                "responseCode" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "responseMessage" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }
        
        $appDate    = $getAppDate['data'][0]['appDate'];
        $expDate    = $getAppDate['data'][0]['expDate'];
        $iatDate    = $getAppDate['data'][0]['iatDate'];
      
        /** Generate JWT */
        $key = MAFConfig::MAF_JWT_KEY;
        $headers = array(
           'alg'=>'HS256',
           'typ'=>'JWT'
        );
        $payload = array(
           'iss' => MAFConfig::MAF_JWT_ISS,
           'sub' => $sub,
           'aud' => $aud,
           'exp' => $expDate,
           'iat' => $iatDate
        );
        $jwtToken = $this->functionapi->generate_jwt($headers, $payload, $key);
        /** End */

        $responseData = [
            "responseCode" => Resstate::HTTP_200.Resstate::TOKEN.Resstate::CASE_00,
            "responseMessage" => Resstate::getName(Resstate::HTTP_200,Resstate::TOKEN,Resstate::CASE_00, "en"),
            "accessToken" => $jwtToken,
            "tokenType" => MAFConfig::DEFAULT_AUTHORIZATION_TYPE,
            "expiresIn" => MAFConfig::MAF_EXPIRED_TOKEN,
        ];
        return $this->respond($responseData,Resstate::HTTP_200);
        // ->setHeader("Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
    }

    /**
     * Verifikasi Token
     */
    public function verifyToken()
    {
        $authorization = null;
        $tokenType = null;
        $accessToken = null;
        // if (!$this->request->hasHeader('Authorization')) {
        //     $responseData = [
        //         "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
        //         "success" => false,
        //         "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "Authorizations"),
        //     ];
        //     return $this->respond($responseData,Resstate::HTTP_400);
        // }
        // $authorization = $this->request->getHeaderLine('Authorization');

        $postparam  = $this->request->getJSON(true);
        $this->validation->setRules([
            'Authorization' => 'required',
        ]);
        if(!$this->validation->run($postparam)){
            $errorBody = $this->validation->getErrors();
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => $postparam["Authorization"],
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "Authorization"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }

        $authorization = isset($postparam["Authorization"]) ? explode(" ",$postparam["Authorization"]) : null;
        $tokenType = isset($authorization[0]) ? trim($authorization[0]," ") : null;
        $accessToken = isset($authorization[1]) ? trim($authorization[1]," ") : null;

        // $responseData = [
        //     "status" => Resstate::HTTP_200.Resstate::TOKEN.Resstate::CASE_00,
        //     "success" => $authorization,
        //     "message" => Resstate::getName(Resstate::HTTP_200,Resstate::TOKEN,Resstate::CASE_00, "en", "Authorization"),
        // ];
        // return $this->respond($responseData,Resstate::HTTP_200);
        
        if($authorization == null){
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "Authorization"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        if($tokenType == null || $tokenType == "" || $tokenType != MAFConfig::DEFAULT_AUTHORIZATION_TYPE){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token Type"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        if($accessToken == null || $accessToken == ""){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }

        /** Verifying JWT Token */
        $key = MAFConfig::MAF_JWT_KEY;

        $result = array();
        // split the jwt
        $tokenParts         = explode('.', $accessToken);
        $header             = isset($tokenParts[0]) ? base64_decode($tokenParts[0]) : null;
        $payload            = isset($tokenParts[1]) ? base64_decode($tokenParts[1]) : null;
        $signature_provided = isset($tokenParts[2]) ? $tokenParts[2] : null;
        
        if($header == null || $header == ""){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        
        if($payload == null || $payload == ""){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        
        if($signature_provided == null || $signature_provided == ""){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }

        // check header JWT
        // build a signature based on the header and payload using the secret
        $base64_url_header = $this->functionapi->base64url_encode($header);
        $base64_url_payload = $this->functionapi->base64url_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header.".".$base64_url_payload, $key, true);
        $base64_url_signature = $this->functionapi->base64url_encode($signature);
        
        if($base64_url_signature != $signature_provided){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
    
        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $getAppDate = $this->mdlCredentials->getAppDate(MAFConfig::MAF_EXPIRED_TOKEN);
        if($getAppDate["sukses"] != true){
            $responseData = [
                "status" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }
        
        $appDate            = $getAppDate['data'][0]['appDate'];
        $expiration         = json_decode($payload)->exp;
        $is_token_expired   = $expiration > $appDate;
        
        if($is_token_expired == false){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Token [Expired]"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }

        // cek valid aud
        $aud                = json_decode($payload)->aud;
        $audParts           = explode('|', $aud);
        $appId              = isset($audParts[0]) ? $audParts[0] : null;
        
        if($appId == null || $appId == ""){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Partner"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        $activeApp = $this->mdlCredentials->getActiveApp($appId);
        if($activeApp["sukses"] != true){
            $responseData = [
                "status" => Resstate::HTTP_500.Resstate::TOKEN.Resstate::CASE_02,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_500,Resstate::TOKEN,Resstate::CASE_02, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_500);
        }
        
        $isActiveApp            = $activeApp['data'][0]['active'];        
        if($isActiveApp != 1){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_00,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_00, "en", "Partner"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        /** END */

        $callback = [
            "isValidToken" => $is_token_expired,
            "isValidApp" => true
        ];
        $responseData = [
            "status" => Resstate::HTTP_200.Resstate::TOKEN.Resstate::CASE_00,
            "success" => true,
            "message" => Resstate::getName(Resstate::HTTP_200,Resstate::TOKEN,Resstate::CASE_00, "en"),
            "data" => $callback
        ];
        return $this->respond($responseData,Resstate::HTTP_200);
    }

    /**
     * Get Public Key Partner for Resources Server
     */
    public function getCredPartner()
    {
        $resCode = null;
        if ($this->request->hasHeader('X-RES-CODE')) {
            $resCode = $this->request->getHeaderLine('X-RES-CODE');
            $resCode = isset($resCode) ? $resCode : null;
        }
        if($resCode == null){
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "X-RES-CODE"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }

        $checkResCode = $this->checkResCode($resCode);
        if(!$checkResCode){
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "X-RES-CODE"),
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }

        // $postparam  = $this->request->getVar();
        $postparam = $this->request->getJSON(true);
        // $postparam = json_decode(file_get_contents("php://input"));
        $this->validation->setRules([
            'appId' => 'required',
        ]);
        if(!$this->validation->run($postparam)){
            $errorBody = $this->validation->getErrors();
            $responseData = [
                "status" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                "success" => false,
                "message" => $errorBody,
            ];
            return $this->respond($responseData,Resstate::HTTP_400);
        }
        /** Ambil data public key partner */
        $appId = $postparam["appId"];
        $credData = $this->mdlCredentials->getActiveApp($appId);
        if($credData["sukses"] != true){
            $responseData = [
                "status" => Resstate::HTTP_401.Resstate::TOKEN.Resstate::CASE_03,
                "success" => false,
                "message" => Resstate::getName(Resstate::HTTP_401,Resstate::TOKEN,Resstate::CASE_03, "en"),
            ];
            return $this->respond($responseData,Resstate::HTTP_401);
        }
        $clientId = $credData['data'][0]['clientId'];
        $clientSecret = $credData['data'][0]['clientSecret'];
        $pubKeyContent = $this->mdl_partner->getKeyContent($credData['data'][0]['clientPubKey']);
        /** End */

        $callback = [
            "clientId" => $clientId,
            "clientSecret" => $clientSecret,
            "publicKey" => $pubKeyContent,
            // "tes" => $appId
        ];
        $responseData = [
            "status" => Resstate::HTTP_200.Resstate::TOKEN.Resstate::CASE_00,
            "success" => true,
            "message" => Resstate::getName(Resstate::HTTP_200,Resstate::TOKEN,Resstate::CASE_00, "en"),
            "data" => $callback
        ];
        return $this->respond($responseData,Resstate::HTTP_200);
    }
}
