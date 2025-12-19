<?php

namespace App\Models;

use CodeIgniter\Model;

class Menu_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = "menu";
        $this->primaryKey = "menu.id_menu";
        $this->fields = array(
            "menu.menu_name",
            "parent.menu_name AS parent_name",
            "menu.url",
            "menu.sort",
            "menu.status",
            "status.nama AS status_name",
            "menu.type",
            "menu.icon",
            "menu.modified_date",
            "menu.parent",
            "menu.created_date",
            "menu.created_user",
            "menu.modified_date",
            "menu.modified_user",
            "user1.name AS created",
            "user2.name AS modified",
            );
        $this->orderBy = array("menu.type, menu.sort" => "ASC");
        $this->relations = array(
            "menu AS parent" => "parent.id_menu = menu.parent",
            "combo AS status"=>"status.kode=menu.status and status.flag='status' and status.status<9",
            "user AS user1"=>"user1.id_user=menu.created_user",
            "user AS user2"=>"user2.id_user=menu.modified_user"
        );
        $this->joins = array("left", "left", "left", "left");

    }

}
