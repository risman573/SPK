<? $this->load->view('header_view'); ?>



<div class="container-fluid bg-light-opac mt-4 main-container">
    <div class="row">
        <div class="container-fluid my-3 main-container">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="content-color-primary page-title">Log Activity</h2>
                    <p class="content-color-secondary page-sub-title"> </p>
                </div>
                <div class="col-auto">
                </div>
            </div>
        </div>
    </div>
</div>




<div class="container-fluid mt-4 main-container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4 fullscreen">
                <div class="card-header">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="content-color-primary mb-0"> </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="dt_default" class="table table-striped table-bordered" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>







<script>

    var save_method;
    var table;
    var foto = 'no_photo.png';


    $(document).ready(function () {
        table = $('#dt_default').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthMenu: [<?=$this->default['listRowInPage']?>, <?=$this->default['listRowInPage']?>],
            ajax: {
                url:"{base_url}report/log_activity/ajax_list",
                type: "POST"
            },
            columns: [
                { title: "Date and Time" },
                { title: "Username" },
                { title: "Action" },
                { title: "Module" },
                { title: "Message" },
            ],
            order: [[ 0, "desc" ]]
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    }

</script>





<? $this->load->view('footer_view'); ?>
