<?php

namespace App\Models\Oauth;

use CodeIgniter\Model;

class MdlCredentials extends Model
{
    /**
     * Get Client Key Valid or Not
     */
    public function getClientKey($clientKey)
    {
        $db = db_connect();
        $query = "
            select * from partner_app where clientId = ? and active = 1
        ";
        $param = [
            $db->escapeString($clientKey),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $numrows
            ];
        }
        return $reply;
    }

    /**
     * Get APP DATE from DB
     */
    public function getAppDate($seconds = 900)
    {
        $db = db_connect();
        $query = "
            select getDate() as appDate, expDate = dateadd(second, ?, getDate()), iatDate = dateadd(month, 1, getDate())
        ";
        $param = [
            $db->escape($seconds),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows
            ];
        }
        return $reply;
    }

    /**
     * Get Data App Partner
     */
    public function getPartnerApp($appId)
    {
        $db = db_connect();
        $query = "
            select * from partner_app where appId = ?
        ";
        $param = [
            $db->escapeString($appId),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows
            ];
        }
        return $reply;
    }
    /**
     * Insert new combination RSA Key for partner to DB
     */
    public function insertCredential(array $arrData)
    {
        $error = 0;
        $message = "";

        if(!isset($arrData)){
            $error++;
            $message = "No array data";
        }else{
            if(
                ( !isset($arrData["appId"]) || $arrData["appId"] == "" ) ||
                ( !isset($arrData["clientId"]) || $arrData["clientId"] == "" ) ||
                ( !isset($arrData["clientSecret"]) || $arrData["clientSecret"] == "" ) ||
                ( !isset($arrData["mafPrivKey"]) || $arrData["mafPrivKey"] == "" ) ||
                ( !isset($arrData["mafPubKey"]) || $arrData["mafPubKey"] == "" )
            ){
                $error++;
                $message = "Uncomplete array";
            }else{
                $appId          = $arrData["appId"];
    
                $clientId       = $arrData["clientId"];
                $clientSecret   = $arrData["clientSecret"];
                $mafPrivKey     = $arrData["mafPrivKey"];
                $mafPubKey      = $arrData["mafPubKey"];
    
                $db = db_connect();
                $query = "
                    UPDATE partner_app SET
                        clientId = ?,
                        clientSecret = ?,
                        mafPrivKey = ?,
                        mafPubKey = ?
                    WHERE appId = ?
                ";
                $param = [
                    $db->escapeString($clientId),
                    $db->escapeString($clientSecret),
                    $db->escapeString($mafPrivKey),
                    $db->escapeString($mafPubKey),
                    $db->escape($appId),
                ];
                $exec = $db->query($query, $param);
        
                if($exec) {
                    $numrows = $exec->getNumRows();
                    if($numrows > 0){
                        $error = 0;
                    }else{
                        $error++;
                        $message = "Insert Failed";
                    }
                } else {
                    $error++;
                    $message = "Query gagal";
                }
            }
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => [
                    "numrows" => $numrows
                ]
            ];
        }
        return $reply;
    }

    /**
     * Get Data Partner from DB for JWT purpose
     */
    public function getDataForJwt($clientKey)
    {
        // $db = \Config\Database::connect();
        $db = db_connect();
        $query = "
            select jwtSub=b.appName, jwtAud=cast(b.appId as varchar)+'|'+a.partnerName+'|'+a.createUser
            from partner_data a
            left join partner_app b on a.partnerId=b.partnerId
            where b.active=1
            and b.clientId= ?
        ";
        $param = [
            $db->escapeString($clientKey),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows
            ];
        }
        return $reply;
    }

    /**
     * Get Data Credential Partner
     */
    public function getCredentialKey($clientKey)
    {
        $db = db_connect();
        $query = "
            select * from partner_app where active=1 and clientId = ?
        ";
        $param = [
            $db->escapeString($clientKey),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows
            ];
        }
        return $reply;
    }

    /**
     * Get Active App
     */
    public function getActiveApp($appId)
    {
        $db = db_connect();
        $query = "
            select * from partner_app where active = 1 and appId = ? 
        ";
        $param = [
            $db->escapeString($appId),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows
            ];
        }
        return $reply;
    }

    /**
     * Get Resources Data
     */
    public function getResourcesData($resCode)
    {
        $db = db_connect();
        $query = "
            select * from resource_data where active = 1 and resCode = ? 
        ";
        $param = [
            $db->escapeString($resCode),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();
            }else{
                $error++;
                $message = "No data";
            }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows
            ];
        }
        return $reply;
    }
}
