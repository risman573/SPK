<?php

namespace App\Models;

use CodeIgniter\Model;

class Penilaian_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "nilai";
        $this->primaryKey = "nilai.id_nilai";
        $this->fields = [
            "nilai.id_nilai",
            "nilai.id_alternatif",
            "nilai.id_kriteria",
            "alternatif.nama_alternatif AS nama_alternatif",
            "kriteria.nama_kriteria AS nama_kriteria",
            "nilai.nilai AS nilai",
            "status.nama AS status_name",
            "nilai.created_date",
            "nilai.created_user",
            "nilai.modified_date",
            "nilai.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
        ];
        $this->orderBy = array("nilai.created_date" => "DESC");
        $this->relations = array(
            "alternatif" => "alternatif.id_alternatif=nilai.id_alternatif",
            "kriteria" => "kriteria.id_kriteria=nilai.id_kriteria",
            "combo AS status" => "status.kode=nilai.status and status.flag='status' and status.status<9",
            "user AS user1" => "user1.id_user=nilai.created_user",
            "user AS user2" => "user2.id_user=nilai.modified_user"
        );
        $this->joins = array("left", "left", "left", "left", "left");
    }
}
