<?php

namespace App\Models;

use CodeIgniter\Model;

class Log_activity_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "log_activity";
        $this->primaryKey = "log_activity.id";
        $this->fields = array(
            "log_activity.datetime",
            "log_activity.username",
            "log_activity.action AS aksi",
            "log_activity.modul",
            "log_activity.message",
            "log_activity.id_user",
//            "user1.name AS created",
//            "user2.name AS modified",
            );
//        $this->orderBy = array("log_activity.datetime" => "DESC");
//        $this->relations = array(
//            "user AS user1"=>"user1.id_user=log_activity.created_user",
//            "user AS user2"=>"user2.id_user=log_activity.modified_user"
//        );
//        $this->joins = array(
//            "left",
//            "left",
//        );
        
    }   
}
