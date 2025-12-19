<?php

namespace App\Models;

class User_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "user";
        $this->primaryKey = "user.id_user";
        $this->fields = array(
            "user.name",
            "user.username",
            "user_group.group_name",
            "user.level",
            "user.status",
            "user.password",
            "user.alamat",
            "user.telp",
            "user.id_group",
            "user.parent",
            "user.menu",
            "user.photo",
            "status.nama AS status_name",
            "user.created_date",
            "user.created_user",
            "user.modified_date",
            "user.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
            );
        $this->orderBy = array("user.level" => "ASC");
        $this->relations = array(
            "combo AS status"=>"status.kode=user.status and status.flag='status' and status.status<9",
            "user_group"=>"user_group.id_group=user.id_group AND user_group.status=1",
            "user AS user1"=>"user1.id_user=user.created_user",
            "user AS user2"=>"user2.id_user=user.modified_user"
            );
        $this->joins = array(
            "left",
            "left",
            "left",
            "left"
            );
    }

    public function get_child($id){
        $builder = $this->db->table($this->table);
        $builder->where("parent",$id);
        return $builder->get()->getResult();
    }

    function getTotalUser(){
        $sql = 'SELECT COUNT(id_user) AS jumlah FROM user WHERE status<9';
        $query = $this->db->query($sql);
        $result =$query->row();
        return $result;
    }

}
