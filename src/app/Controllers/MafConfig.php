<?php

namespace App\Controllers;

class MafConfig extends BaseController
{
    
    const DATE_FORMAT = "Y-m-d\TH:i:sP";

    const DEFAULT_CURRENCY = "IDR";

    const DEFAULT_AUTHORIZATION_TYPE = "Bearer";

    const MAF_EXPIRED_TOKEN = 900;


    const MAF_BASEURL = "https://sismaf.co.id:8181";
    const MAF_DOMAIN_PROD = "/MultindoMobile/zrest";
    const MAF_DOMAIN_DEV = "/MultindoMobile/zrestdev";    

    const MAF_SERVICE_GET_TOKEN = "/v1.0/access-token/b2b";

    const MAF_ENDPOINT_GET_TOKEN = self::MAF_DOMAIN_DEV . self::MAF_SERVICE_GET_TOKEN;
    const MAF_URI_GET_TOKEN = self::MAF_BASEURL . self::MAF_DOMAIN_DEV . self::MAF_SERVICE_GET_TOKEN;

    const MAF_JWT_KEY = "MultindoAutoFinance119";
    const MAF_JWT_ISS = "PT. Multindo Auto Finance";

    const MAF_RES_KEY = "MultindoResources119";

    const MAF_PRIVATE_KEY = "file://".APPPATH."../keys/MafPrivateKey.pem";
    const MAF_PUBLIC_KEY = "file://".APPPATH."../keys/MafPublicKey.pem";
    
    /**
     * DAFTAR KEY PARTNER
     */
    const PARTNER_PUBLIC_KEY = "file://".APPPATH."../keys/MafPublicKey.pem";

}