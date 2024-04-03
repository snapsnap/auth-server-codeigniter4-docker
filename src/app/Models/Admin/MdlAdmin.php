<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MdlAdmin extends Model
{

    /**
     * Get App Data List
     */
    public function getAppList($userid)
    {
        $db = db_connect();
        $query = "            
            select a.*, specialStatus = case isnull(a.appStatus,0) when 0 then 'menunggu' when 1 then 'aktif' when 2 then 'ditolak' else '-' end 
            from partner_app a
            left join partner_data b on a.partnerId = b.partnerId
            left join partner_user c on b.userId = c.userId
            where c.active = 1 and c.role = 1 and isnull(a.active,0) = 1
        ";
        $exec = $db->query($query);

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
     * Update app Status
     */
    public function updateAppStatus($appId, $appStatus)
    {
        $db = db_connect();
        $query = "
            update partner_app set appStatus = $appStatus
            where appId = $appId
        ";
        $param = [
            $db->escapeString($appStatus),
            $db->escapeString($appId),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            // kosong...
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
                "data" => $exec
            ];
        }
        return $reply;
    }

    /**
     * Update Client ID
     */
    public function saveClientId($appId, $clientId)
    {
        $db = db_connect();
        $query = "
            update partner_app set clientId = '$clientId'
            where appId = $appId
        ";
        $param = [
            $db->escapeString($clientId),
            $db->escapeString($appId),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            // kosong...
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
                "data" => $exec
            ];
        }
        return $reply;
    }

    /**
     * Update Client Secret
     */
    public function saveClientSecret($appId, $clientSecret)
    {
        $db = db_connect();
        $query = "
            update partner_app set clientSecret = '$clientSecret'
            where appId = $appId
        ";
        $param = [
            $db->escapeString($clientSecret),
            $db->escapeString($appId),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            // kosong...
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
                "data" => $exec
            ];
        }
        return $reply;
    }

    /**
     * Create Empty Key to DB
     */
    public function createEmptyKey($appId, $privateName, $publicName)
    {
        $dir = APPPATH."../keys/";
        //nama Private Key
        $filepath_priv = $dir.$privateName;
        if(!file_exists($filepath_priv)){
            file_put_contents($filepath_priv, "");
        }
        //nama Public Key
        $filepath_pub = $dir.$publicName;
        if(!file_exists($filepath_pub)){
            file_put_contents($filepath_pub, "");
        }

        $db = db_connect();
        $query = "
            update partner_app set mafPubKey = '$publicName', mafPrivKey = '$privateName'
            where appId = $appId
        ";
        $param = [
            $db->escapeString($publicName),
            $db->escapeString($privateName),
            $db->escapeString($appId),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            // kosong...
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
                "data" => $exec
            ];
        }
        return $reply;
    }
}