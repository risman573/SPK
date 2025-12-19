<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;
use App\Models\Perhitungan_model;

class Perhitungan extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Perhitungan_model = new Perhitungan_model();

        $this->model = $this->Perhitungan_model;
        $this->table = "normalisasi";
        $this->pkField = "id_normalisasi";
        $this->kode = "id_normalisasi";

        $this->fields = array(
            "nama_alternatif" => array("TIPE" => "STRING", "LABEL" => "Alternatif"),
            "nama_kriteria" => array("TIPE" => "STRING", "LABEL" => "Kriteria"),
            "nilai_normalisasi" => array("TIPE" => "STRING", "LABEL" => "Normalisasi"),
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
            return view('perhitungan_view', $this->data);
        }
    }

    public function dataInput() {
        $this->form_validation->setRule('id_alternatif', 'Alternative', 'required');
        $this->form_validation->setRule('id_kriteria', 'Kriteria', 'required');
        $this->form_validation->setRule('nilai_normalisasi', 'Normalized Value', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[1]');

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
