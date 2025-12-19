<?php

namespace App\Models;

class User_aktif_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "user_aktif";
        $this->primaryKey = "user_aktif.id_user";
        $this->fields = array(
            "user_aktif.waktu",
            "user_aktif.browser",
            "user_aktif.address",
            "user_aktif.status",
            );
        $this->orderBy = array("user_aktif.waktu" => "DESC");
         
    }
    
    function getAktif(){
        $sql = "SELECT aktif.*, 
                    user.username AS username, user.name AS nama_user, user.photo AS photo
                FROM user_aktif AS aktif
                    LEFT JOIN user AS user ON user.id_user = aktif.id_user
                WHERE aktif.status=0
                ORDER BY aktif.id_user ASC
                ";
//        $query = $this->db->query($sql);
        $query =$sql->row();
        return $query->result_array();
        
    }
}
