<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;
use App\Models\Kriteria_model;

class Kriteria extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Kriteria_model = new Kriteria_model();

        $this->model = $this->Kriteria_model;
        $this->table = "kriteria";
        $this->pkField = "id_kriteria";
        $this->uniqueFields = array("nama_kriteria");
        $this->kode = "nama_kriteria";

        //pair key value (field => TYPE)
        $this->fields = array(
            "nama_kriteria" => array("TIPE" => "STRING", "LABEL" => "Kriteria Name"),
            "bobot" => array("TIPE" => "STRING", "LABEL" => "Weight (%)"),
            "atribut" => array("TIPE" => "STRING", "LABEL" => "Attribute Type"),
            "status_name" => array("TIPE" => "STRING", "LABEL" => "Status"),
            "created" => array("TIPE" => "STRING", "LABEL" => "Created User"),
            "created_date" => array("TIPE" => "DATETIME", "LABEL" => "Created Date"),
            "modified" => array("TIPE" => "STRING", "LABEL" => "Modified User"),
            "modified_date" => array("TIPE" => "DATETIME", "LABEL" => "Modified Date"),
            "action" => array("TIPE" => "TRANSACTION", "LABEL" => "Action"),
        );
    }

    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        if($this->lihat!=1){
            return view('noaccess_view', $this->data);
        }else{
            return view('/kriteria_view', $this->data);
        }
    }

    public function dataInput() {
        $this->form_validation->setRule('nama_kriteria', 'Kriteria Name', 'required|min_length[3]|max_length[100]');
        $this->form_validation->setRule('bobot', 'Weight', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->setRule('atribut', 'Attribute Type', 'required');

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
        }

        return array("valid" => TRUE, "data" => $data);
    }
}
