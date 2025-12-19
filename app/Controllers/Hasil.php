<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;

class Hasil extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
        return view('hasil_view', $this->data);
    }

    /**
     * Calculate Preferensi dan Ranking
     */
    public function calculate() {
        $db = \Config\Database::connect();

        try {
            // 1. Hapus data hasil lama
            $db->table('hasil')->truncate();

            // 2. Hitung Nilai Preferensi (Vi)
            $query_preferensi = "
                INSERT INTO hasil (id_hasil, id_alternatif, nilai_preferensi, ranking, created_date, modified_date)
                SELECT
                    UUID() as id_hasil,
                    n.id_alternatif,
                    SUM(k.bobot * n.nilai_normalisasi) as nilai_preferensi,
                    0 as ranking,
                    NOW() as created_date,
                    NOW() as modified_date
                FROM
                    normalisasi n
                INNER JOIN
                    kriteria k ON n.id_kriteria = k.id_kriteria
                GROUP BY
                    n.id_alternatif
            ";

            $db->query($query_preferensi);

            // 3. Update Ranking
            $query_ranking = "
                SET @rank = 0;
                UPDATE hasil h
                INNER JOIN (
                    SELECT
                        id_hasil,
                        @rank := @rank + 1 as new_rank
                    FROM
                        hasil
                    ORDER BY
                        nilai_preferensi DESC
                ) ranked ON h.id_hasil = ranked.id_hasil
                SET h.ranking = ranked.new_rank
            ";

            $db->query("SET @rank = 0");
            $db->query("
                UPDATE hasil h
                INNER JOIN (
                    SELECT
                        id_hasil,
                        @rank := @rank + 1 as new_rank
                    FROM
                        hasil
                    ORDER BY
                        nilai_preferensi DESC
                ) ranked ON h.id_hasil = ranked.id_hasil
                SET h.ranking = ranked.new_rank
            ");

            $total = $db->table('hasil')->countAllResults();

            echo json_encode([
                'success' => true,
                'message' => 'Ranking berhasil dihitung',
                'total' => $total
            ]);

        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get Data Ranking
     */
    public function get_ranking() {
        $db = \Config\Database::connect();

        $query = "
            SELECT
                h.ranking,
                a.nama_alternatif as merk_laptop,
                h.nilai_preferensi as vi,
                CASE h.ranking
                    WHEN 1 THEN '🥇 TERBAIK'
                    WHEN 2 THEN '🥈'
                    WHEN 3 THEN '🥉'
                    ELSE ''
                END as badge
            FROM
                hasil h
            INNER JOIN
                alternatif a ON h.id_alternatif = a.id_alternatif
            ORDER BY
                h.ranking ASC
        ";

        $result = $db->query($query)->getResultArray();
        echo json_encode($result);
    }
}