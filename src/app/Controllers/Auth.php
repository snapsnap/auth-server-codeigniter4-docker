<?php

namespace App\Controllers;
use App\Models\MdlAuth;

class Auth extends BaseController
{
    public function __construct()
    {
        //membuat user model untuk konek ke database 
        $this->mdl_auth = new MdlAuth();
        
        //meload validation
        $this->validation = \Config\Services::validation();
        
        //meload session
        $this->session = \Config\Services::session();
        
    }

    public function index()
    {
        return view('login');
    }
    
    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    /**
     * Melakukan Registrasi
     */
    public function doRegist()
    {
        //tangkap data dari form 
        $data = $this->request->getPost();

        // google recaptcha
        $secret='6LeZAv0lAAAAAClhQsH1DU1guOXzeLlAps934O60';
        // $recaptchaResponse = trim($this->request->getVar('g-recaptcha-response'));
        $recaptchaResponse = trim($data['g-recaptcha-response']);
        $userIp = $this->request->getIPAddress();

        $credential = array(
            'secret' => $secret,
            'response' => $recaptchaResponse,
            'remoteip' => $userIp
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
    
        // $status = json_decode($response, true);
        // if(!isset($status)){
        //     session()->setFlashdata('error', 'Verifikasi Captcha Gagal');
        //     return redirect()->to('auth/register');
        // }
        // // var_dump($status);die();
        // if(!$status['success']){
        //     session()->setFlashdata('error', 'Verifikasi Captcha Gagal');
        //     return redirect()->to('auth/register');
        // }
        // end...
        
        //jalankan validasi
        $this->validation->run($data, 'register');
        
        //cek errornya
        $errors = $this->validation->getErrors();
        
        //jika ada error kembalikan ke halaman register
        if($errors){
            session()->setFlashdata('error_validasi', $errors);
            return redirect()->to('auth/register');
        }
        
        //jika tdk ada error 
        $password = md5($data['pass']);
        
        //masukan data ke database
        $arrData = [
            "fullName" => $data['fullName'],
            "email" => $data['email'],
            "pass" => $password,
            "company" => $data['company'],
            "address" => $data['address'],
            "phone" => $data['phone'],
        ];
        $user = $this->mdl_auth->saveRegist($arrData);
        // var_dump($user);
        // die();

        // jika error
        if($user["sukses"] != true){
            session()->setFlashdata('error', 'Gagal Registrasi');
            return redirect()->to('auth/register');
        }

        // jika email pernah terdaftar
        $warning = $user["data"][0]["warning"];
        if($warning == 'true'){
            session()->setFlashdata('error', 'Email sudah terdaftar, silahkan login.');
            return redirect()->to('auth/login');
        }
        
        //arahkan ke halaman login
        session()->setFlashdata('registrasi', 'Sukses mendaftar, silahkan login.');
        session()->setFlashdata('login', 'Anda berhasil mendaftar, silahkan login.');
        return redirect()->to('auth/login');
    }

    /**
     * Melakukan Login
     */
    public function doLogin()
    {
        //ambil data dari form
        $data = $this->request->getPost();

        // google recaptcha
        $secret='6LeZAv0lAAAAAClhQsH1DU1guOXzeLlAps934O60';
        // $recaptchaResponse = trim($this->request->getVar('g-recaptcha-response'));
        $recaptchaResponse = trim($data['g-recaptcha-response']);
        $userIp = $this->request->getIPAddress();

        $credential = array(
            'secret' => $secret,
            'response' => $recaptchaResponse,
            'remoteip' => $userIp
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        // var_dump($credential);
    
        $status= json_decode($response, true);
        // var_dump($status);
        
        // if(!isset($status)){
        //     session()->setFlashdata('error', 'Verifikasi Captcha Gagal');
        //     return redirect()->to('auth/login');
        // }
        // if(!$status['success']){
        //     session()->setFlashdata('error', 'Verifikasi Captcha Gagal');
        //     return redirect()->to('auth/login');
        // }
        // end...

        $email = $data["email"];
        $encPass = md5($data["pass"]);
        
        //ambil data user di database yang usernamenya sama 
        $user = $this->mdl_auth->getPartner($email,$encPass);

        // jika tidak ditemukan arahkan ke halaman login
        if($user["sukses"] != true){
            session()->setFlashdata('error', 'Gagal Login (Email / Password tidak sesuai)');
            return redirect()->to('auth/login');
        }

        $role = $user["data"][0]["specialRole"];
        $userid = $user["data"][0]["userId"];
        $fullname = $user["data"][0]["partnerName"];
        $rolename = $user["data"][0]["roleName"];
        $dbpass = $user["data"][0]["password"];
        if($role == 0){
            session()->setFlashdata('error', 'Gagal Login');
            return redirect()->to('auth/login');
        }

        //jika benar, arahkan user masuk ke aplikasi 
        $sessLogin = [
            'isLogin' => true,
            'fullname' => $fullname,
            'username' => $email,
            'userid' => $userid,
            'rolename' => $rolename,
            'dbpass' => $dbpass,
            'role' => $role
        ];
        $this->session->set($sessLogin);

        if($role == 1){
            return redirect()->to('partner/partner/index');
        }else if($role == 2){
            return redirect()->to('admin/admin/index');
        }else{
            return redirect()->to('auth/login');
        }
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        //hancurkan session 
        //balikan ke halaman login
        $this->session->destroy();
        return redirect()->to('auth/login');
    }
}
