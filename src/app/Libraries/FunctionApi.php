<?php

namespace App\Libraries;

class FunctionApi
{

    /**
     * Cek valid TimeStamp
     */
    public function assertISO8601Date($dateStr) {
       if (preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $dateStr) > 0) {
             return TRUE;
       } else {
             return FALSE;
       }
    }

    /**
     * Get Key Path
     */
    public function getKeyPath($keyName) {
      $path = "file://".APPPATH."../keys/";
      $keyPath = $path.$keyName;
      return $keyPath;
    }

    /**
     * Function Base64URL Encoding for JWT
     */
    function base64url_encode($str) {
       return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * Generate Client ID
     */
    function generate_clientid($id) {
        $secret = "@Multindo123";
        $string = $id."|".$secret;
        $base = $this->base64url_encode($string);
        $clientid = hash_hmac('md5', $base, $secret);
        
        return $clientid;
    }

    /**
     * Generate Client Secret
     */
    function generate_clientSecret($id) {
        $secret = "@Multindo119";
        $string = $id."|".$secret;
        $base = $this->base64url_encode($string);
        $clientSecret = hash_hmac('md5', $base, $secret);
        
        return $clientSecret;
    }

    /**
     * Generate JWT
     */
    function generate_jwt($headers, $payload, $secret = 'secret') {
       $headers_encoded = $this->base64url_encode(json_encode($headers));
       
       $payload_encoded = $this->base64url_encode(json_encode($payload));
       
       $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
       $signature_encoded = $this->base64url_encode($signature);
       
       $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
       
       return $jwt;
    }

    /**
     * Validate JWT
     */
    function is_jwt_valid($jwt, $secret = 'secret') {
       $result = array();
       // split the jwt
       $tokenParts = explode('.', $jwt);
       $header = base64_decode($tokenParts[0]);
       $payload = base64_decode($tokenParts[1]);
       $signature_provided = $tokenParts[2];
    
       // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
       $expiration = json_decode($payload)->exp;
       $is_token_expired = ($expiration - time()) < 0;
    
       // build a signature based on the header and payload using the secret
       $base64_url_header = $this->base64url_encode($header);
       $base64_url_payload = $this->base64url_encode($payload);
       $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
       $base64_url_signature = $this->base64url_encode($signature);
    
       // verify it matches the signature provided in the jwt
       $is_signature_valid = ($base64_url_signature === $signature_provided);
       
       if ($is_token_expired) {
          $result['valid'] = false;
          $result['payload'] = null;
       } else {
          if (!$is_signature_valid) {
             $result['valid'] = false;
             $result['payload'] = null;
          } else {
             $result['valid'] = true;
             $result['payload'] = json_decode($payload);
          }
       }
 
       return $result;
    }

    /**
     * Generate String To Sign Token
     */
    public function generateStringToSignToken($clientKey, $timeStamp)
    {
       $stringToSign = $clientKey."|".$timeStamp;
       return $stringToSign;
    }
 
    /**
     * Generate String To Sign Transaction
     */
    public function generateStringToSignTrans($httpMethod, $endpointUrl, $requestBody, $timeStamp)
    {
       $minify = json_encode($requestBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
       $hash = hash("sha256", $minify, false);
       // $hex = bin2hex($hash);
       $combineBody = strtolower($hash);
 
       $stringToSign = $httpMethod.":".$endpointUrl.":".$combineBody.":".$timeStamp;
       return $stringToSign;
    }
 
    /** Decrypt Signature */
    public function decryptSignature($httpMethod, $url, $requestBody, $timeStamp, $encryptedSign)
    {
       $exameUri = $this->exameUri($url);
       $sha256 = hash('sha256', $this->canonicalize($requestBody));
       $stringToSign = "$httpMethod:$url:$sha256:$timeStamp";
 
       $publicKey = file_get_contents(APPPATH . '/keys/public.pem');
       $decoded = base64_decode($encryptedSign);
       $isValid = openssl_verify($stringToSign, $decoded, $publicKey, "sha256WithRSAEncryption");
 
       if ($isValid == 1) {
          return "VALID";
       } else {
          return "NOT_VALID";
       }
    }
 
    public function generateSignature($httpMethod, $endpointUrl, $requestBody, $timeStamp)
    {
       // $endpointUrl = "/open/bi/1.0.0/get/token";
       $result = $this->generateTransactionSignature($httpMethod, $endpointUrl, $requestBody, $timeStamp);
 
       return $result;
    }

    /**
     *  Format Date Transaction BNC
     */
    public function convertDateBNC($date)
    {
       if ($date != '') {
          $numberOnly =  preg_replace("/[^0-9]/", "", $date);
          $date2 = substr($numberOnly, 4, 2) . "/" . substr($numberOnly, 2, 2) . "/" . substr($numberOnly, 0, 4);
          $his   = substr($numberOnly, -6);
          $jam   = substr($his, 0, 2) . ":" . substr($his, 2, 2) . ":" . substr($his, 4, 2);
          $datex = strtotime($date2 . " " . $jam);
          $dateBNC = date("Y-m-d H:i:s", $datex);
          return $dateBNC;
       } else {
          $dateSQL   = "";
          return $dateSQL;
       }
    }
 
    /**
     * Mengubah variable menjadi canonicalized
     */
    public function canonicalize($json)
    {
       return preg_replace("/[\r\n\t\s+]/", "", $json);
    }
 
    /**
     * Generate Examine URI
     */
    private function exameUri($string)
    {
       $retstr = "";
       for ($i = 0; $i < strlen($string); $i++) {
 
          $char = $string[$i];
          if (($char >= '0' && $char <= '9') ||
             ($char >= 'A' && $char <= 'Z') ||
             ($char >= 'a' && $char <= 'z') ||
             $char == '.' ||
             $char == '~' ||
             $char == '-' ||
             $char == '_' ||
             $char == '/' ||
             $char == '?' ||
             $char == '=' ||
             $char == '&' ||
             (ord($char) >= 128)
          ) {
             $retstr .= $char;
          } else {
 
             $retstr .= "%" . strtoupper(dechex(ord($string[$i])));
          }
       }
       return $retstr;
    }
 
    /**
     * Generate RSA Key Combination
     */
    public static function generateRSAKey($privateName, $publicName)
    {
       //create new private and public key
       $new_key_pair = openssl_pkey_new(array(
          "private_key_bits" => 2048,
          "private_key_type" => OPENSSL_KEYTYPE_RSA,
       ));
       openssl_pkey_export($new_key_pair, $private_key_pem);
 
       $details = openssl_pkey_get_details($new_key_pair);
       $public_key_pem = $details['key'];
 
       //save for later
       $path = APPPATH . "../keys/";
       file_put_contents($path.$privateName, $private_key_pem);
       file_put_contents($path.$publicName, $public_key_pem);

       return true;
    }
 
    /** 
     * Load Key
     */
    public static function loadPrivateKey($keypath)
    {
       $fp = fopen($keypath, "r");
       $privKey = fread($fp, 8192);
       fclose($fp);
       $pKeyId = openssl_pkey_get_private($privKey);
       $key_details = openssl_pkey_get_details($pKeyId);
       // return $key_details['key'];
       return $privKey;
    }
 
    /** 
     * Load Key
     */
    public static function loadPublicKey($keypath)
    {
       $pKeyId = openssl_pkey_get_public($keypath);
       $key_details = openssl_pkey_get_details($pKeyId);
       return $key_details['key'];
    }
    
 
    /**
     * Validasi TimeStamp
     */
    public static function isValidTime($dateTime)
    {
        if (preg_match('/^' .
                '(\d{4})-(\d{2})-(\d{2})T' . // YYYY-MM-DDT ex: 2014-01-01T
                '(\d{2}):(\d{2}):(\d{2})' .  // HH-MM-SS  ex: 17:00:00
                '((-|\+)\d{2}:\d{2})' .  //+01:00 or -01:00
                '$/', $dateTime, $parts) == true) {
            try {
                new DateTime($dateTime);
  
                return true;
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }
 
    /**
     * Generate UUID untuk Token
     */
    public function gen_uuid()
    {
       return sprintf(
          '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          // 32 bits for "time_low"
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
 
          // 16 bits for "time_mid"
          mt_rand(0, 0xffff),
 
          // 16 bits for "time_hi_and_version",
          // four most significant bits holds version number 4
          mt_rand(0, 0x0fff) | 0x4000,
 
          // 16 bits, 8 bits for "clk_seq_hi_res",
          // 8 bits for "clk_seq_low",
          // two most significant bits holds zero and one for variant DCE1.1
          mt_rand(0, 0x3fff) | 0x8000,
 
          // 48 bits for "node"
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff)
       );
    }
 
    /**
     * Generate Base64 Encode
     */
    public function generateBase64($clientId, $clientSecret)
    {
       return base64_encode("$clientId:$clientSecret");
    }
 
    /**
     * Set waktu dengan format ISO ISO8601
     */
    public function timeStamp()
    {
       return (string) date("Y-m-d\TH:i:sP");
    }
 
    /**
     * Convert waktu ISO 8601 ke MAF Server
     */
    public function dateToSql($date)
    {
       $dateTime = new DateTime($date);
       $formatted = $dateTime->format("Y-m-d H:i:s");
       return $formatted;
    }
 
    /**
     * Convert waktu MAF Server ke ISO 8601 
     */
    public function dateToISO($date)
    {
       $dateTime = new DateTime($date);
       $formatted = $dateTime->format("Y-m-d\TH:i:sP");
       return $formatted;
    }
 
 
    /**
     * Hashing method
     */
    public function hashing($data, $privateKey)
    {
       $hash = hash_hmac("SHA256", $data, $privateKey);
       return $hash;
    }
 
    /**
     * Generate Signature
     */
    public function generateSign($data, $privateKey)
    {
       /** GENERATE SIGNATURE */
       $signature = '';
       openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
       $encoded = base64_encode($signature);
 
       return $encoded;
    }
 
 
    /**
     * Verify Signature
     */
    public static function verifySign($data, $signatureBase64, $publicKey)
    {
        $binarySignature = base64_decode($signatureBase64);
        $output = openssl_verify($data, $binarySignature, $publicKey, OPENSSL_ALGO_SHA256);
 
        if ($output == 1) {
           return "VALID";
        } else {
           return "NOT_VALID";
        }
    }
    /**
     * fungsi untuk melakukan request ke API E-COLLECTION BNI
     */ 
    public function get_content($url, $specialHeader = '', $post = '') {
       $usecookie = __DIR__ . "/cookie.txt";
       $header[] = 'Content-Type: application/json';
       // $header[] = "Accept-Encoding: gzip, deflate";
       // $header[] = "Cache-Control: max-age=0";
       // $header[] = "Connection: keep-alive";
 
       if(isset($specialHeader) || $specialHeader <> ''){
          $header[] = "Authorization: ".$specialHeader['Authorization'];
          $header[] = "X-TIMESTAMP: ".$specialHeader['TIMESTAMP'];
          $header[] = "X-CLIENT-KEY: ".$specialHeader['CLIENTKEY'];
          $header[] = "X-SIGNATURE: ".$specialHeader['SIGNATURE'];
       }
    
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       curl_setopt($ch, CURLOPT_HEADER, false);
       curl_setopt($ch, CURLOPT_VERBOSE, false);
       // curl_setopt($ch, CURLOPT_NOBODY, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
       curl_setopt($ch, CURLOPT_ENCODING, true);
       curl_setopt($ch, CURLOPT_AUTOREFERER, true);
       curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    
       // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");
    
       if ($post)
       {
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
       //    switch ($method) {
       //       case "GET":
       //             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
       //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
       //          break;
       //       case "POST":
       //             curl_setopt($ch, CURLOPT_POST, true);
       //             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
       //             // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
       //          break;
       //       case "PUT":
       //             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
       //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
       //          break;
       //       case "DELETE":
       //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
       //             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
       //          break;
       //   }
       }
    
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
       $rs = curl_exec($ch);
    
       if(empty($rs)){
          var_dump($rs, curl_error($ch));
          curl_close($ch);
          return false;
       }
       curl_close($ch);
       return $rs;
    }
}