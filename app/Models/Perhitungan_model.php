<?php

namespace App\Models;

use CodeIgniter\Model;

class Perhitungan_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "normalisasi";
        $this->primaryKey = "normalisasi.id_normalisasi";
        $this->fields = array(
            "normalisasi.id_normalisasi",
            "normalisasi.id_alternatif",
            "normalisasi.id_kriteria",
            "alternatif.nama_alternatif AS nama_alternatif",
            "kriteria.nama_kriteria AS nama_kriteria",
            "normalisasi.nilai_normalisasi",
            "normalisasi.status",
            "status.nama AS status_name",
            "normalisasi.created_date",
            "normalisasi.created_user",
            "normalisasi.modified_date",
            "normalisasi.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
        );
        $this->orderBy = array("normalisasi.created_date" => "DESC");
        $this->relations = array(
            "alternatif" => "alternatif.id_alternatif=normalisasi.id_alternatif",
            "kriteria" => "kriteria.id_kriteria=normalisasi.id_kriteria",
            "combo AS status" => "status.kode=normalisasi.status and status.flag='status' and status.status<9",
            "user AS user1" => "user1.id_user=normalisasi.created_user",
            "user AS user2" => "user2.id_user=normalisasi.modified_user"
        );
        $this->joins = array("left", "left", "left", "left", "left");
    }
}
