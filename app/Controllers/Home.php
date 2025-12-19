<?php

namespace App\Controllers;

use App\Controllers\MY_Controller;

class Home extends MY_Controller {

    var $base_url;
    var $menu_string;

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = array(
            'base_url' => base_url(),
        );
        return redirect()->to(base_url().$this->default['defaultController']);
    }
    
    public function upload_local($id='') {
        $result = array();
        $id = $id;
        $lokasi = "./files/image/user/";
        if (($_FILES[$id]["size"] > 20000000)) {
            $result = array('msg' => "File terlalu besar.");
        } elseif ($_FILES[$id]["error"] > 0) {
            $result = array('msg' => "Return Code: " . $_FILES[$id]["error"] . "<br>");
        } elseif ($this->security->sanitize_filename($_FILES[$id]["name"]) == FALSE) {
            $result = array('msg' => "Nama File tidak baik. Ganti nama file!");
        } elseif ($this->security->xss_clean($_FILES[$id]['tmp_name'], TRUE) === FALSE) {
            $result = array('msg' => "File gambar tidak baik. Harap ganti file!");
        } else {
			
            $filename = $id . date("Y-m-d-H-i-s");
            if (!file_exists($lokasi) && !is_dir($lokasi)) {
                mkdir($lokasi, 0777, true);
            }

            $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $nama = $withoutExt . ".png";

            move_uploaded_file($_FILES[$id]['tmp_name'], $lokasi . $nama);

            $result = array('success' => true, 'lokasi' => $nama);
        }
        echo json_encode($result);
    }

    public function upload($folder='wrong', $id='') {
        // echo $this->default_iowork['link_api'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://localhost:3333/upload',
            // CURLOPT_URL => $this->default_iowork['link_api'].'/upload',
            CURLOPT_URL => $this->default_iowork['link_api_upload_image'].'/dashboard/upload',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'profile_pic'=> new \CURLFILE($_FILES[$id]["tmp_name"], $_FILES[$id]["type"], $_FILES[$id]["name"]),
                'folder' => $folder
            ),
            // CURLOPT_POSTFIELDS => array(
            //     'image'=> new CURLFILE($_FILES[$id]["tmp_name"], $_FILES[$id]["type"], $_FILES[$id]["name"]),
            //     'folder' => $folder
            //     ),
    
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    

    public function upload_help($folder='wrong', $id='') {
        // echo $this->default_iowork['link_api'];
        $curl = curl_init();

        // print_r($id);

        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://localhost:3333/upload',
            // CURLOPT_URL => $this->default_iowork['link_api'].'/upload',
            CURLOPT_URL => $this->default_iowork['link_api_upload_image'].'/dashboard/upload',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'profile_pic'=> new \CURLFILE($_FILES[$id]["tmp_name"], $_FILES[$id]["type"], $_FILES[$id]["name"]),
                'folder' => 'help/'.$folder
            ),
            // CURLOPT_POSTFIELDS => array(
            //     'image'=> new CURLFILE($_FILES[$id]["tmp_name"], $_FILES[$id]["type"], $_FILES[$id]["name"]),
            //     'folder' => $folder
            //     ),
    
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    

    public function upload_ckeditor($folder='wrong', $id='') {
        // echo $this->default_iowork['link_api'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://localhost:3333/upload',
            // CURLOPT_URL => $this->default_iowork['link_api'].'/upload',
            CURLOPT_URL => $this->default_iowork['link_api_upload_image'].'/dashboard/upload',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'profile_pic'=> new \CURLFILE($_FILES[$id]["tmp_name"], $_FILES[$id]["type"], $_FILES[$id]["name"]),
                'folder' => 'help/'.$folder
            ),
            // CURLOPT_POSTFIELDS => array(
            //     'image'=> new CURLFILE($_FILES[$id]["tmp_name"], $_FILES[$id]["type"], $_FILES[$id]["name"]),
            //     'folder' => $folder
            //     ),
    
        ));

        $response = curl_exec($curl);
        $parsing = json_decode($response);
        $hasil = array(
            'fileName' => $parsing->data->filename,
            'url' => $parsing->data->filepath,
            'uploaded' => 1
        );
        // print_r(json_encode($hasil));

        curl_close($curl);

        $function_number = $_GET['CKEditorFuncNum'];
        // echo $parsing->data->filepath;
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$function_number."', '".$parsing->data->filepath."', '');</script>";
    }

    private function resize_image($fn, $nama, $w, $h) {
        $size = getimagesize($fn);
        $ratio = $size[0] / $size[1]; // width/height
        if ($ratio > 1) {
            $width = $w;
            $height = $h / $ratio;
        } else {
            $width = $w * $ratio;
            $height = $h;
        }
        $src = imagecreatefromstring(file_get_contents($fn));
        $dst = imagecreatetruecolor($width, $height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
        imagedestroy($src);
        imagepng($dst, $nama); // adjust format as needed
        imagedestroy($dst);
    }
     function updateGajiku($id){
        $this->db = $this->load->database('iowork',TRUE);
        $this->load->model('M_pekerja_model');
        $pekerja = $this->M_pekerja_model->get_by_id($id);
        if(count($pekerja) > 0){
            if($pekerja->gajiku_status==1){
                $update = updateGajiku($pekerja->id, $pekerja->hp, $pekerja->ktp, $pekerja->nama, $pekerja->email, $pekerja->rek_bank, $pekerja->rek_no, $pekerja->nama_kota);
                print_r($update);
            }else{
                echo "pekerja belum terdaftar";
            }
        }else{
            echo "pekerja tidak ada";
        }
    }

}
