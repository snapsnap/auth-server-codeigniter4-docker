<?php

namespace App\Controllers\Oauth;

use App\Controllers\BaseController;
use App\Controllers\Resstate;
use CodeIgniter\API\ResponseTrait;

class Response extends BaseController
{
    use ResponseTrait;
    protected $format = "json";

    public function __construct()
    {
        $this->validation =  \Config\Services::validation();
    }

    public function errTimestampNull($appId)
    {
        $httpResponse = 400;
        $responseData = [];
        $response = [
            "httpResponse" => $httpResponse,
            "responseData" => $responseData
        ];
        switch ($appId) {
            case '1':
                $httpResponse = Resstate::HTTP_400;
                $responseData = [
                    "responseCode" => Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,
                    "responseMessage" => Resstate::getName(Resstate::HTTP_400,Resstate::TOKEN,Resstate::CASE_01, "en", "Timestamp", "X-TIMESTAMP"),
                ];
                $response = [
                    "httpResponse" => $httpResponse,
                    "responseData" => $responseData
                ];
                break;
            
            default:
                # code...
                break;
        }
        return $response;
    }

}