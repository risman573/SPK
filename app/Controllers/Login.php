<?php

namespace App\Controllers;

$request = \Config\Services::request();

use App\Libraries\Uuid;
use App\Models\User_model;
use App\Models\Log_activity_model;
use App\Models\Defaults_model;
use App\Models\User_aktif_model;

class Login extends BaseController {

    // var $base_url;
    // var $menu_string;
    // private $default;
    // private $User_aktif_model;
    // private $session;

    function __construct() {
        $this->session = \Config\Services::session();

        $this->uuid = new Uuid();
        $this->User_model = new User_model();
        $this->Log_activity_model = new Log_activity_model();
        $this->User_aktif_model = new User_aktif_model();

        $Defaults_model = new Defaults_model();
        $this->default = $this->getDefault($Defaults_model->get());
        if(isset($this->default['dateTimeZone'])){
            date_default_timezone_set($this->default['dateTimeZone']);
        }
    }

    public function index() {
        if ($this->session->get('website_admin_logged_in')) {
            return redirect()->to('/home');
        }

        $data = array(
            'default' => $this->default,
            'title' => 'Login Admin',
            'base_url' => base_url()
        );
        // print_r($data);
        return view('login', $data);
    }

    public function masuk() {
        // $session = \Config\Services::session();
        $errors = array();
        $data = array();

        if (!$this->request->getVar('login_username')) {
            $errors['username'] = 'Username belum diisi.';
        }
        if (!$this->request->getVar('login_password')) {
            $errors['password'] = 'Password belum diisi.';
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors']  = $errors;
        } else {
            $cond = array("user.username"=>$this->request->getVar('login_username'), "user.status"=>"1");
            $user = $this->User_model->get(null, $cond);
            if (count($user) > '0') {
                foreach ($user as $row) {
                    $this->salt = "jaskd12341ierupep094cx!((#@*!&)(@(*(*)!*(*@)(!*)(!*)";
                    $password = $this->salt . $this->request->getVar('login_password');

                    if (md5($password) == $row['password']) {
                        $result = $this->User_model->get_child($row['id_user']);
                        if(count($result)>0){
                            foreach($result as $rs){
                                $res[] = $rs->id_user;
                            }
                        }else{
                            $res = array();
                        }

                        $cek = array(
                                    "user_aktif.id_user" => $row['id_user'],
                                    "user_aktif.status" => "0"
                            );
                        $cekuser = $this->User_aktif_model->get(null, array('user_aktif.id_user' => $row['id_user']));
                        if (Count($cekuser) > 0) {
                            $cek = array(
                                "user_aktif.status" => "1"
                            );
                            $this->User_aktif_model->updates($row['id_user'], $cek);
                        }
                        // print_r($row['id_user']);
                        $userlogin['id_user']   = $row['id_user'];
                        $userlogin['waktu']   = date('Y-m-d H:i:s');
                        $userlogin['browser']   = $_SERVER['HTTP_USER_AGENT'];
                        $userlogin['address']   = $_SERVER['REMOTE_ADDR'];
                        $this->User_aktif_model->add($userlogin);

                        $sess_data = array(
                            'sess_user_id' => $row['id_user'],
                            'user_group' => $row['id_group'],
                            'sess_nama' => $row['name'],
                            'sess_photo' => $row['photo'],
                            'sess_menu' => $row['menu'],
                            'sess_level' => $row['level'],
                            'website_admin_logged_in' => TRUE,
                            'child' => $res
                        );
                        $this->session->set($sess_data);
                        $data['success'] = true;
                        $data['message'] = 'Success!';
                        $this->cekLog('Login');
                    } else {
                        $data['success'] = false;
                        $data['errors'] = array('password' => 'Password Anda salah.');
                    }
                }
            } else {
                $data['success'] = false;
                $data['errors'] = array('username' => 'Username Anda salah / Akun Tidak Aktif.');
            }
        }

        echo json_encode($data);
    }

    function getDefault($data){
        $result = array();
        foreach ($data as $dt){
            // print_r($dt->key);
            $result[$dt['key']] = $dt['value'];
        }
        return $result;
    }

    public function keluar() {
        if($this->session->get('sess_user_id') != "" && $this->session->get('sess_user_id') != null){
            $this->cekLog('Logout');
        }
        $cek = array(
            "user_aktif.status" => "1"
        );
        $this->User_aktif_model->updates($this->session->get('sess_user_id'), $cek);

        $this->session->destroy();
        return redirect()->to(base_url().'login');
    }

    public function cekLog($action) {
        if($this->default['logRecord'] == 1 || $this->default['logRecord'] == 3){
            $path = 'files/logs/';
            $message = date($this->default['dateTimeDB']) . '; ' . $action . ' ; ; ; ';
            $filename = $path . $this->session->get('sess_user_id') . ".txt";
            if(!file_exists($filename)){
                $fp = fopen($filename, "w");
                file_put_contents($filename, "DATE TIME; ACTION; MODUL; MESSAGE;  \n", FILE_APPEND);
            }

            if($this->default['logRecord'] == 1 || $this->default['logRecord'] == 3){
                file_put_contents($filename, "\n" . $message, FILE_APPEND);
            }
        }

        if($this->default['logRecord'] == 2 || $this->default['logRecord'] == 3){
            $input["id"]         = $this->uuid->v4();
            $input["id_user"]    = $this->session->get('sess_user_id');
            $input["username"]   = $this->session->get('sess_nama');
            $input["datetime"]   = date($this->default['dateTimeDB']);
            $input["action"]     = $action;
            // dd($input);
            $this->Log_activity_model->add($input);
        }
    }

}
