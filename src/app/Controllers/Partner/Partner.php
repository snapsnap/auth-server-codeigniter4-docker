<?php

namespace App\Controllers\Partner;
use App\Controllers\BaseController;
use App\Models\Partner\MdlPartner;

class Partner extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        //membuat user model untuk konek ke database 
        $this->mdl_partner = new MdlPartner();        
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
        $data = $this->mdl_partner->getAppList($userid);

        return view('partner/partner_dashboard', $data);
    }
    
    /**
     * ke halaman Password
     */
    public function partner_password()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }

        $userid = $this->session->get('userid');
        $data = $this->mdl_partner->getProfile($userid);

        if($data["sukses"] != true){
            return false;
        }

        return view('partner/partner_password', $data);
    }
    
    /**
     * ke halaman Profile
     */
    public function partner_profile()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }

        $userid = $this->session->get('userid');
        $data = $this->mdl_partner->getProfile($userid);

        if($data["sukses"] != true){
            return false;
        }

        return view('partner/partner_profile', $data);
    }
    
    /**
     * ke halaman Buat Aplikasi
     */
    public function partner_createApp()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }

        return view('partner/partner_create_app');
    }

    /**
     * Membuat aplikasi baru
     */
    public function doCreateApp()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $data = $this->request->getPost();
        
        //jalankan validasi
        $this->validation->run($data, 'createApp');
        
        //cek errornya
        $errors = $this->validation->getErrors();
        
        //jika ada error , peringatkan
        if($errors){
            session()->setFlashdata('error_validasi', $errors);
            return redirect()->to('partner/partner/partner_createApp');
        }
        
        //masukan data ke database
        $userid = $this->session->get('userid');
        $arrData = [
            "userid" => $userid,
            "appName" => $data['appName'],
            "pubKey" => $data['pubKey'],
        ];
        $newApp = $this->mdl_partner->saveNewApp($arrData);
        // var_dump($newApp);
        // die();

        // jika error
        if($newApp["sukses"] != true){
            session()->setFlashdata('error', $newApp["message"]);
            return redirect()->to('partner/partner/partner_createApp');
        }
        
        //jika sukses
        session()->setFlashdata('new_app', 'Sukses Menyimpan Data');
        return redirect()->to('partner/partner/index');
    }

    /**
     * Get Key Content
     */
    public function getKeyContent()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $keyName = $param["keyName"];
        $data = $this->mdl_partner->getKeyContent($keyName);

        return json_encode($data);
    }

    /**
     * Update Client Public Key
     */
    public function updateClientPubKey()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $keyName = $param["keyName"];
        $newKey = $param["newKey"];
        
        $result = $this->mdl_partner->updateKeyContent($keyName, $newKey);

        return json_encode($result);
    }

    /**
     * Delete App
     */
    public function deleteApp()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        $error = 0;
        //ambil data dari form
        $param = $this->request->getPost();
        $appId = $param["appId"];
        
        $result = $this->mdl_partner->deleteApp($appId);

        return json_encode($result);
    }

    /**
     * Download key
     */
    public function downloadKey()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        $keyName = $this->request->getVar('keyName');
        $dir = APPPATH."../keys/";
        $filepath = $dir.$keyName;
        return $this->response->download($filepath, NULL);
    }

    /**
     * Update Profile
     */
    public function updateProfile()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();

        $arrData = [
            "partnerId" => $param["partnerId"],
            "nama" => $param["nama"],
            "email" => $param["email"],
            "perusahaan" => $param["perusahaan"],
            "phone" => $param["phone"],
            "alamat" => $param["alamat"]
        ];
        
        $result = $this->mdl_partner->updateProfile($arrData);

        return json_encode($result);
    }

    /**
     * Update Password
     */
    public function updatePassword()
    {
        //cek apakah ada session bernama isLogin
        if(!$this->session->has('isLogin')){
            return redirect()->to('auth/login');
        }
        //ambil data dari form
        $param = $this->request->getPost();
        $userid = $this->session->get('userid');
        $dbpass = $this->session->get('dbpass');

        $oldPass = $param["oldPass"];
        $newPass = $param["newPass"];
        
        $oldpassword = md5($oldPass);
        $newpassword = md5($newPass);

        if($oldpassword != $dbpass){
            $result = [
                "sukses" => false,
                "message" => "Password Lama tidak sesuai",
                "data" => false
            ];
            return json_encode($result);
        }
        
        $result = $this->mdl_partner->updatePassword($oldpassword, $newpassword, $userid);

        return json_encode($result);
    }
}