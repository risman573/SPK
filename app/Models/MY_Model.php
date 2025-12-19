<?php

namespace App\Models;
use CodeIgniter\Model;


class MY_Model extends Model {

    // public $db;
    public $builder;
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
        // $db = db_connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
    }

    public function get($find = NULL, $condition = array(), $limit = NULL, $offset = NULL) {
        $select = $this->primaryKey;
        $this->defaultField != "" ? $select .= ", " . $this->defaultField : $select .= "";
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $select .= ", " . $field;
            }
        }
        $this->builder = $this->db->table($this->table);
        $this->builder->select($select);
        if (!empty($this->relations)) {
            $i=0;
            foreach ($this->relations as $key => $value) {
                $this->builder->join($key, $value, $this->joins[$i]);
                $i++;
            }
        }
        $this->builder->where($this->primaryKey . " is not null");
        $this->builder->where($this->table.".status < 9");
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->builder->where($key, $value);
            }
        }
                
        if ($find) {
            if ($this->defaultField) {
                $this->builder->like($this->defaultField, $find);
            }
            if (!empty($this->fields)) {
                foreach ($this->fields as $field) {
                    if(($pos = strpos($field, " AS ")) !== FALSE) {
                        $field = substr($field, 0, $pos); 
                    }
                    if (stristr(trim($field), ' ') === FALSE) {
                        $this->builder->orLike($field, $find);
                    } else {
                        $this->builder->orLike(stristr($field, ' ', TRUE), $find);
                    }
                }
            }
        }
        if (!empty($this->orderBy)) {
            foreach ($this->orderBy as $key => $value) {
                $this->builder->orderBy($key, $value);
            }
        }
        if ($limit) {
            // $query = $this->db->table($this->table, $limit, $offset);
            $this->builder->limit($limit, $offset);
        } else {
            // $query = $this->db->table($this->table);
            $this->builder->limit($limit);
        }
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $value) {
                $this->builder->groupByy($value);
            }
        }
        if (!empty($this->where)) {
            foreach ($this->where as $key => $value) {
                $this->builder->where($key, $value);
            }
        }
        // dd($this->builder->get()->getResult());
        return $this->builder->get()->getResult('array');
    }

    public function get_api($find = NULL, $condition = array(), $limit = NULL, $offset = NULL, $sort = array()) {
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
        // dd($select);
        $this->builder = $this->db->table($this->table);
        $this->builder->select($select);
        if (!empty($this->relations)) {
            $i=0;
            foreach ($this->relations as $key => $value) {
                $this->builder->join($key, $value, $this->joins[$i]);
                $i++;
            }
        }
        
        if(!empty($sort)) {
            foreach ($sort as $key => $value) {
                $this->builder->orderBy($key, $value);
            }
        } else {
            if (!empty($this->orderBy)) {
                foreach ($this->orderBy as $key => $value) {
                    $this->builder->orderBy($key, $value);
                }
            }
        }
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $value) {
                $this->builder->groupBy($value);
            }
        }
        
        $this->builder->where($this->primaryKey . " is not null");
        if($this->table!='log_activity'){
            $this->builder->where($this->table.".status < 9");
        }
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $this->builder->where($key, $value);
            }
        }
        // dd($_POST);

        if (!empty($this->where)) {
            foreach ($this->where as $key => $value) {
                $this->builder->where($key, $value);
            }
        }
        
        $i = 0;
        $like='';
        foreach ($this->fields as $item) {            
            if(($pos = strpos($item, " AS ")) !== FALSE) {
                $item = substr($item, 0, $pos); 
            }
            if($this->request->getPost("search")){
                if ($_POST['search']['value']) {
                    //($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
                    $like .= ($i === 0) ? $item." LIKE '%".$_POST['search']['value']."%' ESCAPE '!' " : " OR ".$item." LIKE '%".$_POST['search']['value']."%' ESCAPE '!' ";
                }
            }
            $column[$i] = $item;
            $i++;
        }
        
        if($like != ''){
            $this->builder->where("(".$like.")");
        }
        
        // $this->builder->from($this->table);

        if (isset($_POST['order'])) {
            $orderFieldsView = $this->fields;
            if($orderFieldsView[$_POST['order']['0']['column']] == "action") {
                if (!empty($this->orderBy)) {
                    foreach ($this->orderBy as $key => $value) {
                        $this->builder->orderBy($key, $value);
                    }
                }
            } else {
                if(($pos = strpos($orderFieldsView[$_POST['order']['0']['column']], " AS ")) !== FALSE) {
                    $ord = substr($orderFieldsView[$_POST['order']['0']['column']], 0, $pos); 
                }else{
                    $ord =$orderFieldsView[$_POST['order']['0']['column']];
                }                
                $this->builder->orderBy($ord, $_POST['order']['0']['dir']);
            }
        } else {
            if (!empty($this->orderBy)) {
                foreach ($this->orderBy as $key => $value) {
                    $this->builder->orderBy($key, $value);
                }
            }
        }
    }

    function get_datatables($filter = array()) {
        $this->_get_datatables_query($filter);
        if($this->request->getPost("length")) {
            if ($_POST['length'] != -1)
                $this->builder->limit($_POST['length'], $_POST['start']);
        }
        // dd($filter);
        // $query = $this->builder->get();
        // return $query->getResult('array');
        return $this->builder->get()->getResult('array');
    }

    public function get_by_id($id) {
        // echo $this->primaryKey;
        $select = $this->primaryKey;
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $select .= ", " . $field;
            }
        }
        $this->builder = $this->db->table($this->table);
        $this->builder->select($select);
        if (!empty($this->relations)) {
            $i=0;
            foreach ($this->relations as $key => $value) {
                $this->builder->join($key, $value, $this->joins[$i]);
                $i++;
            }
        }
        // $this->builder->from($this->table);
        $this->builder->where($this->primaryKey, $id);
        // dd($this->builder);
        $query = $this->builder->get();

        return $query->getRow();
    }

    function count_filtered($filter = array()) {
        $this->_get_datatables_query($filter);
        // $this->builder = $this->db->table($this->table);
        // $this->builder = $this->_get_datatables_query($filter);
        // $query = $this->db->get();
        return $this->builder->countAllResults();
        // return $query->num_rows();
    }

    public function count_all() {
        // $this->db->from($this->table);
        $this->builder = $this->db->table($this->table);
        return $this->builder->countAllResults();
    }

    public function add($data) {
        $this->builder = $this->db->table($this->table);
        $hasil = $this->builder->insert($data);
        // dd($data);
        return ($hasil > 0);
    }

    public function updates($id, $data) {
        // print_r($id);
        try {
            $this->builder = $this->db->table($this->table);
            $this->builder->where($this->primaryKey, $id);
            $this->builder->update($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

//    public function deletes($id) {
//         $this->builder = $this->db->table($this->table);
//         // print_r($this->primaryKey);
//         // $this->builder->where($this->primaryKey, $id);
//         // $hasil = $this->builder->delete($this->table);
//         $hasil = $this->builder->delete([$this->primaryKey => $id]);
//         return ($hasil > 0);
//    }
    
    public function deletes($id,$data) {
        try {
            $this->builder = $this->db->table($this->table);
            $this->builder->where($this->primaryKey, $id);
            $this->builder->update($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // public function delete_by($condition = array()) {
    //     if (!empty($condition)) {
    //         foreach ($condition as $key => $value) {
    //             $this->db->where($key, $value);
    //         }
    //     }
    //     $this->db->delete($this->table);
    //     return ($this->db->affected_rows() > 0);
    // }

    // public function check($condition, $id = NULL) {
    //     $select = $this->primaryKey;
    //     $this->defaultField != "" ? $select .= ", " . $this->defaultField : $select .= "";
    //     if (!empty($this->fields)) {
    //         foreach ($this->fields as $field) {
    //             $select .= ", " . $field;
    //         }
    //     }
    //     $this->db->select($select);
    //     if (!empty($this->relations)) {
    //         foreach ($this->relations as $key => $value) {
    //             $this->db->join($key, $value);
    //         }
    //     }

    //     if (!empty($condition)) {
    //         foreach ($condition as $key => $value) {
    //             $this->db->where($key, $value);
    //         }
    //     }

    //     if (!empty($id)) {
    //         $this->db->where($this->primaryKey . ' !=', $id);
    //     }

    //     $query = $this->db->get($this->table);
    //     return ($query->num_rows() > 0) ? TRUE : FALSE;
    // }
    
    // public function cek($field, $value) {
    //     $this->db->where($field, $value);
    //     $query = $this->db->get($this->table);
    //     return ($query->num_rows() > 0) ? TRUE : FALSE;
    // }
    
    // public function cek_modified($value) {
    //     $this->db->where($value);
    //     $query = $this->db->get($this->table);
    //     return ($query->num_rows() > 0) ? TRUE : FALSE;
    // }
}
