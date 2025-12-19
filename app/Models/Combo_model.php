<?php

namespace App\Models;

use CodeIgniter\Model;

class Combo_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "combo";
        $this->primaryKey = "combo.id";
        $this->fields = array(
            "combo.kode",
            "combo.nama",
            "combo.type",
            "combo.flag",
            "combo.sort",
            "status.nama AS status_name",
            "combo.status",
            "combo.created_date",
            "combo.created_user",
            "combo.modified_date",
            "combo.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
            );
        $this->orderBy = array("combo.flag" => "ASC", "combo.sort" => "ASC");
        $this->relations = array(
            "combo AS status"=>"status.kode=combo.status and status.flag='status' and status.status<9",
            "user AS user1"=>"user1.id_user=combo.created_user",
            "user AS user2"=>"user2.id_user=combo.modified_user"
        );
        $this->joins = array(
            "left",
            "left",
            "left",
        );
    }

    function getCombo($type, $flag){
        $sql = "select * from combo where type='". $type ."' and flag='". $flag ."' and status<9 ORDER BY sort ASC";
        $query = $this->db->query($sql);
        return $query->getResult('array');
    }

}
