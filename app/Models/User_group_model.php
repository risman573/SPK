<?php

namespace App\Models;

use CodeIgniter\Model;

class User_group_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "user_group";
        $this->primaryKey = "user_group.id_group";
        $this->fields = array(
            "user_group.group_name",
            "user_group.status",
            "status.nama AS status_name",
            "user_group.created_date",
            "user_group.created_user",
            "user_group.modified_date",
            "user_group.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
            );
        $this->orderBy = array("user_group.group_name" => "ASC");
        $this->relations = array(
            "combo AS status"=>"status.kode=user_group.status and status.flag='status' and status.status<9",
            "user AS user1"=>"user1.id_user=user_group.created_user",
            "user AS user2"=>"user2.id_user=user_group.modified_user"
        );
        $this->joins = array(
            "left",
            "left",
            "left",
        );

    }

    function getGrup(){
        $unit = '';
        $sql="select * from user_group where id_group<>'f6f1a7c7-4868-4383-9ec5-35c9603d' and status<9 ORDER BY group_name ASC";
        $query = $this->db->query($sql);
        return $query->getResult('array');
    }
}
