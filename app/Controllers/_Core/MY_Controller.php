<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    var $base_url;
    var $menu_string;
    var $id_menu;
    var $model;
    var $table;
    var $pkField;
    var $pkFieldValue;
    var $uniqueFields = array();
    var $fields = array();
    var $kode;
    var $bypass = false;
    var $lihat = false;
    var $tambah = false;
    var $ubah = false;
    var $hapus = false;
    
    function __construct() {
        parent::__construct();
        $this->session = \Config\Services::session();

        $this->load->model("defaults_model");
        $this->default = $this->getDefault($this->defaults_model->get());
        
        if(isset($this->default['dateTimeZone'])){
            date_default_timezone_set($this->default['dateTimeZone']);
        }
        
        if (!$this->session->userdata('website_admin_logged_in')) {
            redirect('login');
        }else{
            $this->base_url = base_url();
            $this->keluar = base_url() . 'login/keluar';
            $this->user_id = $this->session->userdata('sess_user_id');
            $this->user_group = $this->session->userdata('user_group');
            $this->user_nama = $this->session->userdata('sess_nama');
            $this->user_menu = $this->session->userdata('sess_menu');
            $this->user_level = $this->session->userdata('sess_level');
            $this->user_cabang = $this->session->userdata('sess_cabang');
            $this->user_companies = $this->session->userdata('sess_companies');
            $this->default_content = base_url() . $this->default['defaultController'];
            $this->user_photo = $this->session->userdata('sess_photo');
            $this->menu_string = $this->getListMenu();
            $this->get_hak_akses();
            
            if($this->router->fetch_method() == 'index' && $this->router->fetch_class() != 'home'){
                if($this->lihat==1){
                    $this->cekLog('Open', '');
                }else{
                    $this->cekLog('Open', 'Not Allow Access');
                }
            }
//            echo $this->lihat;
        }
    }
    
    function getDefault($data){
        $result = array();
        foreach ($data as $dt){
            $result[$dt['key']] = $dt['value'];
        }
        return $result;
    }
    
    public function getListMenu(){
        $this->load->model("authority_model");
        $kondisi = array("authority.id_group"=>$this->session->userdata('user_group'),"authority.status"=>"1");
        $dt = $this->authority_model->get(NULL,$kondisi); 
        $this->menu_string='<ul class="nav flex-column">';
        $listMenu = $this->buildTree($dt);
        $this->createMenu($listMenu);
        $this->menu_string .= '</ul>';
        
        return $this->menu_string;
        
    }   
    
    function createMenu(array &$elements){
        foreach($elements as $e){
            if($e['parent'] == '' && !isset ($e['children'])){
                $this->menu_string .= '<li title="'.$e['menu_name'].'" class="nav-item">
                                            <a href="'.base_url().$e['url'].'" class="nav-link">
                                            <i class="material-icons icon">'.$e['icon'].'</i>
                                            <span class="title">'.$e['menu_name'].'</span>
                                            </a>
                                        </li>';
            }else if(isset($e['children'])){
                if($e['parent']==''){
                    $this->menu_string .= '<li class="nav-item">
                                                <a href="javascript:void(0);" class="nav-link dropdwown-toggle">
                                                    <i class="material-icons icon">'.$e['icon'].'</i>
                                                    <span>'.$e['menu_name'].'</span>
                                                    <i class="material-icons icon arrow">expand_more</i>
                                                </a>
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                ';
                }
                $this->createMenu($e['children']);                
                $this->menu_string .= '</li></ul></li>';
            }else{
                $this->menu_string .='
                                        <a href="'.base_url().$e['url'].'" class="nav-link  pink-gradient-active"><i class="material-icons icon"></i>'.$e['menu_name'].'</a>
                                     ';
            }
        }
    }
    
    public function buildTree(array &$elements, $parentId = '', $depth = 0) {
        if($depth > 1000) return '';
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent'] == $parentId && $element['menu_name']!=NULL) {
                $children = $this->buildTree($elements, $element['id_menu'], $depth+1);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id_menu']] = $element;
                unset($elements[$element['id_menu']]);
                              
            }
        }
        return $branch;
    }
    
    
    
    
    
    
    //---------  LAMA  ----------------------------------------------------------------------------
    
    public function get_hak_akses() {
        $this->load->model("authority_model");
        $kondisi = array("authority.id_group"=>$this->session->userdata('user_group'), "menu.url" => $this->router->directory . $this->router->fetch_class(),"authority.status"=>"1");
        $this->orderBy = array("menu.sort"=>"ASC");
        $lists = $this->authority_model->get(NULL,$kondisi); 
//        print_r($lists);
        if ($lists) {
            $this->lihat = true;
            foreach ($lists as $value) {
                $this->tambah = $value['addable'];
                $this->ubah = $value['updateable'];
                $this->hapus = $value['deleteable'];
            }
        } else {
            $this->lihat = false;
            $this->tambah = false;
            $this->ubah = false;
            $this->hapus = false;
        }
    }
    
    public function ajax_list() {
        $filter = array();
        if($this->input->post("filter")) {
            foreach ($this->input->post("filter") as $key => $value) {
                if (strpos($key, "0")) {
                    $key = str_replace('0', '.', $key);
                    $filter[$key] = $value;
                } else {
                    $filter[$key] = $value;
                }
            }
        }
        foreach($this->uniqueFields as $k => $val){
            if($this->input->post($val) || $this->input->post($val) !=''){
                $filter[$this->table .".". $val] = $this->input->post($val);
            }
        }
        if($this->input->get("key") && $this->input->get("val") && $this->session->userdata("hak_akses")!=0) {
            if($this->session->userdata("hak_akses")==9){
                $filter[$this->table .".". $this->input->get("key")]=$this->input->get("val");
            }elseif($this->session->userdata("hak_akses")==2){
                $param = $this->session->userdata("child");
                array_push($param,$this->input->get("val"));
                $filter[$this->table .".". $this->input->get("key")]=$param;
            }else{
                $filter[$this->table .".". $this->input->get("key")]=$this->input->get("val");
            }
        }else{
            $filter[$this->table .".". $this->input->get("key")]=$this->input->get("val");
        }

//            if($this->input->get("key") && $this->input->get("val")) {
//                    $filter[$this->table .".". $this->input->get("key")]=$this->input->get("val");
//            }


        $lists = $this->model->get_datatables($filter);
        $data = array();
        $no = !$this->input->post("start") ? 0 : $this->input->post("start"); //$_POST['start'];
        foreach ($lists as $list) {
            $no++;
            $row = array();
//                $this->approve = $list['approve'];                

            foreach ($this->fields as $key => $value) {
                if ($key == "action") {
                    $row[] = "<div align='center' style='margin-bottom:-10px; margin-top:-10px;'>".$this->action($list[$this->pkField])."</div>";
                } elseif ($key == "view") {
                    $row[] = "<div align='center' style='margin-bottom:-10px; margin-top:-10px;'>".$this->view($list[$this->pkField])."</div>";
                } elseif ($key == "delete") {
                    $row[] = $this->action_delete($list[$this->pkField]);
                }elseif ("TIPE" == array_search("ICON", $value)) {
                    $row[] = '<i class="material-icons">' . $list[$key] . '</i>';
                }elseif ("TIPE" == array_search("DATETIME", $value)){
                    if($list[$key] == '0000-00-00 00:00:00'){
                        $row[] = '';
                    }else{
                        $row[]= date($this->default['dateTimeFront'], strtotime($list[$key]));
                    }
                } elseif ("TIPE" == array_search("DATE", $value)) {
                    if($list[$key] == '0000-00-00'){
                        $row[] = '';
                    }else{
                        $row[]= date($this->default['dateFront'], strtotime($list[$key]));
                    }
                } elseif ("TIPE" == array_search("DAY", $value)) {
                    $row[] = "<div align='left'>".indonesian_date($list[$key])."</div>"; 
                } elseif ("TIPE" == array_search("STATUS", $value)) {
                    if ($list[$key]=="1") {
                        $row[] = "<div align='center'><i class='material-icons'>done</i></div>";
                    } else {
                        $row[] = "<div align='center'><i class='material-icons'>close</i></div>";
                    }
                }elseif ("TIPE" == array_search("STRING", $value)) {
                    if(strlen($list[$key])>50){
                        $row[] = substr($list[$key],0,50) . ' ....';
                    }else{
                        $row[] = $list[$key];
                    }
                } elseif ("TIPE" == array_search("RUPIAH", $value)) {
                    $row[] = "<div align='right'>".format_rupiah($list[$key])." &nbsp;&nbsp;&nbsp;</div>";
                } elseif ("TIPE" == array_search("FLOAT", $value)) {
                    $row[] = "<div align='right'>".number_format($list[$key], 0, '.', ',')." &nbsp;&nbsp;&nbsp;</div>";
                } else {
                    $row[] = $list[$key];
                }
            }
            $data[] = $row;
        }

        $draw = !$this->input->post("draw") ? 1 : $this->input->post("draw");
        $output = array(
            "draw" => $draw, //$_POST['draw'],
//                "recordsTotal" => $this->model->count_all(),
            "recordsTotal" => $this->model->count_filtered($filter),
            "recordsFiltered" => $this->model->count_filtered($filter),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function action($id, $fname = '', $approve = null) {
        $tombol = '';
        if($this->ubah == '1'){
            $tombol .= '<a href="javascript:void(0);" title="Edit" onclick="edit(' . "'" . $id . "'" . ')" class="mb-2 btn btn-outline-primary">Edit <i class="material-icons">'.$this->default['iconEdit'].'</i></a> ';
        }

        if($this->hapus == '1'){
            $tombol .= '<a href="javascript:void(0);" title="Hapus" onclick="hapus(' . "'" . $id . "'" . ')" class="mb-2 btn btn-outline-danger"><i class="material-icons">'.$this->default['iconDelete'].'</i> Delete</a> ';
        }
        return $tombol;
    }

    public function add() {
        if (!$this->tambah) {
            $result = array("msg" => "Do not have access.");
        } else {
            $result = array();
            $data = $this->dataInput();          
            if ($data["valid"]) {
                $data["data"]["created_date"] = date($this->default['dateTimeDB']);
                $data["data"]["created_user"] = $this->session->userdata('sess_user_id');
                $insert = $this->model->add($data["data"]);
                if ($insert) {
                    if($this->kode) {
                        $result = array("success" => TRUE, "id" => $this->pkFieldValue, "kode" => $this->kode);
                    } else {
                        $result = array("success" => TRUE, "id" => $this->pkFieldValue);                        
                    }
                    $this->cekLog('Add', json_encode($data["data"]));
                } else {
                    $result = array("msg" => "Gagal tersimpan.", "success" => FALSE);
                }
            } else {
                $result = array("msg" => $data["error"], "success" => FALSE);
            }
        }
        echo json_encode($result);
    }

    public function edit($id) {
        if ($id) {
            $data = $this->model->get_by_id($id);
            echo json_encode($data);
        }
    }
    
    public function update() {
//        $this->get_hak_akses($this->kode_transaksi);
//        if (!$this->ubah && !$this->bypass) {
//            $result = array("msg" => "Do not have access.");
//        } else {
            $result = array();
            $data = $this->dataInput();
            if ($data["valid"]) {
                $data["data"]["modified_date"] = date($this->default['dateTimeDB']);
                $data["data"]["modified_user"] = $this->session->userdata('sess_user_id');
                $update = $this->model->update($this->input->post($this->pkField), $data["data"]);
                if ($update) {
                    if($this->kode) {
                        $result = array("success" => TRUE, "id" => $this->pkFieldValue, "kode" => $this->kode);
                    } else {
                        $result = array("success" => TRUE, "id" => $this->pkFieldValue);
                    }
                    $this->cekLog('Update', json_encode($data["data"]));
                } else {
                    $result = array("msg" => "Gagal diupdate.", "success" => FALSE);
                }
            } else {
                $result = array("msg" => $data["error"], "success" => FALSE);
            }
//        }
        echo json_encode($result);
    }

    public function delete($id) {
        if (!$this->hapus) {
            $result = array("msg" => "Do not have access.");
        } else {
            if ($id) {
                $data = array(
                        "status"=>9,
                        "modified_user"=>$this->session->userdata('sess_user_id'),
                        "modified_date"=>date('Y-m-d H:m:s')
                        );
                $delete = $this->model->deletes($id,$data);
                if ($delete) {
                    $result = array("success" => TRUE);
                    $this->cekLog('Delete', $id);
                } else {
                    $result = array("msg" => "Gagal menghapus data.");
                }
            }
        }
        echo json_encode($result);
    }

    public function get() {
        $cari = NULL;
        $kondisi = array();
        if ($this->input->post()) {
            foreach ($this->input->post() as $key => $value) {
                if ($key == "cari" || $key == "q") {
                    $cari = $value;
                } elseif (strpos($key, ".")) {
                    $kondisi[$key] = $value;
                } else {
                    $kondisi[$this->table . "." . $key] = $value;
                }
            }
        }
        $result = $this->model->get($cari, $kondisi);
        echo json_encode($result);
    }

    public function cekLog($action, $data) {
        if($this->default['logRecord'] == 1 || $this->default['logRecord'] == 3){
            $this->logFile($action, $data);
        }
        if($this->default['logRecord'] == 2 || $this->default['logRecord'] == 3){
            $this->logDatabase($action, $data);
        }
    }

    public function logFile($action, $data) {
        // DATE TIME - ACTION - MODUL - MESSAGE
        // ACTION = Open, Add, Edit, Delete
        $path = 'files/logs/';
        $filename = $path . $this->user_id . ".txt";
        $message = date($this->default['dateTimeDB']) . '; ' . $action . ' ; ' . $this->router->fetch_class() . '; ' . $data . '; ';
        
        if(file_exists($filename) && filesize($filename) >= $this->default['LogMaxSize']){ // 100000000 = 100 Mb
            copy($filename, $path . $this->user_id . "-" . date('YmdHis') . ".txt");
            $fp = fopen($filename, "w");
            fwrite($fp,'DATE TIME; ACTION; MODUL; MESSAGE; ');
            file_put_contents($filename, "\n" . $message, FILE_APPEND);
        }elseif(file_exists($filename)){
            file_put_contents($filename, "\n" . $message, FILE_APPEND);
        }else{
            $fp = fopen($filename, "w");
            fwrite($fp,'DATE TIME; ACTION; MODUL; MESSAGE; ');
            file_put_contents($filename, "\n" . $message, FILE_APPEND);
        }
    }

    public function logDatabase($action, $data=null) {
        $input["id"]         = $this->uuid->v4();
        $input["id_user"]    = $this->user_id;
        $input["username"]   = $this->user_nama;
        $input["datetime"]   = date($this->default['dateTimeDB']);
        $input["action"]     = $action;
        $input["modul"]      = $this->router->fetch_class();
        $input["message"]    = $data;
        $this->load->model("Log_activity_model");
        $this->Log_activity_model->add($input);
        
    }

}
