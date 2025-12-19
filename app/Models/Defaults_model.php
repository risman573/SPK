<?php

namespace App\Models;

use CodeIgniter\Model;

class Defaults_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "default";
        $this->primaryKey = "default.id";
        $this->fields = array(
            "default.key",
            "default.value",
            "default.description",
            "default.status",
            "default.created_date",
            "default.created_user",
            "default.modified_date",
            "default.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
            );
        $this->orderBy = array("default.status" => "DESC", "default.key" => "ASC");
        $this->relations = array(
            "user AS user1"=>"user1.id_user=default.created_user",
            "user AS user2"=>"user2.id_user=default.modified_user"
        );
        $this->joins = array(
            "left",
            "left",
        );
        
    }   
}
