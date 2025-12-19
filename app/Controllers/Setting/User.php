<?php

namespace App\Controllers\Setting;

use App\Controllers\MY_Controller;
use App\Models\User_model;

class User extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->User_model = new User_model();

        $this->model = $this->User_model;
        $this->table = "user";
        $this->pkField = "id_user";
        $this->uniqueFields = array("username");
        $this->kode = "username";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "name" => array("TIPE" => "STRING", "LABEL" => "Name"),
            "username" => array("TIPE" => "STRING", "LABEL" => "Username"),
            "group_name" => array("TIPE" => "STRING", "LABEL" => "Group Name"),
            "status_dok" => array("TIPE" => "STRING", "LABEL" => "Status"),
            "created" => array("TIPE" => "STRING", "LABEL" => "Created User"),
            "created_date" => array("TIPE" => "DATETIME", "LABEL" => "Created Date"),
            "modified" => array("TIPE" => "STRING", "LABEL" => "Modified User"),
            "modified_date" => array("TIPE" => "DATETIME", "LABEL" => "Modified Date"),
            "action" => array("TIPE" => "TRANSACTION", "LABEL" => "Action"),
        );
        //$this->kondisi = array("username"=>$this->request->getPost("username"),
        //    "password"=>md5($this->request->getPost("password")));

        //$this->model->fieldsView = $this->fields;
    }

    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        if($this->lihat!=1){
            return view('noaccess_view', $this->data);
        }else{
            return view('setting/user_view', $this->data);
        }
    }

    public function dataInput() {
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('', '');

        if((!$this->request->getPost('photo') || $this->request->getPost('photo')) && $this->request->getPost('password')){
            $this->form_validation->setRule('password', 'Password', 'required|min_length[5]|max_length[255]');
        }else if(!$this->request->getPost('photo')){
            $this->form_validation->setRule('name', 'Name', 'required|min_length[3]|max_length[255]');
            $this->form_validation->setRule('username', 'Username', 'required|min_length[3]|max_length[255]');
//            $this->form_validation->setRule('password', 'Password', 'required|min_length[7]|max_length[255]');
            $this->form_validation->setRule('id_group', 'Menu Group', 'required');
            $this->form_validation->setRule('status', 'Status', 'required');
        }else{
            $this->form_validation->setRule('photo', 'Photo', 'required');
            $sess_data = array(
                'sess_photo' => $this->request->getPost('photo')
            );
            $this->session->set_userdata($sess_data);
        }


        if ($this->form_validation->withRequest($this->request)->run() == FALSE) {
            return array("valid" => FALSE, "error" =>  $this->form_validation->listErrors());
        } else {
            $data = array();
            foreach ($this->request->getPost() as $key => $value) {
                if ($key == "method") {

                } elseif ($key == $this->pkField) {
//                    $data[$key] = !$value ? $this->uuid->v4() : $value;
                    $this->pkFieldValue = !$value ? $this->uuid->v4() : $value;
                    $data[$key] = $this->pkFieldValue;
                } elseif ($key == $this->kode) {
                    $this->kode = $value;
                    $data[$key] = $value;
                } elseif ($key == 'password'){
                    if ($value != '' || $value != null) {
                        $this->salt = "jaskd12341ierupep094cx!((#@*!&)(@(*(*)!*(*@)(!*)(!*)";
                        $password = $this->salt . $value;
                        $data[$key] = md5($password);
//                        $data[$key] = $value;
                    }
                } else {
                    if (isset($value)) {
                        $data[$key] = $value;
                    }
                }
            }

            return array("valid" => TRUE, "data" => $data);
        }
    }

    public function password_check($pwd) {
        $cek = array();
        $cek["m_users.id_user"] = $this->request->getPost("id_user");
        $cek["m_users.password"] = md5($pwd);
        $hasil = $this->User_model->check($cek);
        if ($hasil) {
            return TRUE;
        } else {
            $this->form_validation->set_message("password_check", "Password lama salah.");
            return FALSE;
        }
    }

    public function parent_check($value) {

        $data["m_user.id_user"] = $value;
        $result = $this->User_model->check($data);
        if (!$result) {
            $this->form_validation->set_message(__FUNCTION__, "Parent " . $value . " tidak ada.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function ubah_password() {
        $result = array();
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->setRule('password', 'Password', 'required|max_length[255]|xss_clean|callback_password_check');
        $this->form_validation->setRule('password1', 'Password Baru', 'required|min_length[3]|max_length[255]|xss_clean');
        $this->form_validation->setRule('password2', 'Pengulangan Password', 'required|min_length[3]|max_length[255]|xss_clean|matches[password1]');
        if ($this->form_validation->withRequest($this->request)->run() == FALSE) {
            $result = array("msg" =>  $this->form_validation->listErrors());
        } else {
            $data = array();
            $id_user = $this->request->getPost("id_user");
            $data["m_users.password"] = md5($this->request->getPost("password2"));
            $hasil = $this->User_model->updates($id_user, $data);
            if($hasil) {
                $result = array("success" => TRUE);
            } else {
                $result = array("msg" => "Gagal tersimpan.");
            }
        }
        echo json_encode($result);
    }



    private function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function event() {
        $event = $this->User_model->event();
        foreach ($event as $value) {
            echo $value->id_event;
        }
    }

}