<?php

namespace App\Models;

use CodeIgniter\Model;

class Alternatif_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "alternatif";
        $this->primaryKey = "alternatif.id_alternatif";
        $this->fields = array(
            "alternatif.id_alternatif",
            "alternatif.nama_alternatif",
            "alternatif.status",
            "status.nama AS status_name",
            "alternatif.created_date",
            "alternatif.created_user",
            "alternatif.modified_date",
            "alternatif.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
        );
        $this->orderBy = array("alternatif.created_date" => "DESC");
        $this->relations = array(
            "combo AS status" => "status.kode=alternatif.status and status.flag='status' and status.status<9",
            "user AS user1" => "user1.id_user=alternatif.created_user",
            "user AS user2" => "user2.id_user=alternatif.modified_user"
        );
        $this->joins = array("left", "left", "left");
    }
}
