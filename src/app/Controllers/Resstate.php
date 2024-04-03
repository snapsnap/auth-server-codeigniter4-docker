<?php

namespace App\Controllers;

class Resstate extends BaseController
{

    /** Service Code */
    const TOKEN             = "73";

    /** HTTP Code */
    const HTTP_200 = 200;
    const HTTP_202 = 202;
    const HTTP_400 = 400;
    const HTTP_401 = 401;
    const HTTP_403 = 403;
    const HTTP_404 = 404;
    const HTTP_405 = 405;
    const HTTP_409 = 409;
    const HTTP_429 = 429;
    const HTTP_500 = 500;
    const HTTP_504 = 504;

    /** Case Code */
    const CASE_00    = "00";
    const CASE_01    = "01";
    const CASE_02    = "02";
    const CASE_03    = "03";
    const CASE_04    = "04";
    const CASE_05    = "05";
    const CASE_06    = "06";
    const CASE_07    = "07";
    const CASE_08    = "08";
    const CASE_09    = "09";
    const CASE_10    = "10";
    const CASE_11    = "11";
    const CASE_12    = "12";
    const CASE_13    = "13";
    const CASE_14    = "14";
    const CASE_15    = "15";
    const CASE_16    = "16";
    const CASE_17    = "17";
    const CASE_18    = "18";
    const CASE_19    = "19";
    const CASE_20    = "20";
    const CASE_21    = "21";
    const CASE_22    = "22";
    const CASE_23    = "23";
    
    public function __construct(){
        // parent::__construct();
    }

    public static function getName($code, $serviceCode = "00", $caseCode = "00", $lang = "", $info = "", $subinfo = "")
    {
        $desc_in = array(
            self::HTTP_200.$serviceCode.self::CASE_00 => "Sukses",

            self::HTTP_400.$serviceCode.self::CASE_00 => "Format bidang tidak valid [".$info."]",
            self::HTTP_400.$serviceCode.self::CASE_01 => "Format ".$info." tidak valid [".$subinfo."]",
            self::HTTP_400.$serviceCode.self::CASE_02 => "Bidang Wajib Tidak Valid [".$info."]",

            self::HTTP_401.$serviceCode.self::CASE_00 => "Tidak Sah. [".$info."]",
            self::HTTP_401.$serviceCode.self::CASE_01 => "Tidak Valid",
            self::HTTP_401.$serviceCode.self::CASE_03 => "Tidak Ditemukan",
            
            self::HTTP_404.$serviceCode.self::CASE_08 => "Merchant Tidak Valid",
            self::HTTP_404.$serviceCode.self::CASE_11 => "Kartu/Akun/Pelanggan Tidak Valid ".$info."/Virtual Account",
            self::HTTP_404.$serviceCode.self::CASE_12 => "Tagihan/Virtual Account Tidak Valid ".$info,
            self::HTTP_404.$serviceCode.self::CASE_13 => "Amount Tidak Valid",
            self::HTTP_404.$serviceCode.self::CASE_14 => "Tagihan Telah Dibayar",
            self::HTTP_404.$serviceCode.self::CASE_19 => "Tagihan/Virtual Account Tidak Valid",

            self::HTTP_409.$serviceCode.self::CASE_01 => "partnerReferenceNo Ganda",

            self::HTTP_500.$serviceCode.self::CASE_02 => "Server Eksternal Bermasalah",
        );

        $desc_en = array(
            self::HTTP_200.$serviceCode.self::CASE_00 => "Successful",

            self::HTTP_400.$serviceCode.self::CASE_00 => "Invalid field format [".$info."]",
            self::HTTP_400.$serviceCode.self::CASE_01 => "Invalid ".$info." format [".$subinfo."]",
            self::HTTP_400.$serviceCode.self::CASE_02 => "Invalid mandatory field [".$info."]",

            self::HTTP_401.$serviceCode.self::CASE_00 => "Unauthorized. [".$info."]",
            self::HTTP_401.$serviceCode.self::CASE_01 => "Invalid",
            self::HTTP_401.$serviceCode.self::CASE_03 => "Not Found",
            
            self::HTTP_404.$serviceCode.self::CASE_08 => "Invalid Merchant",
            self::HTTP_404.$serviceCode.self::CASE_11 => "Invalid Card/Account/Customer ".$info."/Virtual Account",
            self::HTTP_404.$serviceCode.self::CASE_12 => "Invalid Bill/Virtual Account ".$info,
            self::HTTP_404.$serviceCode.self::CASE_13 => "Invalid Amount",
            self::HTTP_404.$serviceCode.self::CASE_14 => "Paid Bill",
            self::HTTP_404.$serviceCode.self::CASE_19 => "Invalid Bill/Virtual Account",

            self::HTTP_409.$serviceCode.self::CASE_01 => "Duplicate partnerReferenceNo",

            self::HTTP_500.$serviceCode.self::CASE_02 => "External Server Error",
        );
        
        $name = "";
        switch($lang){
            case "in":
                $name = $desc_in[$code.$serviceCode.$caseCode];
                break;
            case "en":
                $name = $desc_en[$code.$serviceCode.$caseCode];
                break;
            default:
                $name = $desc_en[$code.$serviceCode.$caseCode];
        }
        return $name;
    }

}