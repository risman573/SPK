<?php

// namespace App\Helpers;

    function config_email($row){
        $config = array(
            'protocol' => $row['email_service'],
            'SMTPHost' => $row['email_host'],
            'SMTPPort' => $row['email_port'],
            // 'smtp_from_name' => 'IOWork Indonesia',
            'SMTPUser' => $row['email_user'],
            'SMTPPass' => $row['email_pass'],
            'newline' => '\r\n',
            'mailType' => 'html',
            'charset' => 'utf-8',
            'wordWrap' => true,
            // 'smtp_crypto'   => $row->akun_smtp_crypto
        );
        return $config;
    }

    function temp_register($nama, $id, $help){
        // $help = $defaults_apps;
        $konten = '
                        <div class="container" style="margin-top: 20px;">
                            <div class="row  justify-content-center text-center mt-4">
                                <div class="col-sm-12">
                                    <p class="text-header-only" style="text-align: center; font-family: \'Exo 2\', sans-serif;font-weight: 800;font-size: 25px;color: #1A3B6B;"> Account Verification </p>
                                </div>
                            </div>
                        </div>
                        <!-- text header -->
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B; font-family: \'Exo 2\', sans-serif; font-size: 13px;font-weight: 500;"><b>Hi, '.$nama.'</b></p>
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B;font-family: \'Exo 2\', sans-serif;font-size: 13px;font-weight: 500;">Immediately verify your account</p>
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B;font-family: \'Exo 2\', sans-serif;font-size: 13px;font-weight: 500;"> To verify your account please follow the link below:</p>
                                    <!-- button -->
                                    <div class="container">
                                        <div class="row text-center" style="justify-content: center;text-align: center;margin-top: 40px;margin-bottom: 40px;">
                                            <div class="col-sm-12">
                                            <a href="'.$help['link_backend'].'/account/activation/'.$id.'" style="border-radius: 10px;background: #1a3b6b;border:0 solid #2d8cff;display:inline-block;font-size:14px;padding:15px 50px 15px 50px;text-align:center;font-weight:700;text-decoration:none;color:#ffffff" target="_blank">Activate Account Now</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- last button -->
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B;font-family: \'Exo 2\', sans-serif;font-size: 13px;font-weight: 500;">Don&rsquo;t share any sensitif information about account , bank account and your password to others</p>
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B;font-family: \'Exo 2\', sans-serif;font-size: 13px;font-weight: 500;">If you need help or have any questions, please visit https://Iowork.id</p>
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B;font-family: \'Exo 2\', sans-serif;font-size: 13px;font-weight: 500;">Thank you,</p>
                                    <p class="text-p" style="margin-left:15px !important;color: #1A3B6B;font-family: \'Exo 2\', sans-serif;font-size: 13px;font-weight: 500;"><b>IOWORK Team</b></p>
                                </div>
                            </div>
                        </div>
                    ';
        return $konten;
        // return $this->template($konten, 'Register');
    }

    function template($konten, $title, $help){
        // $help = $defaults_apps;

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
                        <img class="img-logo" src="'.$help['link_cdn'].'/email/logo.png" style="width: 86px !important;margin-top:20px;">
                    </div>
                </div>
                <!-- last logo -->
            </div>
            <!-- text header -->
                                            
            '.$konten.'

            
            <!-- footer -->
            <div class="container" style="margin-top: 30px;">
                <div class="row text-center">
                    <div class="col-sm-12">
                        <p class="text-footer" style="text-align: center; margin-top: 70px;font-weight: 600;font-family: \'Exo 2\', sans-serif;font-size: 13px;">Support Centre</p>
                    </div>
                </div>
            </div>
            <div class="container" style="align-items: center;">
                <div class="row justify-content-center text-center">
                    <div class="col-sm-12">
                        <div class="row" style="margin-top: 30px;text-align: center;">
                            <img src="'.$help['link_cdn'].'/email/call.png" style="width: 20px;height: 20px !important;margin-bottom:-7px!important;"><span class="text-footer" style="margin-left:5px;font-weight: 600;font-family: \'Exo 2\', sans-serif;font-size: 13px;">'.$help['profile_telp'].'</span>
                        </div>
                        <div class="row"  style="margin-top: 10px;text-align: center;">
                            <img src="'.$help['link_cdn'].'/email/email.png" style="width: 20px;height: 20px !important;margin-bottom:-7px!important;"><span class="text-footer" style="margin-left:5px;font-weight: 600; font-family: \'Exo 2\', sans-serif; font-size: 13px;">'.$help['profile_email'].'</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- last footer -->
            <!-- address -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-sm-12">
                        <p class="text-address" style="margin-top:30px;font-family:Exo 2;font-size:10px;text-align:center;color:#1A3B6B;">'.$help['copyRight'].'</p>
                        <p class="text-address mb-0 mt-2" style="font-family: Exo 2;font-size: 10px; text-align: center; color: #1A3B6B;">'.$help['profile_alamat'].'</p>
                    </div>
                </div>
            </div>
            <!-- last address -->
        </body>
        </html>

        ';
    }
    

    function template_email_bc($konten, $link_cdn){
        // dd($konten);
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

// }
