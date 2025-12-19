<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;
use App\Models\Dashboard_model;
use App\Models\Data_full_model;

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();
        
        $this->Dashboard_model = new Dashboard_model();
    }

    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        return view('dashboard_view', $this->data);
    }
    
    function data(){
        // $this->db = $this->load->database('iowork',TRUE);
        // $this->load->model('Data_full_model');
        $this->Data_full_model = new Data_full_model();
        // $this->load->model('M_pekerja_model');
        // $this->load->model('T_tugas_pekerja_model');

        $dsum = $this->Data_full_model->getDashboardSummary();
        $dbar = $this->Data_full_model->getDashboardBar();
        $dpie = $this->Data_full_model->getDashboardPie();

        $dataPie = [
            ['category'=>'Konsultan','value'=>$dpie->Konsultan],
            ['category'=>'Fellowship','value'=>$dpie->Fellowship],
        ];
        echo json_encode(
            array(
                'cabang'=> $dsum->total_cabang,
                'anggota'=> $dsum->total_anggota,
                'konsultan'=> $dsum->konsultan_fellowship,
                'rs'=> $dsum->rs_tht,
                'bar'=> $dbar,
                'pie'=> $dataPie,
            )
        );
    }
    
}
