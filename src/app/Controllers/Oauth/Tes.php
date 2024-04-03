<?php

namespace App\Controllers\Oauth;

// use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;

class Tes extends ResourceController
{
    protected $format = "json";

    public function index()
    {
        $error = "";
        if ($this->request->hasHeader('DNT')) {
            $error = $this->request->getHeaderLine('DNT');
        }

        $postparam  = $this->request->getVar();
        $validate = [
            "coba" => "required",
        ];
        if(!$this->validate($validate)){
            return $this->respond(["status"=> Resstate::HTTP_400.Resstate::TOKEN.Resstate::CASE_01,"message"=>$this->validator->getErrors()], Resstate::HTTP_400);
        }

        $response = [
            "status" => 200,
            "message" => "sukses",
            "error" => $error,
            "cid" => $this->functionapi->generate_clientid("1"),
            "data" => $this->functionapi->base64url_encode("tes1234568790")
        ];

        return $this->respond($response, 200);
    }
}
