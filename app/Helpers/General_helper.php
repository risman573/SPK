<?php

function sum_rate($jumlah) {
    $hasil = '';
    $total = $jumlah;
    for ($x = 0; $x < floor($jumlah); $x++) {
        $hasil .= '<i class="material-icons text-warning">star</i>';
        $total = $total-1;
    }
    //        echo $total;
    if($total>0 && $total<1){
        $hasil .= '<i class="material-icons text-warning">star_half</i>';
    }
    
    $sisa = 5-ceil($jumlah);
    for ($x = 0; $x < $sisa; $x++) {
        $hasil .= '<i class="material-icons text-warning">star_border</i>';
    }
    return $hasil;
}

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



function checkNumbr($number) {
    if(substr($number, 0, 1) == '8'){
        $no = '62'.$number;
    }elseif(substr($number, 0, 1) == '0'){
        $no = '62'.substr($number, 1);
    }elseif(substr($number, 0, 3) == '+62'){
        $no = '62'.substr($number, 3);
    }else{
        $no = $number;
    }
    return $no;
}
