<?php

/**
 * change date format from dd/mm/yyyy become yyyy-mm-dd
 * @param <type> $date
 * @return string
 */
function backend_date($date) {
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
    {
        return $date;
    }else{
        list($day, $month, $year) = explode('/', $date);
        $new_date = $year . "-" . $month . "-" . $day;
        return $new_date;
    }
    
}

/**
 * change date format from yyyy-mm-dd become dd/mm/yyyy
 * @param <type> $date
 * @return string
 */
function frontend_date($date) {
    list($year, $month, $day) = explode('-', $date);
    $new_date = $day . "/" . $month . "/" . $year;
    return $new_date;
}

/**
 * create money value in Rupiah
 * @param <type> $uang
 * @return <type>
 */
function format_rupiah($uang) {
    return "Rp. " . number_format($uang, 0, ",", ".");

    //    if ($uang >= 0) {
    //        return 'Rp ' . number_format($uang, 0, ',', '.');
    //    } else {
    //        return 'Rp -' . number_format(abs($uang), 0, ',', '.');
    //    }
}

function format_dana($uang) {
    return number_format($uang, 0, ".", "");
}

function spasi($total) {
    $string = '';
    for ($i=0; $i < $total; $i++) { 
        $string .= ' &nbsp; ';
    }
    return $string;
}

function val_decimal($angka) {
    $angka2=(preg_replace("/,/","",$angka));
    return floatval(preg_replace("/^[^0-9\.]/","",$angka2));
}

function indonesian_month($month) {
    switch ($month) {
        case 1 : $imonth = "Januari";
            break;
        case 2 : $imonth = "Februari";
            break;
        case 3 : $imonth = "Maret";
            break;
        case 4 : $imonth = "April";
            break;
        case 5 : $imonth = "Mei";
            break;
        case 6 : $imonth = "Juni";
            break;
        case 7 : $imonth = "Juli";
            break;
        case 8 : $imonth = "Agustus";
            break;
        case 9 : $imonth = "September";
            break;
        case 10 : $imonth = "Oktober";
            break;
        case 11 : $imonth = "November";
            break;
        case 12 : $imonth = "Desember";
            break;
        default : $imonth = "-";
            break;
    }
    return $imonth;
}

/**
 * change date format to indonesia
 * @param <type> $month
 * @return string
 */
function indonesian_date($date) {
    list($year, $month, $day) = explode('-', $date);
    $new_date = $day . " " . indonesian_month((int)$month) . " " . $year;
    return $new_date;
}
function indonesian_dateTime($date) {
    list($date, $time) = explode(' ', $date);
    list($year, $month, $day) = explode('-', $date);
    $new_date = $day . " " . indonesian_month((int)$month) . " " . $year;
    return $new_date;
}

function indonesian_yearmonth($yearmonth) {
    list($month, $year) = explode(' ', $yearmonth);
    $new_yearmonth = indonesian_month($month) . " " . $year;
    return $new_yearmonth;
}

function bulan_hari($day) {
    $tahun = floor($day / 365);
    $bulan = floor(($day - ($tahun * 365)) / 30);
    $hari = $day - $bulan * 30;
    if ($day == "30") {
        return $bulan . " Bulan ";
    } elseif ($day >= "30") {
        return $bulan . " Bulan, " . $hari . " Hari";
    } else {
        return $hari . " Hari";
    }
}
 
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

function template_email($konten, $link_cdn){
    return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!-- Font -->
            <link rel="stylesheet" href="../email/css/style.css">
            <!-- Font -->
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600&amp;display=swap" rel="stylesheet">
            <!-- last Font -->
        </head>
        <body  style="max-width: 500px;margin: auto;box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;margin-bottom: 50px;margin-top: 50px; border-radius: 8px;">
            <div class="dash" style="width: 100%; height: 22px;left: -5px; top: -13px;background: #1A3B6B;">
            </div>
            <div class="container">
                <!-- logo -->
                <div class="row justify-content-center text-center mt-3" style="text-align:center;align-item:center;">
                    <div class="col-sm-12">
                        <img class="img-logo" src="'.$link_cdn['link_cdn'].'/email/logo.png" style="width: 86px !important;margin-top:20px;">
                    </div>
                </div>
                <!-- last logo -->
            </div>
            <!-- text header -->
            '.$konten.'

            <br>
            <br>
            If you want to know further more about us ,  please visit our website <a href="https://iowork.id">iowork.id</a>
            <br>
            <br>
            Thank you, <br><span style="font-weight:600">IOWork Team</span>
            <hr>
            <!-- footer -->
            <div style="font-family: "Poppins";min-width:260px;min-height:100%;padding:10px;margin:0 auto;background-color:#f7f7f7">
                <table class="table" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;min-width:300px;margin:0 auto">
                    <tbody>
                        <tr  align="center" style="padding: 0 8px;">
                                <td  align="center" style="padding:0 8px">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;">
                                        <tbody>
                                            <tr>
                                                <td align="center" style="font-size: 14px;font-weight: 600;">Need More Help?<br><br>Visit : <a href="https://help.iowork.id">help.iowork.id</td>
                                            </tr>
        
                                            <tr>
                                                <td colspan="3" style="font-size:1px;border-bottom:1px solid #e4e2e0;padding-top: 20px;"></td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="left" style="padding-top: 20px;font-size: 14px;"><span style="font-weight: 600;">Support Centre</span> <br>
                                                    <!-- <span style="font-size: 13px;"> <img src="ini icon" alt=""> info@iorooms.id</span></td> -->
                                                    <span style="font-size: 13px;"><img src="'.$link_cdn['link_cdn'].'/email/mail.png"> '.$link_cdn['profile_email'].'</span><br>
                                                    <span style="font-size: 13px;"><img src="'.$link_cdn['link_cdn'].'/email/telp.png"> '.$link_cdn['profile_telp'].'</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="padding-top: 50px;font-size: 13px;">
                                                    <span style="font-weight: 500;">All Right Reserved - PT Mega Kreasi Teknologi, 2023 </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="padding-top: 20px;font-size: 13px;">
                                                    <a href="'.$link_cdn['profile_facebook'].'"><img src="'.$link_cdn['link_cdn'].'/email/facebook.png"></a>
                                                    <a href="'.$link_cdn['profile_instagram'].'"><img src="'.$link_cdn['link_cdn'].'/email/instagram.png"></a>
                                                    <a href="'.$link_cdn['profile_youtube'].'"><img src="'.$link_cdn['link_cdn'].'/email/youtube.png"></a>
                                                    <a href="'.$link_cdn['profile_linkedin'].'"><img src="'.$link_cdn['link_cdn'].'/email/linkedin.png"></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- last address -->
        </body>
        </html>
    ';
        
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




//---------- KIRIM WALLET GAJIKU -------------- 

function kirim_tiket($token, $id, $pesan){
    $telegrambot = $token; 
    $website="https://api.telegram.org/".$telegrambot; 
    $chatId=$id; 
    $params=array('chat_id'=>$chatId,'text'=>$pesan, 'parse_mode' => 'HTML');    

    $ch = curl_init($website . '/sendMessage'); 
    curl_setopt($ch, CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params)); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $result = curl_exec($ch); 
    curl_close($ch);
    return $result;
}



