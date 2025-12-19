<?php

namespace App\Controllers\Setting;
 
use App\Controllers\MY_Controller;
use App\Models\Authority_model;
 
class Authority extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->Authority_model = new Authority_model();
        
        $this->model = $this->Authority_model;
        $this->table = "authority";
        $this->pkField = "id_auth";

        //pair key value (field => TYPE)
        //TYPE: EMAIL/STRING/INT/FLOAT/BOOLEAN/DATE/PASSWORD/URL/IP/MAC/RAW/DATA
        $this->fields = array(
            "group_name" => array("TIPE" => "STRING", "LABEL" => "Group Name"),
            "menu_name" => array("TIPE" => "STRING", "LABEL" => "Menu Name"),         
            "addable" => array("TIPE" => "STATUS", "LABEL" => "Addable"),
            "updateable" => array("TIPE" => "STATUS", "LABEL" => "Updateable"),
            "deleteable" => array("TIPE" => "STATUS", "LABEL" => "Deleteable"),
            "status" => array("TIPE" => "STATUS", "LABEL" => "Status"),
            "modified_date" => array("TIPE" => "DATE", "LABEL" => "Modified Date"),
            "action" => array("TIPE" => "TRANSACTION", "LABEL" => "Action")
        );
        //$this->kondisi = array("username"=>$this->request->getPost("username"),
        //    "password"=>md5($this->request->getPost("password")));
        
        //$this->model->fieldsView = $this->fields;
    }
    
    public function index() {
        if (!$this->session->get('website_admin_logged_in')) {
            return redirect()->to(base_url().'login');
        }
    }
    
    public function dataInput() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('id_group', 'Group Name', 'required');
        $this->form_validation->set_rules('id_menu', 'Menu', 'required');
        $this->form_validation->set_rules('addable', 'Addable', 'required');
        $this->form_validation->set_rules('updateable', 'Editable', 'required');
        $this->form_validation->set_rules('deleteable', 'Deleteable', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        

        if ($this->form_validation->run() == FALSE) {
            return array("valid" => FALSE, "error" =>  $this->form_validation->listErrors());
        } else {
            $data = array();
            foreach ($this->request->getPost() as $key => $value) {
                if ($key == "method") {
                    
                } elseif ($key == $this->pkField) {
                    $data[$key] = !$value ? $this->uuid->v4() : $value;
                } elseif ($key == 'modified_date') {
                    $data[$key] = date('Y-m-d');
                } elseif ($key == 'modified_user') {
                    $data[$key] = $this->session->get('sess_user_id');
                } elseif ($key == 'password'){
                    $data[$key] = md5($value);
                } else {
                    if (isset($value)) {
                        $data[$key] = $value;
                    }
                }
            }

            return array("valid" => TRUE, "data" => $data);
        }
    }
    
    function get_list($id = null){
        $dt = $this->model->getMenuList($id);
//        print_r($dt);
        $result = $this->buildTree($dt);
        $this->createList($result);
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
//        print_r($branch);
        return $branch;
    }
    
    function createList(array &$elements){
        foreach($elements as $e){
            $akses='';
            $add_check='';
            $update_check='';
            $delete_check='';
            $check='';
            $textadd_check='0';
            $textupdate_check='0';
            $textdelete_check='0';
            $textcheck='0';
			$no=$e['id_menu'];
            
            if($e['type'] == 1){
                $menu_name = "&nbsp;&nbsp; ".$e['menu_name'];
            }else if($e['type'] == 2){
                $menu_name = "&nbsp;&nbsp;&nbsp;&nbsp;- ".$e['menu_name'];
            }else if($e['type'] == 3){
                $menu_name = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ".$e['menu_name'];
            }else{
                $menu_name = "<b>".$e['menu_name']."</b>";
            }
            
            if($e['addable'] == 1){
                $add_check = 'checked="checked"';
                $textadd_check = '1';
            }            
            if($e['updateable'] == 1){
                $update_check = 'checked="checked"';
                $textupdate_check = '1';
            }            
            if($e['deleteable']  == 1){
                $delete_check = 'checked="checked"';
                $textdelete_check = '1';
            }
            
            if($e['id_auth'] != '' || $e['id_auth'] != null){                
                $check = 'checked="checked"';
                $textcheck = '1';
            }
            
            if($e['menu_name']=='Default' || $e['menu_name']=='Company'){
                $akses = 'disabled="disabled"';
            }
                        
            echo '<tr>'.
                    '<td align="center">
                            <input type="checkbox" id="check'.$no.'" name="check[]" '.$check.' value=":'.$e['parent'].':'.$e['id_auth'].'" onclick="setcek(this.id)">
                            <input type="hidden" id="textcheck'.$no.'" name="textcheck[]" value="'.$textcheck.'" style="width: 20px;">
                    </td>'.
                    '<td>'. $menu_name .'
                            <input type="hidden" id="textid_auth'.$no.'" name="textid_auth[]" value="'.$e['id_auth'].'" style="width: 20px;">
                            <input type="hidden" id="textid_menu'.$no.'" name="textid_menu[]" value="'.$e['id_menu'].'" style="width: 20px;">
                    </td>'.
                    '<td align="center">
                            <input type="checkbox" '.$akses.' id="addable'.$no.'" name="addable[]" '.$add_check.' value="1" onclick="setcek(this.id)">
                            <input type="hidden" id="textaddable'.$no.'" name="textaddable[]" value="'.$textadd_check.'" style="width: 20px;">
                    </td>'.
                    '<td align="center">
                            <input type="checkbox" id="updateable'.$no.'" name="updateable[]" '.$update_check.' value="1" onclick="setcek(this.id)">
                            <input type="hidden" id="textupdateable'.$no.'" name="textupdateable[]" value="'.$textupdate_check.'" style="width: 20px;">
                    </td>'.
                    '<td align="center">
                            <input type="checkbox" '.$akses.' id="deleteable'.$no.'" name="deleteable[]" '.$delete_check.'value="1" onclick="setcek(this.id)">
                            <input type="hidden" id="textdeleteable'.$no.'" name="textdeleteable[]" value="'.$textdelete_check.'" style="width: 20px;">
                    </td>'.
                '</tr>';
            if(isset($e['children'])){
                $this->createList($e['children']); 
            }
        }
    }
    
    public function add_auth($id_group){
        /*$data2 = $this->request->getPost();
        if($this->request->getPost('id_group')){
            $id_group = $this->request->getPost('id_group');
            unset($data2['id_group']);
        }*/
		
		$no = 0;
		$textid_auth = $this->request->getPost('textid_auth');
		$textid_menu = $this->request->getPost('textid_menu');
		$textaddable = $this->request->getPost('textaddable');
		$textupdateable = $this->request->getPost('textupdateable');
		$textdeleteable = $this->request->getPost('textdeleteable');
		// print_r($this->request->getPost('textcheck'));
		$data_ok = $this->request->getPost('textcheck');
		foreach($data_ok as $d){
                    //print_r($d . '<br>');
                    if($d==1){
                        $data['addable'] = $textaddable[$no];
                        $data['updateable'] = $textupdateable[$no];
                        $data['deleteable'] = $textdeleteable[$no];
                        $data['modified_user'] = $this->session->get('sess_user_id');   
                        $data['modified_date'] = date('Y-m-d H:i:s');
                        if($textid_auth[$no]!=''){
                            $this->model->updates($textid_auth[$no], $data);
                        }else{
                            $data['id_auth'] = $this->uuid->v4();
                            $data['id_group'] = $id_group;
                            $data['id_menu'] = $textid_menu[$no];
                            $data['status'] = '1';   
                            $data['created_user'] = $this->session->get('sess_user_id');   
                            $data['created_date'] = date('Y-m-d H:i:s');   
    //					$this->load->model("Authority_model");
                            $this->model->add($data);
                        }
                    }else{
                        if($textid_auth[$no]!=''){
                            $this->delete($textid_auth[$no]);
                        }
                    }
                $no++;
		}
		
    }

    public function parent_check($value) {
        
        $data["m_user.id_user"] = $value;
        $result = $this->model->check($data);
        if (!$result) {
            $this->form_validation->set_message(__FUNCTION__, "Parent " . $value . " tidak ada.");
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function ubah_password() {
        $result = array();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[255]|xss_clean|callback_password_check');
        $this->form_validation->set_rules('password1', 'Password Baru', 'trim|required|min_length[3]|max_length[255]|xss_clean');
        $this->form_validation->set_rules('password2', 'Pengulangan Password', 'trim|required|min_length[3]|max_length[255]|xss_clean|matches[password1]');
        if ($this->form_validation->run() == FALSE) {
            $result = array("msg" =>  $this->form_validation->listErrors());
        } else {       
            $data = array();
            $id_user = $this->request->getPost("id_user");
            $data["m_users.password"] = md5($this->request->getPost("password2"));
            $hasil = $this->model->updates($id_user, $data);
            if($hasil) {
                $result = array("success" => TRUE);
            } else {
                $result = array("msg" => "Gagal tersimpan.");
            }
        }
        echo json_encode($result);
    }

       

    private function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
    
    public function event() {
        $event = $this->model->event();
        foreach ($event as $value) {
            echo $value->id_event;
        }
    }

}