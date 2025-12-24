<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;
use App\Models\Dashboard_model;

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
        // Disable CodeIgniter's output
        $this->response->setContentType('application/json');

        try {
            $db = \Config\Database::connect();

            // Get summary statistics
            $stat_alternatif = $db->table('alternatif')
                ->where('status', 1)
                ->countAllResults();

            $stat_kriteria = $db->table('kriteria')
                ->where('status', 1)
                ->countAllResults();

            $stat_nilai = $db->table('nilai')
                ->where('status', 1)
                ->countAllResults();

            $stat_normalisasi = $db->table('normalisasi')
                ->where('status', 1)
                ->countAllResults();

            $result = [
                'stat_alternatif' => $stat_alternatif,
                'stat_kriteria' => $stat_kriteria,
                'stat_nilai' => $stat_nilai,
                'stat_normalisasi' => $stat_normalisasi,
            ];

            // Clean output and return JSON
            ob_end_clean();
            ob_start();
            echo json_encode($result);
            $output = ob_get_clean();

            return $this->response
                ->setStatusCode(200)
                ->setBody($output);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'error' => $e->getMessage()
                ]);
        }
    }

}