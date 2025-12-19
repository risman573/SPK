<?php

namespace App\Models;

use CodeIgniter\Model;

class Kriteria_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "kriteria";
        $this->primaryKey = "kriteria.id_kriteria";
        $this->fields = array(
            "kriteria.id_kriteria",
            "kriteria.nama_kriteria",
            "kriteria.bobot",
            "kriteria.atribut",
            "kriteria.status",
            "status.nama AS status_name",
            "kriteria.created_date",
            "kriteria.created_user",
            "kriteria.modified_date",
            "kriteria.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
        );
        $this->orderBy = array("kriteria.created_date" => "DESC");
        $this->relations = array(
            "combo AS status" => "status.kode=kriteria.status and status.flag='status' and status.status<9",
            "user AS user1" => "user1.id_user=kriteria.created_user",
            "user AS user2" => "user2.id_user=kriteria.modified_user"
        );
        $this->joins = array("left", "left", "left");
    }
}
