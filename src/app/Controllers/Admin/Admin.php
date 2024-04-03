<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\MdlAdmin;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        //membuat user model untuk konek ke database 
        $this->mdl_admin = new MdlAdmin();        
        //meload validation
        $this->validation = \Config\Services::validation();
    }
    
    /**
     * ke halaman Dashboard
     */
    public function index()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }

        $userid = $this->session->get('userid');
        $data = $this->mdl_admin->getAppList($userid);

        return view('admin/admin_dashboard', $data);
    }

    /**
     * Update App Status
     */
    public function updateAppStatus()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $appId = $param["appId"];
        $appStatus = $param["appStatus"];
        
        $result = $this->mdl_admin->updateAppStatus($appId, $appStatus);

        return json_encode($result);
    }

    /**
     * Generate Client ID
     */
    public function generateClientId()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $appId = $param["appId"];
        
        // generate client Id
        $clientId = $this->functionapi->generate_clientid($appId);
        // simpan DB
        $result = $this->mdl_admin->saveClientId($appId, $clientId);

        return json_encode($result);
    }

    /**
     * Generate Client Secret
     */
    public function generateClientSecret()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $appId = $param["appId"];
        
        // generate client Id
        $clientSecret = $this->functionapi->generate_clientSecret($appId);
        // simpan DB
        $result = $this->mdl_admin->saveClientSecret($appId, $clientSecret);

        return json_encode($result);
    }

    /**
     * Generate MAF RSA Key Combination
     */
    public function generateMafKey()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $appId = $param["appId"];

        $privateName = $appId."_mPrivKey.pem";
        $publicName = $appId."_mPubKey.pem";
        
        // bentuk file key kosong ke DB
        $result = $this->mdl_admin->createEmptyKey($appId, $privateName, $publicName);
        if(!$result["data"]){
            return false;
        }
        // generate RSA key combination
        $keyCombination = $this->functionapi->generateRSAKey($privateName, $publicName);

        return json_encode($keyCombination);
    }
}