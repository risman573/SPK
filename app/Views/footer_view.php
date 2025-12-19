

        </div>

    <div class="modal fade" id="modal_form_edituser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form_home" class="uk-form-stacked" enctype="multipart/form-data">
                    <div class="modal-header <?=$default['themeColor']?> text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body text-center">
                            <div class="avatar avatar-120 mx-auto my-3">
                                <img id="photo_img" src="" alt="user avatar"/>
                                <img id="loading" src="<?=$base_url?>files/image/user/loader.gif" alt="loading" style="display:none;"/>
                            </div>
                            <div class="mx-auto my-3">
                                <input type="file" name="photo" id="photo" accept="image/*" onchange="previewFile(this)">
                            </div>
                        </div>
                        <hr>
                        <h3>Ubah Password</h3>
                        <input type="hidden" class="md-input" id="id_user" name="id_user" value="<?=$user_id?>"/>
                        <div class="uk-form-row">
                            <label for="name">Password Baru</label>
                            <input id="password_1" name="password_1" type="password" class="form-control" style="width: 100%;" autocomplete="off" />
                        </div>
                        <div class="uk-form-row">
                            <label for="name">Ulangi Password Baru</label>
                            <input id="password_2" name="password_2" type="password" class="form-control" style="width: 100%;" autocomplete="off" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" onclick="simpan_passBaru()">Update</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>


    </div>
    <footer class="border-top">
        <div class="row">
            <div class="col-12 col-sm-6 "></div>
            <div class="col-12 col-sm-6 text-right"><?=$default['copyRight']?></div>
        </div>
    </footer>

    <script src="<?=$base_url?>assets/loading.js"></script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VB9KX4X3LK"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-VB9KX4X3LK');
    </script>
    <!-- page specific script -->
    <script>
        
        var tgl = new Date();
        var timezone_offset_minutes = tgl.getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
        
        
        $(document).ready(function () {  
            console.log('');
        });
        
        function toaster(judul, pesan, icon){
            $.toast({
                heading: judul,
                text: pesan,
                showHideTransition: 'plain',
                icon: icon
            })
        }
            
        function showProfile(){
            $("#form_home")[0].reset();
            if($('#photo_img').attr("src")== ''){
                tampilGambar("photo","<?=$user_photo?>");
            }
        }
        
        function simpan_passBaru(){
            var password='';
            var cond = false;
            if($('#password_1').val()!='' && $('#password_2').val()!=''){
                if($('#password_1').val() == $('#password_2').val()){
                    password = $('#password_1').val();
                    cond = true;
                }else{
//                    UIkit.modal.alert('Password Tidak Sama');
                    toaster('Failed', 'Password does not match', 'error'); 
                }
            }else{
                cond=true;
            }
//                console.log(foto);

            if(cond){
                $.ajax({
                    url: '<?=$base_url;?>setting/user/update',
                    type: "POST",
                    data: {
                        id_user: $("#id_user").val(),
                        //photo: foto,
                        telp: $("#no_hp").val(),
                        password: password,
                        modified_date: ''
                    },
                    dataType: "JSON",
                    success: function (data)
                    {
                        if (data.success) {                            
                            if(password != ''){
//                                UIkit.modal.alert("Data tersimpan.");
//                                UIkit.modal("#modal_form_edituser").hide();
                                toaster('Success', 'Updated', 'success');
                                $('#modal_form_edituser').modal('hide');
                            }
                        } else {
//                            UIkit.modal.alert(data.msg);
                            toaster('Failed', data.msg, 'error'); 
                        }
                        $.unblockUI();
                    },
                    beforeSend: function(){
                        $.blockUI();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        console.log(errorThrown);
                    }
                });
            }
        } 

        function simpan_Gambar(){
                $.ajax({
                    url: '<?=$base_url;?>setting/user/update',
                    type: "POST",
                    data: {
                        id_user: $("#id_user").val(),
                        photo: foto,
                        modified_date: ''
                    },
                    dataType: "JSON",
                    success: function (data)
                    {
                        if (data.success) {                            
                            //if(password != ''){
//                                UIkit.modal.alert("Gambar tersimpan.");
                                //UIkit.modal("#modal_form_edituser").hide();
                            toaster('Success', '', 'success');      
                            //}
                        } else {
//                            UIkit.modal.alert(data.msg);
                            toaster('Failed', data.msg, 'error'); 
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        console.log(errorThrown);
                    }
                });
        }

        function previewFile(input) {  
            var stat;
            if (document.getElementById(input.id).files.length !== 0) {
                var formData = new FormData($('#form_home')[0]);
                $.ajax({
                    url: "<?=$base_url;?>home/upload_local/photo",
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function(){
//                        $('#photo_img').css("display","none");
//                        $('#loading').css("display","block");
                        $.blockUI();
                    },
                    success: function (data) {
                        var result = eval('(' + data + ')');
                        if (result.success) {
                            foto = result.lokasi;
                            simpan_Gambar();
                            tampilGambar('profile',result.lokasi);
                            tampilGambar('profile2',result.lokasi);
                            tampilGambar('photo',result.lokasi);
                            stat = true;
                        } else {
                            stat = false;
                        }
                        $.unblockUI();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        }

        function tampilGambar(input,img){
//			console.log(input);
            if (img !== '') {
                image_url = '<?=$base_url;?>files/image/user/' + img;
                $.get(image_url)
                        .done(function () {
                            image_url=image_url;
                        })
                        .fail(function () {
                            image_url="<?=$base_url;?>files/image/user/blank.png";
                        });
                        $('#'+input+'_img').attr('src', image_url);
                        //console.log("tampilGambar",image_url);
            } else {
                $('#'+input+'_img').attr('src', '<?=$base_url;?>files/image/user/blank.png');
            }
        }

        

        function update_gajiku($id) {
            $.ajax({
                url: '<?=$base_url;?>home/updateGajiku/'+$id,
                type: "POST",
                async: false,
                dataType: "JSON",
                success: function (data){
                    console.log(data);
                },
                beforeSend: function(){
                },
                error: function (jqXHR, textStatus, errorThrown){
                }
            });
        }

        function add_notif_pekerja_log(id_pekerja, ket, id_task='', type_notif='') {
            console.log(id_pekerja+' - '+ket);
            $.ajax({
                url: "<?=$base_url;?>log_notif_pekerja/add",
                type: "POST",
                async:false,
                data: {
                    id: '',
                    tipe: type_notif, 
                    id_relasi: id_task, 
                    id_pekerja: id_pekerja, 
                    tgl: new Date().toISOString(), 
                    ket: ket, 
                    status: 0,
                    method: 'add'
                },
                dataType: "JSON",
                success: function (data){
                    console.log(data);
                    sendIdFirebase(data.id, id_task, type_notif);
                },
            });
        }
        function sendIdFirebase(id_pekerja, id_task='', type_notif='') {
            console.log('id_task');
            console.log(id_task);
            
            $.ajax({
                url: "<?=$base_url;?>log_notif_pekerja/sendIdFirebase/"+id_pekerja+"/"+id_task+"/"+type_notif,
                type: "GET",
                dataType: "JSON",
                async:false,
                success: function (data){
                    console.log(data);
                },
            });
        }
    
        function addFormat(val){
            if(isNaN(parseFloat(val))){
                val=0;
            }
            return  parseFloat(val).toFixed(0).toString().replace('.',',').replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1.");
        }

    </script>

</body>
