<?php

namespace App\Controllers;
// Set header encoding
header('Content-Type: text/html; charset=utf-8');

use CodeIgniter\Controller;
use App\Models\Defaults_model;
use App\Models\Defaults_iowork_model;
use App\Models\Authority_model;
// use App\Models\AuthModel;
use App\Models\Log_activity_model;
use App\Libraries\Uuid;
Use CodeIgniter\HTTP\Request;
// $session = \Config\Services::session();
// $session = session();

abstract class MY_Controller extends BaseController {

    // public $testing;
    var $default;
    var $base_url;
    var $website_admin_logged_in;
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

    protected $helpers = ['form_validation', 'url', 'General_helper', 'Excel_helper', 'Email_helper', 'Telegram_helper'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        parent::initController($request, $response, $logger);
    }

    public function __construct() {
        // parent::__construct();

        helper('form');
        $this->form_validation = \Config\Services::validation();

        $this->Authority_model = new Authority_model();
        $this->Defaults_model = new Defaults_model();
        $this->Log_activity_model = new Log_activity_model();

        $this->router = \Config\Services::router();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->router = \Config\Services::router();
        $this->uri = new \CodeIgniter\HTTP\URI();
        // $this->uri = service('uri');
        $this->uuid = new Uuid();
        $this->default = $this->getDefault($this->Defaults_model->get());

        $lokasi = str_replace("\App\Controllers\\","", $this->router->controllerName());;
        $this->current = strtolower(str_replace("\\","/", $lokasi));

        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }else{
            $this->get_hak_akses();
            $this->data = array(
                'base_url'       => base_url(),
                'keluar'         => base_url() . 'login/keluar',
                'user_id'        => $this->session->get('sess_user_id'),
                'user_group'     => $this->session->get('user_group'),
                'user_nama'      => $this->session->get('sess_nama'),
                'user_menu'      => $this->session->get('sess_menu'),
                'user_level'     => $this->session->get('sess_level'),
                'default_content'=> base_url() . $this->default['defaultController'],
                'user_photo'     => $this->session->get('sess_photo'),
                'menu_string'    => $this->getListMenu(),
                // 'hak_akses'      => $this->get_hak_akses(),
                'default'        => $this->default,

                'tambah'        => $this->tambah,
                'ubah'          => $this->ubah,
                'hapus'         => $this->hapus,
            );
            // dd($this->base_url);

            // if(uri_string() == 'index' && uri_string() != 'home'){
            if($this->router->methodName() == 'index' && $this->current != 'home'){
                if($this->lihat==1){
                    $this->cekLog('Open', '');
                }else{
                    $this->cekLog('Open', 'Not Allow Access');
                }
            }
        }
    }

    function getDefault($data){
        $result = array();
        foreach ($data as $dt){
            $result[$dt['key']] = $dt['value'];
        }
        return $result;
    }


    // ---------- CREAT MENU ----------------------------
    public function getListMenu(){
        $kondisi = array("authority.id_group"=>$this->session->get('user_group'),"authority.status"=>"1");
        $dt = $this->Authority_model->get(NULL,$kondisi);
        $this->menu_string='<ul class="nav flex-column">';
        $listMenu = $this->buildTree($dt);
        $this->createMenu($listMenu);
        $this->menu_string .= '</ul>';
        return $this->menu_string;

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
    function createMenu(array &$elements){
        foreach($elements as $e){
            if($e['parent'] == '' && !isset ($e['children'])){
                $this->menu_string .= '<li title="'.$e['menu_name'].'" class="nav-item">
                                            <a href="'.base_url().$e['url'].'" class="nav-link">
                                            <i class="material-icons icon">'.$e['icon'].'</i>
                                            <span class="title">'.$e['menu_name'].'</span>
                                            </a>
                                        </li>';
                }else if(isset($e['children']) && $e['parent'] == ''){
                    if($e['parent']==''){
                        $this->menu_string .= '
                                                    <li class="nav-item mt-4">
                                                        <span class="menu-text"><b>'.$e['menu_name'].'</b></span>
                                                    </li>
                                                    <ul class="nav flex-column">
                                                    ';
                    }
                    $this->createMenu($e['children']);
                    $this->menu_string .= '</ul></li>';
                }else if(isset($e['children']) && $e['parent'] != ''){
                        $this->menu_string .= '<li title="'.$e['menu_name'].'" class="nav-item">
                                                <a href="javascript:void(0);" class="nav-link dropdwown-toggle">
                                                    <i class="material-icons icon">'.$e['icon'].'</i>
                                                    <span class="title">'.$e['menu_name'].'</span>
                                                    <i class="material-icons arrow">expand_more</i>
                                                </a>
                                                <ul class="nav flex-column">
                                                    ';
                        $this->createMenu($e['children']);
                        $this->menu_string .= '</ul></li>';

            }else{
                if($e['icon']!=''){
                    $this->menu_string .='
                    <li class="nav-item"><a href="'.base_url().$e['url'].'" class="nav-link  pink-gradient-active"><i class="material-icons icon">'.$e['icon'].'</i><span class="title">'.$e['menu_name'].'</span></a></li>
                                         ';
                }else{
                    $this->menu_string .='
                    <li class="nav-item"><a href="'.base_url().$e['url'].'" class="nav-link pink-gradient-active"><i class="material-icons icon"></i><span class="title">'.$e['menu_name'].'</span></a></li>
                                         ';
                }
            }
        }
    }


    // ---------- HAK AKSES ----------------------------
    public function get_hak_akses() {
        $kondisi = array("authority.id_group"=>$this->session->get('user_group'), "menu.url" => $this->current,"authority.status"=>"1");
        $this->orderBy = array("menu.sort"=>"ASC");
        $lists = $this->Authority_model->get(NULL,$kondisi);
        if ($lists) {
            $this->lihat = true;
            foreach ($lists as $value) {
                $this->tambah = $value['addable'];
                $this->ubah = $value['updateable'];
                $this->hapus = $value['deleteable'];
            }
        } else {
            if($this->current == 'log_notif_pekerja'){
                $this->lihat = true;
                $this->tambah = true;
                $this->ubah = true;
                $this->hapus = true;
            }else{
                $this->lihat = false;
                $this->tambah = false;
                $this->ubah = false;
                $this->hapus = false;
            }
        }
    }


    public function ajax_list() {
        // $this->get_hak_akses();
        $filter = array();
        if($this->request->getPost("filter")) {
            foreach ($this->request->getPost("filter") as $key => $value) {
                if (strpos($key, "0")) {
                    $key = str_replace('0', '.', $key);
                    $filter[$key] = $value;
                } else {
                    $filter[$key] = $value;
                }
            }
        }
        foreach($this->uniqueFields as $k => $val){
            if($this->request->getPost($val) || $this->request->getPost($val) !=''){
                $filter[$this->table .".". $val] = $this->request->getPost($val);
            }
        }



        $lists = $this->model->get_datatables($filter);
        // dd($lists);
        $data = array();
        $no = !$this->request->getPost("start") ? 0 : $this->request->getPost("start"); //$_POST['start'];
        foreach ($lists as $list) {
            $no++;
            $row = array();

            foreach ($this->fields as $key => $value) {
                if ($key == "action") {
                    $row[] = "<div align='center' style='margin-bottom:-10px;'>".$this->action($list[$this->pkField])."</div>";
                } elseif ($key == "view") {
                    $row[] = "<div align='center' style='margin-bottom:-10px;'>".$this->view($list[$this->pkField])."</div>";
                } elseif ($key == "delete") {
                    $row[] = $this->action_delete($list[$this->pkField]);
                }elseif ("TIPE" == array_search("ICON", $value)) {
                    $row[] = '<i class="material-icons">' . $list[$key] . '</i>';
                }elseif ("TIPE" == array_search("DATETIME", $value)){
                    if($list[$key] == '0000-00-00 00:00:00' || $list[$key] == ''){
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
                } elseif ("TIPE" == array_search("CHECK", $value)) {
                    if ($list[$key]=="1") {
                        $row[] = "<div align='center'><i class='material-icons'>checked</i></div>";
                    } else {
                        $row[] = "";
                    }
                }elseif ("TIPE" == array_search("STRING", $value)) {
                    // print_r($key . ' : ' .$list[$key] . '<br>');
                    if (strlen($list[$key]) > 50) {
                        $short_text = mb_substr($list[$key], 0, 50, 'UTF-8');
                        $row[] = $short_text . ' ....';
                    } else {
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

        $draw = !$this->request->getPost("draw") ? 1 : $this->request->getPost("draw");
        $output = array(
            "draw" => $draw, //$_POST['draw'],
            //"recordsTotal" => $this->model->count_all(),
            "recordsTotal" => $this->model->count_all($filter),
            "recordsFiltered" => $this->model->count_filtered($filter),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function action($id, $fname = '', $approve = null) {
        $tombol = '' ;
        // dd($this->ubah );
        if($this->ubah == '1'){
            $tombol .= '<a href="javascript:void(0);" title="Edit" onclick="edit(' . "'" . $id . "'" . ')" class="mb-2 btn-sm btn btn-outline-primary"><i class="material-icons">'.$this->default['iconEdit'].'</i></a> ';
        }

        if($this->hapus == '1'){
            $tombol .= '<a href="javascript:void(0);" title="Hapus" onclick="hapus(' . "'" . $id . "'" . ')" class="mb-2 btn-sm btn btn-outline-danger"><i class="material-icons">'.$this->default['iconDelete'].'</i></a> ';
        }
        return $tombol;
    }


    // ------------ CRUD -------------------------

    public function add() {
        if (!$this->tambah) {
            $result = array("msg" => "Do not have access.");
        } else {
            $result = array();
            $data = $this->dataInput();
            if ($data["valid"]) {
                $data["data"]["created_date"] = date($this->default['dateTimeDB']);
                $data["data"]["created_user"] = $this->session->get('sess_user_id');
                // echo "TESSSSS";
                // echo json_encode($data);
                // dd($data["data"]);
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
                $data["data"]["modified_user"] = $this->session->get('sess_user_id');
                $update = $this->model->updates($this->request->getPost($this->pkField), $data["data"]);
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
        // if (!$this->hapus) {
        //     $result = array("msg" => "Do not have access.");
        // } else {
            if ($id) {
                $data = array(
                        "status"=>9,
                        "modified_user"=>$this->session->get('sess_user_id'),
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
        // }
        echo json_encode($result);
    }

    public function get() {
        $cari = NULL;
        $kondisi = array();
        // print_r($this->request->getPost());
        if ($this->request->getPost()) {
            foreach ($this->request->getPost() as $key => $value) {
                if ($key == "cari" || $key == "q") {
                    $cari = $value;
                } elseif (strpos($key, ".")) {
                    $kondisi[$key] = $value;
                } else {
                    $kondisi[$this->table . "." . $key] = $value;
                }
            }
        }
        // print_r($kondisi);
        $result = $this->model->get($cari, $kondisi);
        echo json_encode($result);
    }


    // ---- LOG -------------------------------
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
        $filename = $path . $this->session->get('sess_user_id') . ".txt";
        $message = date($this->default['dateTimeDB']) . '; ' . $action . ' ; ' . $this->current . '; ' . $data . '; ';

        if(file_exists($filename) && filesize($filename) >= $this->default['LogMaxSize']){ // 100000000 = 100 Mb
            copy($filename, $path . $this->session->get('sess_user_id') . "-" . date('YmdHis') . ".txt");
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
        $input["id_user"]    = $this->session->get('sess_user_id');
        $input["username"]   = $this->session->get('sess_nama');
        $input["datetime"]   = date($this->default['dateTimeDB']);
        $input["action"]     = $action;
        $input["modul"]      = $this->current;
        $input["message"]    = $data;
        $this->Log_activity_model->add($input);

    }
}
