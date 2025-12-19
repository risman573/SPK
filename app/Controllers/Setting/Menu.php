<?php

namespace App\Controllers\Setting;
 
use App\Controllers\MY_Controller;
use App\Models\Menu_model;
 
class Menu extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Menu_model = new Menu_model();
        
        $this->model = $this->Menu_model;
        $this->table = "menu";
        $this->pkField = "id_menu";
        $this->uniqueFields = array("menu_name");
        $this->kode = "menu_name";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "menu_name" => array("TIPE" => "STRING", "LABEL" => "Menu Name"),
            "parent_name" => array("TIPE" => "STRING", "LABEL" => "Parent Menu"),
            "url" => array("TIPE" => "STRING", "LABEL" => "URL"),
            "sort" => array("TIPE" => "INT", "LABEL" => "Sort"),
            "icon" => array("TIPE" => "ICON", "LABEL" => "Icon"),
            "status_dok" => array("TIPE" => "STRING", "LABEL" => "Status"),
            "created" => array("TIPE" => "STRING", "LABEL" => "Created User"),
            "created_date" => array("TIPE" => "DATETIME", "LABEL" => "Created Date"),
            "modified" => array("TIPE" => "STRING", "LABEL" => "Modified User"),
            "modified_date" => array("TIPE" => "DATETIME", "LABEL" => "Modified Date"),
            "action" => array("TIPE" => "TRANSACTION", "LABEL" => "Action"),
        );
        
        //$this->model->fieldsView = $this->fields;
    }
    
    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        if($this->lihat!=1){
            return view('noaccess_view', $this->data);
        }else{
            return view('setting/menu_view', $this->data); 
        }
    }
    
    public function dataInput() {
        // $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->setRule('menu_name', 'Menu Name', 'required|min_length[3]|max_length[255]');
        $this->form_validation->setRule('type', 'Menu Type', 'required');
        $this->form_validation->setRule('url', 'URL', 'required|min_length[1]|max_length[255]');
        $this->form_validation->setRule('status', 'Status', 'required');
        

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