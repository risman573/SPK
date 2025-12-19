<?php

namespace App\Config\Validation;

use App\Models\User_model;
use App\Models\User_iowork_model;
use App\Models\M_pekerja_model;

class User{

    public function valid_username($id){
        $User_model = new User_model();
        
        // print_r('asdasd');
        $result = $User_model->get(null, array('user.username' => $id));
        if(count((array)$result)>0){
            $error = "Username tidak dapat digunakan";
            return false;
        }else{
            return true;
        }
        // echo json_encode($respon);
    }
    
}
