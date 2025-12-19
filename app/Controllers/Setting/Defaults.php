<?php

namespace App\Controllers\Setting;
 
use App\Controllers\MY_Controller;
use App\Models\Defaults_model;
 
class Defaults extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Defaults_model = new Defaults_model();
        
        $this->model = $this->Defaults_model;
        $this->table = "default";
        $this->pkField = "id";
        $this->uniqueFields = array("key");
        $this->kode = "key";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "key" => array("TIPE" => "STRING", "LABEL" => "Key"),
            "value" => array("TIPE" => "STRING", "LABEL" => "Value"),
            "description" => array("TIPE" => "STRING", "LABEL" => "Description"),
//            "status" => array("TIPE" => "STATUS", "LABEL" => "Status"),
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
            return view('setting/defaults_view', $this->data); 
        }
    }
    
    public function getCombo($tipe = null, $flag = null){
    	$data=$this->Defaults_model->getCombo($tipe, $flag);
	    echo json_encode($data,JSON_NUMERIC_CHECK);
    }
    
    public function dataCombo($tipe = null, $flag = null){
    	$data=$this->Defaults_model->dataCombo($tipe, $flag);
	    echo json_encode($data,JSON_NUMERIC_CHECK);
    }
    
    public function dataInput() {
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('', '');

        //$this->form_validation->setRule('kode', 'Kode', 'required|min_length[3]|max_length[255]');
        $this->form_validation->setRule('key', 'Key', 'required|min_length[1]|max_length[255]');
//        $this->form_validation->setRule('value', 'Value', 'required|min_length[1]|max_length[255]');
        

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