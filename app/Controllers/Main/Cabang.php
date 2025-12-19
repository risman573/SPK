<?php

namespace App\Controllers\main;

use App\Controllers\MY_Controller;
use App\Models\Cabang_model;

class Cabang extends MY_Controller {

    function __construct() {
        parent::__construct();
        
        $this->Cabang_model = new Cabang_model();
    }

    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        return view('main/cabang_view', $this->data);
    }
    
    function data(){
        // Get filters from request
        $filters = array();
        if ($this->request->getGet('provinsi')) {
            $filters['provinsi'] = $this->request->getGet('provinsi');
        }
        if ($this->request->getGet('kota')) {
            $filters['kota'] = $this->request->getGet('kota');
        }
        if ($this->request->getGet('cabang')) {
            $filters['cabang'] = $this->request->getGet('cabang');
        }
        if ($this->request->getGet('status_user')) {
            $filters['status_user'] = $this->request->getGet('status_user');
        }

        // Get cabang data (bar chart)
        $cabangData = $this->Cabang_model->getCabangData($filters);
        
        // Get distribusi data (grouped bar chart)
        // $distribusiData = $this->Cabang_model->getDistribusiData($filters);
        
        echo json_encode(
            array(
                'cabang' => $cabangData,
                // 'distribusi' => $distribusiData,
                'filters_applied' => $filters
            )
        );
    }

    function provinsi_list() {
        $provinsiList = $this->Cabang_model->getProvinsiList();
        echo json_encode($provinsiList);
    }

    function kota_list() {
        $provinsi = $this->request->getGet('provinsi');
        $kotaList = $this->Cabang_model->getKotaByProvinsi($provinsi);
        echo json_encode($kotaList);
    }

    function cabang_list() {
        $provinsi = $this->request->getGet('provinsi');
        $kota = $this->request->getGet('kota');
        $cabangList = $this->Cabang_model->getCabangByProvinsiKota($provinsi, $kota);
        echo json_encode($cabangList);
    }

    function provinsi_stats() {
        $provinsi = $this->request->getGet('provinsi');
        $stats = $this->Cabang_model->getProvinsiStats($provinsi);
        echo json_encode($stats);
    }

    function cabang_by_provinsi() {
        $provinsi = $this->request->getGet('provinsi');
        $cabangList = $this->Cabang_model->getCabangByProvinsi($provinsi);
        echo json_encode($cabangList);
    }

    function cabang_detail() {
        $cabang = $this->request->getGet('cabang');
        $detail = $this->Cabang_model->getCabangDetail($cabang);
        echo json_encode($detail);
    }
    
}
