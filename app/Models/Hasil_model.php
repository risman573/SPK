<?php

namespace App\Models;

use CodeIgniter\Model;

class Hasil_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "hasil";
        $this->primaryKey = "hasil.id_hasil";
        $this->fields = array(
            "hasil.id_hasil",
            "hasil.id_alternatif",
            "alternatif.nama_alternatif",
            "hasil.nilai_preferensi",
            "hasil.ranking",
            "hasil.status",
            "status.nama AS status_name",
            "hasil.created_date",
            "hasil.created_user",
            "hasil.modified_date",
            "hasil.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
        );
        $this->orderBy = array("hasil.ranking" => "ASC");
        $this->relations = array(
            "alternatif" => "alternatif.id_alternatif=hasil.id_alternatif",
            "combo AS status" => "status.kode=hasil.status and status.flag='status' and status.status<9",
            "user AS user1" => "user1.id_user=hasil.created_user",
            "user AS user2" => "user2.id_user=hasil.modified_user"
        );
        $this->joins = array("left", "left", "left", "left");
    }
}