//---------- KIRIM WALLET GAJIKU -------------- 

function auth(){
    // $ID = getenv('GAJIKU_ID');
    // $SECRET = getenv('GAJIKU_SECRET');
    // $data = $ID . ':' . $SECRET;
    // $hasil = 'Basic ' . base64_encode($data);
    // return $hasil;
}

function daftarGajiku($employeeId, $phone, $ktp, $name, $email, $bank_code, $bank_number, $kota) {
    // $auth = auth();
    // // print_r($auth);
    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => getenv('GAJIKU_URL').'/employee',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS =>'{
    //         "employeeId": "'.$employeeId.'",
    //         "phoneNumber": "'.$phone.'",
    //         "idCardNumber": "'.$ktp.'",
    //         "name": "'.$name.'",
    //         "email": "'.$email.'",
    //         "bankAccountCode": "'.$bank_code.'",
    //         "bankAccountNumber": "'.$bank_number.'",
    //         "ewaAllowed": true,
    //         "ewaMonthlyLimit": 10000000,
    //         "locationCode": "'.$kota.'"
    //     }',
    //     CURLOPT_HTTPHEADER => array(
    //         'Authorization: '.$auth,
    //         'Content-Type: application/json'
    //     ),
    //   ));
    
    // $response = curl_exec($curl);
    // // print_r($response);
    // return json_decode($response);
    
}

function addWallet($employeeId, $refId, $amount, $desc) {
    // $auth = auth();
    // // print_r($auth);
    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => getenv('GAJIKU_URL').'/ewa-wallet',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS =>'{
    //         "employeeId": "'.$employeeId.'",
    //         "refId": "'.$refId.'",
    //         "amount": '.$amount.',
    //         "description": "'.$desc.'"
    //     }',
    //     CURLOPT_HTTPHEADER => array(
    //         'Authorization: '.$auth,
    //         'Content-Type: application/json'
    //     ),
    // ));
    
    // $response = curl_exec($curl);
    // // print_r($response);
    // return json_decode($response);
    
}

function addWalletBatch($employeeId, $refId, $amount, $desc) {
    // $auth = auth();
    // // print_r($auth);
    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => getenv('GAJIKU_URL').'/ewa-wallet/batch',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS =>'{ 
    //         "data" :  [{
    //             "employeeId": "'.$employeeId.'",
    //             "refId": "'.$refId.'",
    //             "amount": '.$amount.',
    //             "description": "'.$desc.'",
    //             "date": "'.date('Y-m-d').'"
    //         }]
    //     }',
    //     CURLOPT_HTTPHEADER => array(
    //         'Authorization: '.$auth,
    //         'Content-Type: application/json'
    //     ),
    // ));
    
    // $response = curl_exec($curl);
    // // print_r($response);
    // return json_decode($response);
    
}

function updateGajiku($employeeId, $phone, $ktp, $name, $email, $bank_code, $bank_number, $kota) {
    // $auth = auth();
    // // print_r($auth);
    // $curl = curl_init();
    // curl_setopt_array($curl, array(
    //     CURLOPT_URL => getenv('GAJIKU_URL').'/employee/'.$employeeId,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'PUT',
    //     CURLOPT_POSTFIELDS =>'{
    //         "phoneNumber": "'.$phone.'",
    //         "idCardNumber": "'.$ktp.'",
    //         "name": "'.$name.'",
    //         "email": "'.$email.'",
    //         "bankAccountCode": "'.$bank_code.'",
    //         "bankAccountNumber": "'.$bank_number.'",
    //         "ewaAllowed": true,
    //         "ewaMonthlyLimit": 1000000,
    //         "locationCode": "'.$kota.'"
    //     }',
    //     CURLOPT_HTTPHEADER => array(
    //         'Authorization: '.$auth,
    //         'Content-Type: application/json'
    //     ),
    // ));
    
    // $response = curl_exec($curl);
    // return json_decode($response);
    
}
