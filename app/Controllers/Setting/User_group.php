<?php

namespace App\Controllers\Setting;

use App\Controllers\MY_Controller;
use App\Models\User_group_model;

class User_group extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->User_group_model = new User_group_model();

        $this->model = $this->User_group_model;
        $this->table = "user_group";
        $this->pkField = "id_group";
        $this->uniqueFields = array("group_name");
        $this->kode = "group_name";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "group_name" => array("TIPE" => "STRING", "LABEL" => "Nama"),
            "status_name" => array("TIPE" => "STRING", "LABEL" => "Status"),
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
            return view('setting/user_group_view', $this->data);
        }
    }

    public function dataInput() {
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('', '');
        // $this->form_validation->unset_field_data ('', '');

        $this->form_validation->setRule('group_name', 'Group Name', 'required|min_length[3]');
        $this->form_validation->setRule('status', 'Status', 'required');


        // $this->form_validation->setRules([
        //     'group_name' => 'required|min_length[10]',
        //     'password' => 'required|min_length[10]',
        // ]);

        // dd($this->form_validation->withRequest($this->request)->run());
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
                } else {
                    if (isset($value)) {
                        $data[$key] = $value;
                    }
                }
            }

            return array("valid" => TRUE, "data" => $data);
        }
    }

    public function event() {
        $event = $this->User_group_model->event();
        foreach ($event as $value) {
            echo $value->id_event;
        }
    }

    function getGrup(){
    	$data=$this->User_group_model->getGrup();
	    echo json_encode($data,JSON_NUMERIC_CHECK);
    }

}