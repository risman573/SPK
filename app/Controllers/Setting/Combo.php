<?php

namespace App\Controllers\Setting;

use App\Controllers\MY_Controller;
use App\Models\Combo_model;

class Combo extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Combo_model = new Combo_model();

        $this->model = $this->Combo_model;
        $this->table = "combo";
        $this->pkField = "id";
        $this->uniqueFields = array("kode");
        $this->kode = "kode";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "kode" => array("TIPE" => "STRING", "LABEL" => "Code"),
            "nama" => array("TIPE" => "STRING", "LABEL" => "Nama"),
            "type" => array("TIPE" => "STRING", "LABEL" => "Tipe"),
            "flag" => array("TIPE" => "STRING", "LABEL" => "Flag"),
            "sort" => array("TIPE" => "STRING", "LABEL" => "Sort"),
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
        }
        return view('setting/combo_view', $this->data);
    }

    public function getCombo($tipe = null, $flag = null){
    	$data=$this->Combo_model->getCombo($tipe, $flag);
	    echo json_encode($data,JSON_NUMERIC_CHECK);
    }

    public function dataCombo($tipe = null, $flag = null){
    	$data=$this->Combo_model->dataCombo($tipe, $flag);
	    echo json_encode($data,JSON_NUMERIC_CHECK);
    }

    public function dataInput() {
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('', '');

        //$this->form_validation->setRule('kode', 'Kode', 'required|min_length[3]|max_length[255]');
        $this->form_validation->setRule('nama', 'Nama', 'required|min_length[1]|max_length[255]');


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

}