<?php

class MY_Model extends CI_Model {

    public $table;
    public $primaryKey;
    public $defaultField;
    public $orderBy = array();
    public $groupBy = array();
    public $fields = array();
    public $fields_api = array();
    public $joins = array();
    public $where = array();
    public $relations = array();

    function __construct() {
        parent::__construct();
    }

    public function get($find = NULL, $condition = array(), $limit = NULL, $offset = NULL) {
        $select = $this->primaryKey;
        $this->defaultField != "" ? $select .= ", " . $this->defaultField : $select .= "";
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $select .= ", " . $field;
            }
        }
        $this->db->select($select);
        if (!empty($this->relations)) {
            $i=0;
            foreach ($this->relations as $key => $value) {
                $this->db->join($key, $value, $this->joins[$i]);
                $i++;
            }
        }
        $this->db->where($this->primaryKey . " is not null");
        $this->db->where($this->table.".status < 9");
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
        }
                
        if ($find) {
            if ($this->defaultField) {
                $this->db->like($this->defaultField, $find);
            }
            if (!empty($this->fields)) {
                foreach ($this->fields as $field) {
                    if(($pos = strpos($field, " AS ")) !== FALSE) {
                        $field = substr($field, 0, $pos); 
                    }
                    if (stristr(trim($field), ' ') === FALSE) {
                        $this->db->or_like($field, $find);
                    } else {
                        $this->db->or_like(stristr($field, ' ', TRUE), $find);
                    }
                }
            }
        }
        if (!empty($this->orderBy)) {
            foreach ($this->orderBy as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit) {
            $query = $this->db->get($this->table, $limit, $offset);
        } else {
            $query = $this->db->get($this->table);
        }
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $value) {
                $this->db->group_by($value);
            }
        }
        if (!empty($this->where)) {
            foreach ($this->where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
//        print_r($this->where);
//        echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_api($find = NULL, $condition = array(), $limit = NULL, $offset = NULL, $sort = array()) {
//        $this->db->_protect_identifiers = false;
        $select = $this->primaryKey;
        $this->defaultField != "" ? $select .= ", " . $this->defaultField : $select .= "";
        if (!empty($this->fields_api)) {
            foreach ($this->fields_api as $field) {
                $select .= ", " . $field;
            }
        }
        $this->db->select($select);
        if (!empty($this->relations)) {
            foreach ($this->relations as $key => $value) {
                $this->db->join($key, $value);
            }
        } 
        
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $value) {
                $this->db->group_by($value);
            }
        }
        
        $this->db->where($this->primaryKey . " is not null");
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        if ($find) {
            if ($this->defaultField) {
                $this->db->like($this->defaultField, $find);
            }
            if (!empty($this->fields)) {
                foreach ($this->fields as $field) {
                    if(($pos = strpos($field, " AS ")) !== FALSE) {
                        $field = substr($field, 0, $pos); 
                    }
                    if (stristr(trim($field), ' ') === FALSE) {
                        $this->db->or_like($field, $find);
                    } else {
                        $this->db->or_like(stristr($field, ' ', TRUE), $find);
                    }
                }
            }
        }
        if(!empty($sort)) {
            foreach ($sort as $key => $value) {
                $this->db->order_by($key, $value);
            }
        } else {
            if (!empty($this->orderBy)) {
                foreach ($this->orderBy as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            }
        }
        if ($limit) {
            $query = $this->db->get($this->table, $limit, $offset);
        } else {
            $query = $this->db->get($this->table);
        }
        return $query->result();
    }

    private function _get_datatables_query($condition = array()) {
        $select = $this->primaryKey;
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $select .= ", " . $field;
            }
        }
        $this->db->select($select);
        if (!empty($this->relations)) {
            $i=0;
            foreach ($this->relations as $key => $value) {
                $this->db->join($key, $value, $this->joins[$i]);
                $i++;
            }
        }
        
        if(!empty($sort)) {
            foreach ($sort as $key => $value) {
                $this->db->order_by($key, $value);
            }
        } else {
            if (!empty($this->orderBy)) {
                foreach ($this->orderBy as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            }
        }
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $value) {
                $this->db->group_by($value);
            }
        }
        
        $this->db->where($this->primaryKey . " is not null");
