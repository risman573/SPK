

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">

    <!--CSS-->
    <link rel="icon" href="<?=$base_url?><?=$default['logo']?>">
    <!-- <link rel="stylesheet" href="<?=$base_url?>assets/vendor/bootstrap-4.1.3/scss/bootstrap.scss"> -->
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/materializeicon/material-icons.css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/animatecss/animate.css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">
    <link href="<?=$base_url?>assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link id="theme" rel="stylesheet" href="<?=$base_url?>assets/css/<?=($default['themeColor']==''?'blue':$default['themeColor'])?>sidebar.css" type="text/css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/bootstrap-tagsinput/tagsinput.css">

    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/bootstrap-daterangepicker-master/daterangepicker.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mosque" />


    <style>
        .menu-text{
            font-size: small;
            margin-left: 20px;
            color: white;
        }
    </style>


    <!-- JavaScript -->
    <script src="<?=$base_url?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=$base_url?>assets/js/popper.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/bootstrap-4.1.3/js/bootstrap.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/cookie/jquery.cookie.js"></script>
    <script src="<?=$base_url?>assets/js/main.js"></script>
    <script src="<?=$base_url?>assets/vendor/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/DataTables-1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/chosen1.8/chosen.jquery.min.js"></script>
    <script src="<?=$base_url?>assets/loading.js"></script>
    <script src="<?=$base_url?>assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/bootstrap-tagsinput/tagsinput.js"></script>
    <script src="<?=$base_url?>assets/js/moment.min.js"></script>

    <!--CHART -->

    <title><?=$default['titleTab']?></title>
</head>

<body class="fixed-header sidebar-right-close <?=($default['themeBarIcon']=='true' ? 'sidebar-left-close iconsibarbar' : '')?>">
    <!-- page loader -->
    <div class="loader justify-content-center <?=$default['themeColor']?> text-white">
        <div class="align-self-center text-center">
            <img src="<?=$base_url?>assets/img/loading.gif" alt="" style="width: 150px; border-radius:20px;" class="logo-icon">
            <h2 class="mt-3 font-weight-light"><?=$default['title']?></h2>
            <p class="mt-2 text-white">Awesome things getting ready...</p>
            <!-- <div class="logo-img-loader">
                <img src="<?=$base_url?>assets/img/loader-bg.png" alt="" class="logo-bg-image">
            </div> -->
        </div>
    </div>
    <!-- page loader ends  -->

    <div class="wrapper">
        <!-- main header -->
        <header class="main-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto pl-0">
                        <button class="btn <?=$default['themeColor']?> text-white btn-icon" id="left-menu"><i class="material-icons">widgets</i></button>
                        <a href="<?=$base_url.'home'?>" class="logo"><img src="<?=$base_url?><?=$default['logo']?>" alt=""><span class="text-hide-xs"><?=$default['title']?></span></a>
                    </div>
                    <div class="col text-center p-xs-0">

                    </div>
                    <div class="col-auto pr-0">
                        <div class="dropdown d-inline-block">
                            <a class="btn header-color-secondary dropdown-toggle username" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <figure class="userpic"><img src="<?=$base_url?>files/image/user/<?=$user_photo?>" alt=""></figure>
                                <h5 class="text-hide-xs">
                                    <small class="header-color-secondary">Welcome,</small>
                                    <span class="header-color-primary"><?=$user_nama?></span>
                                </h5>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown" aria-labelledby="dropdownMenuLink">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <figure class="avatar avatar-120 mx-auto my-3">
                                            <img src="<?=$base_url?>files/image/user/<?=$user_photo?>" alt="">
                                        </figure>
                                        <h5 class="card-title mb-2 header-color-primary"><?=$user_nama?></h5>
                                        <a href="#" data-toggle="modal" data-target="#modal_form_edituser" onclick="showProfile()" class="btn btn-sm <?=$default['themeColor']?> text-white border-0 mb-3">Edit</a>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <a class="dropdown-item pink-gradient-active" href="<?=$base_url?>login/keluar">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            Logout
                                        </div>

                                        <div class="col-auto">
                                            <div class="text-danger ml-2"><i class="material-icons vm">exit_to_app</i></div>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </header>
        <!-- main header ends -->

        <!-- sidebar left -->
        <div class="sidebar sidebar-left" style="width: 275px;">
            <br>
            <?=$menu_string?>
        </div>
        <!-- sidebar left ends -->


        <div class="page-content" id="" style="margin-left: 50px;">
