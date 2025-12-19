<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <link rel="icon" href="<?=$base_url?><?=$default['logo']?>">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/bootstrap-4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/materializeicon/material-icons.css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/animatecss/animate.css">
    <link rel="stylesheet" href="<?=$base_url?>assets/vendor/swiper/css/swiper.min.css">
    <link id="theme" rel="stylesheet" href="<?=$base_url?>assets/css/purplesidebar.css" type="text/css">

    
    
    <title><?=$default['titleTab']?></title>
</head>

<body class="fixed-header sidebar-right-close sidebar-left-close">
    <!-- page loader -->
    <div class="loader justify-content-center">
        <div class="align-self-center text-center">
            <img src="<?=$base_url?><?=$default['logo']?>" alt="" class="logo-image" style="width: 300px;">
            <h2 class="mt-3 font-weight-light"><?=$default['title']?></h2>
            <p class="mt-2">Awesome things getting ready... </p>
            <div class="logo-img-loader">
                <img src="<?=$base_url?>assets/img/loader-bg.png" alt="" class="logo-bg-image">
            </div>
        </div>
    </div>
    <!-- page loader ends  -->

    <div class="wrapper h-100  h-sm-auto justify-content-center">
        <!-- main header -->
        <header class="main-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto pl-0">
                        <!--<button class="btn pink-gradient btn-icon" id="left-menu"><i class="material-icons">widgets</i></button>-->
                        <a href="" class="logo"><img src="<?=$base_url?><?=$default['logo']?>" alt=""><span class="text-hide-xs"><?=$default['title']?></span></a>
                    </div>
                    <div class="col text-right p-xs-0">
                        <ul class="time-day">
                            <li class="text-right">
                                <p class="header-color-primary"><span class="header-color-secondary"><?=date("M")?></span><small><?=date("Y")?></small></p>
                                <h2><?=date("d")?></h2>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                    </div>
                </div>
            </div>
        </header>
        <!-- main header ends -->



        <!-- content page -->
        <div class="carosel swiper-location-carousel bg-dark background-img position-fixed">
            <div data-pagination='{"el": ".swiper-pagination"}' data-space-between="0" data-slides-per-view="1" class="swiper-container swiper-init swiper-signin h-100">
                <div class="swiper-pagination"></div>
                <div class="swiper-wrapper">
                    <div class="swiper-slide text-center ">
                        <div class="background-img"><img src="<?=$base_url?>assets/img/BG.png" alt="" class="w-100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container h-100  h-sm-auto align-items-center">
            <div class="row align-items-center h-100  h-sm-auto">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto text-center">
                    <h1 class="font-weight-light mb-3 text-white mt-2">Login <span class="font-weight-normal"> </span></h1>
                    <h4 class="font-weight-light mb-5 text-white text-center">Please sign in to your account.</h4>
                    <p id="pesan" style="color:red;"></p>
                    <form id="form_login">
                    <div class="card mb-3">
                        <div class="card-body p-0">
                            <label for="inputEmail" class="sr-only">Username</label>
                            <input type="text" id="login_username" name="login_username" class="form-control text-center form-control-lg border-0" placeholder="Username" required="" autofocus="">
                            <hr class="my-0">
                            <label for="inputPassword" class="sr-only">Password</label>
                            <input type="password" id="login_password" name="login_password" class="form-control text-center form-control-lg border-0" placeholder="Password" required="">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" href="javascript:void(0)" class="btn btn-primary ">Sign In</button>
                    </div>
                    </form>
                    <br>

                </div>
            </div>
        </div>
        <!-- content page ends -->

    </div>
    <footer class="border-top">
        <div class="row">
            <div class="col-12 col-sm-6 text-white"></div>
            <div class="col-12 col-sm-6 text-right text-white"><?=$default['copyRight']?></div>
        </div>
    </footer>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?=$base_url?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=$base_url?>assets/js/popper.min.js"></script>
    <script src="<?=$base_url?>assets/vendor/bootstrap-4.1.3/js/bootstrap.min.js"></script>

    <!-- Cookie jquery file -->
    <script src="<?=$base_url?>assets/vendor/cookie/jquery.cookie.js"></script>

    <!-- swiper slider jquery file -->
    <script src="<?=$base_url?>assets/vendor/swiper/js/swiper.min.js"></script>

    <!-- Application main common jquery file -->
    <script src="<?=$base_url?>assets/js/main.js"></script>

    <!-- page specific script -->
    <script>
        'use strict';
        var mySwiper = new Swiper('.swiper-signin', {
            slidesPerView: 1,
            spaceBetween: 0,
            autoplay: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            }
        });


        $( "#form_login" ).submit(function( event ) {

            $('.form-group').removeClass('has-error');
            $('.help-block').remove();

            if($('input[name=login_username]').val() == ""){
                console.log('Username');
            }
            if($('input[name=login_password]').val() == ""){
                console.log('Password');
            }

            var formData = {
                'login_username' : $('input[name=login_username]').val(),
                'login_password' : $('input[name=login_password]').val()
                };

            $.post( "<?=$base_url?>login/masuk", formData)
                .done(function( data ) {

                var result = eval('(' + data + ')');

                if (!result.success) {

                    if (result.errors.username) {
                        document.getElementById("pesan").innerHTML = result.errors.username;
                    }
                    if (result.errors.password) {
                        document.getElementById("pesan").innerHTML = result.errors.password;
                    }
                } else {
                    window.location = '<?=$base_url?>home';
                }
            });

            event.preventDefault();
        });


    </script>
</body>

</html>
