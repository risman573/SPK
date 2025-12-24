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

            // Get top 10 rankings from hasil table
            $rankings = $db->table('hasil as h')
                ->select('h.ranking, h.nilai_preferensi, a.nama_alternatif, a.id_alternatif')
                ->join('alternatif as a', 'a.id_alternatif = h.id_alternatif', 'left')
                ->where('h.status', 1)
                ->where('a.status', 1)
                ->orderBy('h.nilai_preferensi', 'DESC')
                ->limit(10)
                ->get()
                ->getResult();

            $result = [
                'stat_alternatif' => $stat_alternatif,
                'stat_kriteria' => $stat_kriteria,
                'stat_nilai' => $stat_nilai,
                'stat_normalisasi' => $stat_normalisasi,
                'rankings' => $rankings
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