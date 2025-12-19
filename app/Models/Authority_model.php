<?php

namespace App\Models;

use CodeIgniter\Model;

class Authority_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "authority";
        $this->primaryKey = "authority.id_auth";
        $this->fields = array(
            "user_group.group_name",
            "menu.menu_name",
            "authority.addable",
            "authority.updateable",
            "authority.deleteable",
            "authority.status",
            "authority.modified_user",            
            "authority.modified_date",            
            "authority.id_group",
            "authority.id_menu",
            "menu.url",
            "menu.parent",
            "menu.id_menu",
            "menu.icon"
            );
        $this->orderBy = array("user_group.group_name" => "ASC","menu.sort"=>"ASC");
        $this->relations = array("user_group" => "user_group.id_group = authority.id_group AND user_group.status = authority.status",
            "menu" => "menu.id_menu = authority.id_menu AND menu.status = authority.status");
        $this->joins = array("left","left","left");
        
    }
    public function getMenu($id_group){
        $kondisi = array("authority.id_group"=>$id_group,"authority.status"=>"1");
        $this->orderBy = array("menu.sort"=>"ASC");
        return $result = $this->model->get(__FUNCTION__, $kondisi);
    }
    
    public function getMenuList($id_group){
        $sql = "SELECT m.id_menu, m.parent, m.type, m.menu_name, a.id_auth, a.addable, a.updateable, a.deleteable  
                FROM menu m
                LEFT JOIN authority a on a.id_menu = m.id_menu and a.status=1 and a.id_group='".$id_group."'
                WHERE m.status = 1
                ORDER BY m.type, m.sort";
        $query = $this->db->query($sql);
        return $query->getResult('array');
    }
    
}
