<?php

namespace App\Models;

use CodeIgniter\Model;

class MdlAuth extends Model
{
    /**
     * Menyimpan data registrasi
     */
    public function saveRegist(array $arrData)
    {
        $fullName   = $arrData["fullName"];
        $email      = $arrData["email"];
        $pass       = $arrData["pass"];
        $company    = $arrData["company"];
        $address    = $arrData["address"];
        $phone      = $arrData["phone"];
        
        $db = db_connect();
        $query = "
            exec espe_register_user ?, ?, ?, ?, ?, ?
        ";
        $param = [
            $db->escapeString($email),
            $db->escapeString($fullName),
            $db->escapeString($pass),
            $db->escapeString($company),
            $db->escapeString($address),
            $db->escapeString($phone),
        ];
        $exec = $db->query($query, $param);

        $error = 0;
        $message = "";
        
        if($exec) {
            $numrows = $exec->getNumRows();
            // if($numrows > 0){
                $rows = $exec->getResultArray();
            // }else{
            //     $error++;
            //     $message = "No data";
            // }
        } else {
            $error++;
            $message = "Query gagal";
        }

        if($error > 0){
            $reply = [
                "sukses" => false,
                "message" => $message,
                "debug" => $rows
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
     * Mengambil data login
     */
    public function getPartner($encEmail,$encPass)
    {
        $db = db_connect();
        $query = "
            select *, specialRole=isnull(role,0), roleName = case a.role when 1 then 'User' when 2 then 'Admin' else '-' end from partner_user a
            left join partner_data b on a.userId = b.userId
            where a.email= ? and a.password= ? and a.active=1
        ";
        $param = [
            $db->escapeString($encEmail),
            $db->escapeString($encPass),
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