//        echo $this->table;
        if($this->table!='log_activity'){
            $this->db->where($this->table.".status < 9");
        }
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        
        if($this->input->post("contra")){
            foreach ($this->input->post("contra")as $key => $value){
                $this->db->where_not_in($key,$value);
            }
        }

        if (!empty($this->where)) {
            foreach ($this->where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        
        $i = 0;
        $like='';
        foreach ($this->fields as $item) {            
            if(($pos = strpos($item, " AS ")) !== FALSE) {
                $item = substr($item, 0, $pos); 
            }
            if($this->input->post("search")){
                if ($_POST['search']['value']) {
                    //($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
                    $like .= ($i === 0) ? $item." LIKE '%".$_POST['search']['value']."%' ESCAPE '!' " : " OR ".$item." LIKE '%".$_POST['search']['value']."%' ESCAPE '!' ";
                }
            }
            $column[$i] = $item;
            $i++;
        }
        
        if($like != ''){
            $this->db->where("(".$like.")");
        }
        
        $this->db->from($this->table);

        if (isset($_POST['order'])) {
            $orderFieldsView = $this->fields;
            if($orderFieldsView[$_POST['order']['0']['column']] == "action") {
                if (!empty($this->orderBy)) {
                    foreach ($this->orderBy as $key => $value) {
                        $this->db->order_by($key, $value);
                    }
                }
            } else {
                if(($pos = strpos($orderFieldsView[$_POST['order']['0']['column']], " AS ")) !== FALSE) {
                    $ord = substr($orderFieldsView[$_POST['order']['0']['column']], 0, $pos); 
                }else{
                    $ord =$orderFieldsView[$_POST['order']['0']['column']];
                }                
                $this->db->order_by($ord, $_POST['order']['0']['dir']);
            }
        } else {
            if (!empty($this->orderBy)) {
                foreach ($this->orderBy as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            }
        }
    }

    function get_datatables($filter = array()) {
        $this->_get_datatables_query($filter);
        if($this->input->post("length")) {
            if ($_POST['length'] != -1)
                $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
//        return $query->result();
//        echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_by_id($id) {
        $select = $this->primaryKey;
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $select .= ", " . $field;
            }
        }
        $this->db->select($select);
        if (!empty($this->relations)) {
            $i=0;
            foreach ($this->relations as $key => $value) {
                $this->db->join($key, $value, $this->joins[$i]);
                $i++;
            }
        }
        $this->db->from($this->table);
        $this->db->where($this->primaryKey, $id);
        $query = $this->db->get();

        return $query->row();
    }

    function count_filtered($filter = array()) {
        $this->_get_datatables_query($filter);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function add($data) {
        $this->db->insert($this->table, $data);
        return ($this->db->affected_rows() > 0);
    }

    public function update($id, $data) {
        try {
            $this->db->where($this->primaryKey, $id);
            $this->db->update($this->table, $data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

//    public function delete($id) {
//        $this->db->where($this->primaryKey, $id);
//        $this->db->delete($this->table);
//        return ($this->db->affected_rows() > 0);
//    }
    
    public function delete($id,$data) {
        $this->db->where($this->primaryKey, $id);
        $this->db->update($this->table, $data);
        return ($this->db->affected_rows() > 0);
    }

    public function delete_by($condition = array()) {
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->delete($this->table);
        return ($this->db->affected_rows() > 0);
    }

    public function check($condition, $id = NULL) {
        $select = $this->primaryKey;
        $this->defaultField != "" ? $select .= ", " . $this->defaultField : $select .= "";
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $select .= ", " . $field;
            }
        }
        $this->db->select($select);
        if (!empty($this->relations)) {
            foreach ($this->relations as $key => $value) {
                $this->db->join($key, $value);
            }
        }

        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (!empty($id)) {
            $this->db->where($this->primaryKey . ' !=', $id);
        }

        $query = $this->db->get($this->table);
//        return $query->result();
        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }
    
    public function cek($field, $value) {
        $this->db->where($field, $value);
        $query = $this->db->get($this->table);
        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }
    
    public function cek_modified($value) {
        $this->db->where($value);
        $query = $this->db->get($this->table);
        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }
}
