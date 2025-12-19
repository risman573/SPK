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

    /**
     * Get Statistik untuk dashboard
     */
    public function get_statistik() {
        $db = \Config\Database::connect();

        $total_alternatif = $db->table('alternatif')
            ->where('status', 1)
            ->countAllResults();

        $total_kriteria = $db->table('kriteria')
            ->where('status', 1)
            ->countAllResults();

        $total_normalisasi = $db->table('normalisasi')
            ->countAllResults();

        echo json_encode([
            'total_alternatif' => $total_alternatif,
            'total_kriteria' => $total_kriteria,
            'total_normalisasi' => $total_normalisasi
        ]);
    }

    /**
     * Get Data Normalisasi dalam bentuk Tabel 4 Jurnal
     */
    public function get_normalisasi() {
        $db = \Config\Database::connect();

        $query = "
            SELECT
                a.id_alternatif,
                a.nama_alternatif,
                k.id_kriteria,
                k.nama_kriteria,
                n.nilai_normalisasi
            FROM normalisasi n
            JOIN alternatif a ON n.id_alternatif = a.id_alternatif
            JOIN kriteria k ON n.id_kriteria = k.id_kriteria
            WHERE n.status = 1 AND a.status = 1 AND k.status = 1
            ORDER BY a.nama_alternatif, k.nama_kriteria
        ";

        $result = $db->query($query)->getResultArray();
        echo json_encode($result);
    }

    /**
     * FUNGSI UTAMA: Calculate Normalisasi (Auto)
     */
    public function calculate() {
        $db = \Config\Database::connect();

        try {
            // 1. Hapus data normalisasi lama
            $db->table('normalisasi')->truncate();

            // 2. Hitung Normalisasi dengan Query
            $query = "
                INSERT INTO normalisasi (id_normalisasi, id_alternatif, id_kriteria, nilai_normalisasi, created_date, modified_date)
                SELECT
                    UUID() as id_normalisasi,
                    n.id_alternatif,
                    n.id_kriteria,
                    CASE
                        WHEN k.atribut = 'benefit' THEN
                            n.nilai / (SELECT MAX(nilai) FROM nilai WHERE id_kriteria = n.id_kriteria AND status = 1)
                        WHEN k.atribut = 'cost' THEN
                            (SELECT MIN(nilai) FROM nilai WHERE id_kriteria = n.id_kriteria AND status = 1) / n.nilai
                        ELSE 0
                    END as nilai_normalisasi,
                    NOW() as created_date,
                    NOW() as modified_date
                FROM
                    nilai n
                INNER JOIN
                    kriteria k ON n.id_kriteria = k.id_kriteria
                WHERE
                    n.status = 1 AND k.status = 1
            ";

            $db->query($query);

            // 3. Hitung total data
            $total = $db->table('normalisasi')->countAllResults();

            echo json_encode([
                'success' => true,
                'message' => 'Normalisasi berhasil dihitung',
                'total' => $total
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}