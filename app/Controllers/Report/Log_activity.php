<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
class Log_activity extends MY_Controller {

    function __construct() {
        parent::__construct();
        
        //$this->kode_transaksi = "USER";

        $mdl = "log_activity_model";
        $this->model_name = $mdl;
        $this->load->model($mdl);
        $this->model = $this->$mdl;
        $this->table = "default";
        $this->pkField = "id";
        $this->uniqueFields = array("key");
        $this->kode = "key";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "datetime" => array("TIPE" => "DATETIME", "LABEL" => "Key"),
            "username" => array("TIPE" => "STRING", "LABEL" => "Value"),
            "aksi" => array("TIPE" => "STRING", "LABEL" => "Description"),
            "modul" => array("TIPE" => "STRING", "LABEL" => "Description"),
            "message" => array("TIPE" => "STRING", "LABEL" => "Description"),
        );
        //$this->kondisi = array("username"=>$this->input->post("username"),
        //    "password"=>md5($this->input->post("password")));
        
        //$this->model->fieldsView = $this->fields;
    }
    
    public function index() {
        $data = array(
            'base_url' => base_url(),
        );
        if($this->lihat!=1){
            $this->parser->parse('noaccess_view', $data);
        }else{
            $this->parser->parse('report/log_activity_view', $data);  
        }
    }
    
    public function dataInput() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        //$this->form_validation->set_rules('kode', 'Kode', 'trim|required|min_length[3]|max_length[255]');
//        $this->form_validation->set_rules('key', 'Key', 'trim|required|min_length[1]|max_length[255]');
//        $this->form_validation->set_rules('value', 'Value', 'trim|required|min_length[1]|max_length[255]');
        

        if ($this->form_validation->run() == FALSE) {
            return array("valid" => FALSE, "error" =>  $this->form_validation->listErrors());
        } else {
            $data = array();
            foreach ($this->input->post() as $key => $value) {
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