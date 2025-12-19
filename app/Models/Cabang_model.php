<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class Cabang_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "data_full";
        $this->primaryKey = "data_full.id";
    }

    /**
     * Get cabang (branch) statistics - anggota per cabang
     */
    function getCabangData($filters = array()) {
        $where = " WHERE df.status < 9 ";
        
        if($this->session->get('sess_level') == '1'){
            $where .= ' AND df.cabang = "'.$this->session->get('sess_cabang').'"';
        }
        
        // Apply filters
        if (!empty($filters['provinsi'])) {
            $where .= " AND df.provinsi = '" . $this->db->escapeString($filters['provinsi']) . "'";
        }
        if (!empty($filters['kota'])) {
            $where .= " AND df.kota = '" . $this->db->escapeString($filters['kota']) . "'";
        }
        if (!empty($filters['cabang'])) {
            $where .= " AND df.cabang = '" . $this->db->escapeString($filters['cabang']) . "'";
        }
        // status_user filter: support mapped values similar to PublicDokter::search
        if (isset($filters['status_user']) && $filters['status_user'] !== '') {
            $su = $filters['status_user'];
            // if passed numeric like '1' or '2', map to rules
            if ((string)$su === '1') {
                $where .= " AND (df.status_user = 'Konsultan' OR df.status_user = 'Fellowship')";
            } elseif ((string)$su === '2') {
                // status 2 in public search used to filter by kodi non-empty
                $where .= " AND (df.kodi IS NOT NULL AND df.kodi <> '')";
            } else {
                // otherwise treat as exact status_user match (escaped)
                $where .= " AND df.status_user = '" . $this->db->escapeString($su) . "'";
            }
        }

        $sql = "
            SELECT 
                df.cabang,
                df.provinsi,
                df.kota,
                COUNT(*) AS jumlah,
                (
                    SELECT COUNT(DISTINCT alamat_praktek)
                    FROM (
                        SELECT praktek_1 AS alamat_praktek, provinsi, kota, cabang
                        FROM data_full
                        WHERE praktek_1 IS NOT NULL AND praktek_1 <> ''
                        AND (
                                LOWER(praktek_1) LIKE '%rs%' OR 
                                LOWER(praktek_1) LIKE '%rumah sakit%' OR 
                                LOWER(praktek_1) LIKE '%hospital%'
                            )
                        UNION ALL
                        SELECT praktek_2 AS alamat_praktek, provinsi, kota, cabang
                        FROM data_full
                        WHERE praktek_2 IS NOT NULL AND praktek_2 <> ''
                        AND (
                                LOWER(praktek_2) LIKE '%rs%' OR 
                                LOWER(praktek_2) LIKE '%rumah sakit%' OR 
                                LOWER(praktek_2) LIKE '%hospital%'
                            )
                        UNION ALL
                        SELECT praktek_3 AS alamat_praktek, provinsi, kota, cabang
                        FROM data_full
                        WHERE praktek_3 IS NOT NULL AND praktek_3 <> ''
                        AND (
                                LOWER(praktek_3) LIKE '%rs%' OR 
                                LOWER(praktek_3) LIKE '%rumah sakit%' OR 
                                LOWER(praktek_3) LIKE '%hospital%'
                            )
                    ) AS rs_union
                    WHERE 
                        rs_union.provinsi = df.provinsi
                        AND rs_union.kota = df.kota
                        AND rs_union.cabang = df.cabang
                ) AS jumlah_rs
            FROM data_full df
            $where
            GROUP BY df.cabang, df.provinsi, df.kota
            ORDER BY jumlah DESC
        ";
        // print_r($sql);exit;
        
        try {
            $query = $this->db->query($sql);
            return $query->getResult();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getCabangData() - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get trend data for member growth per branch (monthly)
     */
    function getTrendData($filters = array()) {
        $where = " WHERE data_full.status < 9 AND cabang IS NOT NULL AND cabang <> ''";
        
        // Apply filters (same as above)
        if (!empty($filters['provinsi'])) {
            $where .= " AND (provinsi = '" . $this->db->escapeString($filters['provinsi']) . "' OR 
                            provinsi_1 = '" . $this->db->escapeString($filters['provinsi']) . "' OR
                            provinsi_2 = '" . $this->db->escapeString($filters['provinsi']) . "' OR
                            provinsi_3 = '" . $this->db->escapeString($filters['provinsi']) . "')";
        }
        if (!empty($filters['kota'])) {
            $where .= " AND (kota = '" . $this->db->escapeString($filters['kota']) . "' OR 
                            kota_1 = '" . $this->db->escapeString($filters['kota']) . "' OR
                            kota_2 = '" . $this->db->escapeString($filters['kota']) . "' OR
                            kota_3 = '" . $this->db->escapeString($filters['kota']) . "')";
        }
        if (!empty($filters['cabang'])) {
            $where .= " AND cabang = '" . $this->db->escapeString($filters['cabang']) . "'";
        }

        $sql = "
            SELECT 
                DATE_FORMAT(created_date, '%b') AS bulan,
                DATE_FORMAT(created_date, '%Y-%m') AS `year_month`,
                COUNT(*) AS anggota
            FROM data_full
            $where
            AND created_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_date, '%Y-%m')
            ORDER BY `year_month` ASC
        ";
        
        try {
            $query = $this->db->query($sql);
            return $query->getResult();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getTrendData() - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get distribusi data konsultan vs fellowship per provinsi
     */
    function getDistribusiData($filters = array()) {
        $where = " WHERE data_full.status < 9";
        
        if($this->session->get('sess_level') == '1'){
            $where .= ' AND data_full.cabang = "'.$this->session->get('sess_cabang').'"';
        }
        
        // Apply filters
        if (!empty($filters['provinsi'])) {
            $where .= " AND (provinsi = '" . $this->db->escapeString($filters['provinsi']) . "' )";
        }
        if (!empty($filters['kota'])) {
            $where .= " AND (kota = '" . $this->db->escapeString($filters['kota']) . "' ";
        }
        if (!empty($filters['cabang'])) {
            $where .= " AND cabang = '" . $this->db->escapeString($filters['cabang']) . "'";
        }

        $sql = "
            SELECT 
                COALESCE(provinsi, provinsi_1, provinsi_2, provinsi_3) AS provinsi,
                SUM(CASE WHEN status_user = 'Konsultan' THEN 1 ELSE 0 END) AS konsultan,
                SUM(CASE WHEN status_user = 'Fellowship' THEN 1 ELSE 0 END) AS fellowship
            FROM data_full
            $where
            AND COALESCE(provinsi, provinsi_1, provinsi_2, provinsi_3) IS NOT NULL
            AND COALESCE(provinsi, provinsi_1, provinsi_2, provinsi_3) <> ''
            GROUP BY COALESCE(provinsi, provinsi_1, provinsi_2, provinsi_3)
            HAVING (SUM(CASE WHEN status_user = 'Konsultan' THEN 1 ELSE 0 END) > 0 OR 
                    SUM(CASE WHEN status_user = 'Fellowship' THEN 1 ELSE 0 END) > 0)
            ORDER BY (SUM(CASE WHEN status_user = 'Konsultan' THEN 1 ELSE 0 END) + 
                     SUM(CASE WHEN status_user = 'Fellowship' THEN 1 ELSE 0 END)) DESC
        ";
        
        try {
            $query = $this->db->query($sql);
            return $query->getResult();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getDistribusiData() - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get list of provinces for filter dropdown
     */
    function getProvinsiList() {
        $where = 'WHERE data_full.status < 9';
        if($this->session->get('sess_level') == '1'){
            $where .= ' AND data_full.cabang = "'.$this->session->get('sess_cabang').'"';
        }
        $sql = "
            SELECT DISTINCT provinsi
            FROM data_full
            $where
            ORDER BY provinsi ASC
        ";
        
        try {
            $query = $this->db->query($sql);
            return $query->getResult();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getProvinsiList() - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get statistics for a specific province
     */
    function getProvinsiStats($provinsi) {
        if (empty($provinsi)) {
            return null;
        }

        // Get total anggota for province
        $sql_anggota = "
            SELECT COUNT(*) AS total_anggota
            FROM data_full
            WHERE provinsi = '" . $this->db->escapeString($provinsi) . "' AND status < 9
        ";
        
        try {
            $anggota_result = $this->db->query($sql_anggota)->getRow();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getProvinsiStats() anggota query - ' . $e->getMessage());
            return null;
        }

        // Get unique RS count for province
        $sql_rs = "
            SELECT COUNT(*) AS total_rs FROM (
                SELECT DISTINCT praktek FROM (
                    SELECT LOWER(praktek_1) AS praktek FROM data_full 
                    WHERE provinsi = '" . $this->db->escapeString($provinsi) . "' AND praktek_1 IS NOT NULL AND praktek_1 <> ''
                    UNION
                    SELECT LOWER(praktek_2) AS praktek FROM data_full 
                    WHERE provinsi_1 = '" . $this->db->escapeString($provinsi) . "' AND praktek_2 IS NOT NULL AND praktek_2 <> ''
                    UNION
                    SELECT LOWER(praktek_3) AS praktek FROM data_full 
                    WHERE provinsi_2 = '" . $this->db->escapeString($provinsi) . "' AND praktek_3 IS NOT NULL AND praktek_3 <> ''
                ) p
                WHERE praktek LIKE '%rs%' OR praktek LIKE '%rumah sakit%' OR praktek LIKE '%hospital%'
            ) rs_list
        ";
        
        try {
            $rs_result = $this->db->query($sql_rs)->getRow();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getProvinsiStats() RS query - ' . $e->getMessage());
            $rs_result = (object)['total_rs' => 0];
        }

        // Get cabang count for province
        $sql_cabang = "
            SELECT COUNT(DISTINCT cabang) AS total_cabang
            FROM data_full
            WHERE provinsi = '" . $this->db->escapeString($provinsi) . "' AND status < 9 AND cabang IS NOT NULL AND cabang <> ''
        ";
        
        try {
            $cabang_result = $this->db->query($sql_cabang)->getRow();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getProvinsiStats() cabang query - ' . $e->getMessage());
            $cabang_result = (object)['total_cabang' => 0];
        }

        return (object)[
            'provinsi' => $provinsi,
            'total_anggota' => $anggota_result->total_anggota ?? 0,
            'total_rs' => $rs_result->total_rs ?? 0,
            'total_cabang' => $cabang_result->total_cabang ?? 0
        ];
    }

    /**
     * Get kota list for specific province
     */
    function getKotaByProvinsi($provinsi) {
        if (empty($provinsi)) {
            return array();
        }

        $sql = "
            SELECT DISTINCT 
                CASE 
                    WHEN kota IS NOT NULL AND kota <> '' AND (provinsi = '" . $this->db->escapeString($provinsi) . "') THEN kota
                    WHEN kota_1 IS NOT NULL AND kota_1 <> '' AND (provinsi_1 = '" . $this->db->escapeString($provinsi) . "') THEN kota_1
                    WHEN kota_2 IS NOT NULL AND kota_2 <> '' AND (provinsi_2 = '" . $this->db->escapeString($provinsi) . "') THEN kota_2
                    WHEN kota_3 IS NOT NULL AND kota_3 <> '' AND (provinsi_3 = '" . $this->db->escapeString($provinsi) . "') THEN kota_3
                END AS kota_name
            FROM data_full
            WHERE (provinsi = '" . $this->db->escapeString($provinsi) . "' OR 
                   provinsi_1 = '" . $this->db->escapeString($provinsi) . "' OR
                   provinsi_2 = '" . $this->db->escapeString($provinsi) . "' OR
                   provinsi_3 = '" . $this->db->escapeString($provinsi) . "')
            AND status < 9
            HAVING kota_name IS NOT NULL AND kota_name <> ''
            ORDER BY kota_name ASC
        ";
        
        try {
            $query = $this->db->query($sql);
            return $query->getResult();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getKotaByProvinsi() - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cabang list for specific province and kota
     */
    function getCabangByProvinsiKota($provinsi, $kota = '') {
        $where = "WHERE status < 9 AND cabang IS NOT NULL AND cabang <> '' ";
        // if (!empty($provinsi)) {
        //     // return array();Z
        //     $where .= "AND (provinsi = '" . $this->db->escapeString($provinsi) . "' OR 
        //                     provinsi_1 = '" . $this->db->escapeString($provinsi) . "' OR
        //                     provinsi_2 = '" . $this->db->escapeString($provinsi) . "' OR
        //                     provinsi_3 = '" . $this->db->escapeString($provinsi) . "')";
        // }


        // if (!empty($kota)) {
        //     $where .= " AND (kota = '" . $this->db->escapeString($kota) . "' OR 
        //                     kota_1 = '" . $this->db->escapeString($kota) . "' OR
        //                     kota_2 = '" . $this->db->escapeString($kota) . "' OR
        //                     kota_3 = '" . $this->db->escapeString($kota) . "')";
        // }

        $sql = "
            SELECT DISTINCT cabang, COUNT(*) as jumlah_anggota
            FROM data_full
            $where
            GROUP BY cabang
            ORDER BY jumlah_anggota DESC
        ";
        
        try {
            $query = $this->db->query($sql);
            return $query->getResult();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getCabangByProvinsiKota() - ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cabang list for specific province (legacy method)
     */
    /**
     * Get cabang list for specific province (legacy method)
     */
    function getCabangByProvinsi($provinsi) {
        return $this->getCabangByProvinsiKota($provinsi);
    }

    /**
     * Get detailed cabang information
     */
    function getCabangDetail($cabang) {
        if (empty($cabang)) {
            return null;
        }

        $sql = "
            SELECT 
                cabang,
                COUNT(*) AS total_anggota,
                COUNT(DISTINCT provinsi) AS provinsi_count,
                COUNT(DISTINCT kota) AS kota_count,
                SUM(CASE WHEN status_user = 'Konsultan' THEN 1 ELSE 0 END) AS konsultan_count,
                SUM(CASE WHEN status_user = 'Fellowship' THEN 1 ELSE 0 END) AS fellowship_count
            FROM data_full
            WHERE cabang = '" . $this->db->escapeString($cabang) . "' AND status < 9
            GROUP BY cabang
        ";
        
        try {
            $query = $this->db->query($sql);
            return $query->getRow();
        } catch (Exception $e) {
            log_message('error', 'Cabang_model::getCabangDetail() - ' . $e->getMessage());
            return null;
        }
    }
}