<?php

namespace App\Models\Partner;

use CodeIgniter\Model;

class MdlPartner extends Model
{
    /**
     * Get data profil partner
     */
    public function getProfile($userid)
    {
        $db = db_connect();
        $query = "
            select * from partner_user a
            left join partner_data b on a.userId = b.userId
            where a.active=1 and a.userId = ?
        ";
        $param = [
            $db->escapeString($userid),
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
     * Get App Data List
     */
    public function getAppList($userid)
    {
        $db = db_connect();
        $query = "            
            select a.*, specialStatus = case isnull(a.appStatus,0) when 0 then 'menunggu' when 1 then 'aktif' when 2 then 'ditolak' else '-' end from partner_app a
            left join partner_data b on a.partnerId = b.partnerId
            left join partner_user c on b.userId = c.userId
            where c.active = 1 and a.active = 1 and c.userId= ?
        ";
        $param = [
            $db->escapeString($userid),
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
     * Menyimpan aplikasi baru
     */
    public function saveNewApp(array $arrData)
    {
        $userid      = $arrData["userid"];
        $appName    = $arrData["appName"];
        $pubKey      = $arrData["pubKey"];
        
        $db = db_connect();
        $query = "
            exec espe_create_app ?, ?
        ";
        $param = [
            $db->escapeString($userid),
            $db->escapeString($appName),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            if($numrows > 0){
                $rows = $exec->getResultArray();

                //buat nama partner PubKey dahulu
                $appid = $rows[0]["appId"];
                if($appid == 0){
                    $error++;
                    $message = "No App Id";
                }else{
                    //nama file key
                    $cPubKey = $appid."_cPubKey.pem";
                    //isi key
                    $content = $pubKey;
                    //masukkan pubKey ke file
                    $callback = $this->setKeyContent($cPubKey, $content);
                    if($callback == 0){
                        //update nama partner Public Key
                        $db_up = db_connect();
                        $query_up = "
                            exec espe_update_app '$cPubKey', $appid  
                        ";
                        $exec_up = $db_up->query($query_up);
                        if($exec_up){
                            // $numrows_up = $exec_up->getNumRows();
                            // if($numrows_up > 0){
                                $error = 0;
                            // }else{
                            //     $error++;
                            //     $message = "No data updated";
                            // }
                        }else{
                            $error++;
                            $message = "Query Update gagal";
                        }
                    }else{
                        $error++;
                        $message = "Gagal menyimpan kunci";
                    }
                }
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
                "message" => $message,
                // "debug" => $numrows_up
            ];
        }else{
            $reply = [
                "sukses" => true,
                "message" => null,
                "data" => $rows,
                // "callback" => $query_up
            ];
        }
        return $reply;
    }

    /**
     * Set Key Content to File
     */
    public function setKeyContent($keyName, $content)
    {
        $error = 0;
        //nama direktori
        $dir = APPPATH."../keys/";
        if(!file_exists($dir)){
            mkdir($dir, 0777);
        }
        $filepath = $dir.$keyName;
        if(file_exists($filepath)){
            $error++;
        }
        file_put_contents($filepath, $content);
        return $error;
    }

    /**
     * Update Key Content to File
     */
    public function updateKeyContent($keyName, $content)
    {
        $error = 0;
        //nama direktori
        $dir = APPPATH."../keys/";
        if(!file_exists($dir)){
            mkdir($dir, 0777);
        }
        $filepath = $dir.$keyName;
        if(file_exists($filepath)){
            file_put_contents($filepath, $content);
            $error = 0;
        }else{
            $error++;
        }
        return $error;
    }

    /**
     * Get Key Content
     */
    public function getKeyContent($keyName)
    {
        //nama direktori
        $dir = APPPATH."../keys/";
        $filepath = $dir.$keyName;
        if(!file_exists($filepath)){
            $content = "";
        }else{
            $content = file_get_contents($filepath);
        }
        return $content;
    }

    /**
     * Delete Partner App
     */
    public function deleteApp($appId)
    {
        $db = db_connect();
        $query = "
            update partner_app set active = 0 where appId = ?
        ";
        $param = [
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
     * Update Profile
     */
    public function updateProfile(array $arrayData)
    {
        $partnerId = $arrayData["partnerId"];
        $nama = $arrayData["nama"];
        $email = $arrayData["email"];
        $perusahaan = $arrayData["perusahaan"];
        $phone = $arrayData["phone"];
        $alamat = $arrayData["alamat"];

        $db = db_connect();
        $query = "
            update partner_data set
                partnerName = ?,
                partnerCompany = ?,
                partnerAddress = ?,
                partnerPhone = ?,
                lastUpdate = getDate(),
                lastUser = ?
            where partnerId = ?
        ";
        $param = [
            $db->escapeString($nama),
            $db->escapeString($perusahaan),
            $db->escapeString($alamat),
            $db->escapeString($phone),
            $db->escapeString($email),
            $db->escapeString($partnerId),
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
     * Update Password
     */
    public function updatePassword($oldPass, $newPass, $userid)
    {
        $db = db_connect();
        $query = "
            update partner_user set
                password = ?,
                lastUpdate = getDate()
            where userId = ?
        ";
        $param = [
            $db->escapeString($newPass),
            $db->escapeString($userid),
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