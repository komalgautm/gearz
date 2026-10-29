@include('admin.includes.header')
<style>
    .table img {
        object-fit: cover;
    }

    .reviewimg img {
        height: 316px;
        width: 100%;
        object-fit: cover;
    }
    .reviewpara p{
        text-align: justify;
    }

    a.delete-img{
        position: relative;
        top: 25px;
        left: 5px;
    }

</style>
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                    </ol> -->
                    </div>
                    @if(Session::has('message'))
                    <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
                    @endif
                    <!-- <a href="{{ url('admin/addMagazine') }}" class="white_btn3">Create</a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <h3 class="mb-5"><?php if ($language == 2) {
                    echo $news->heading_ar;
                } else {
                    echo $news->heading;
                } ?></h3>

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group p-3">
                                <span class="float-start"><strong>Posted By:</strong> <?php if ($language == 2) {
                                    echo $news->postedBy_ar;
                                } else {
                                    echo $news->postedBy;
                                } ?></span>
                                <span class="float-end"> {{date('M d,Y',strtotime($news->postedDate))}} <?php if ($news->end_date != '') { ?>- {{date('M d,Y',strtotime($news->end_date))}}<?php } ?></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php if ($news->type == '4') { ?>
                                <div class="form-group p-3">
                                    <a href="{{$news->facebook}}"><i class="fa fa-facebook"></i></a> <a href="{{$news->instagram}}"><i class="fa fa-instagram"></i></a> <a href="{{$news->twitter}}"><i class="fa fa-twitter"></i></a> <a href="{{$news->linkedin}}"><i class="fa fa-linkedin"></i></a>
                                </div>
                                <div class="form-group p-3 reviewpara">
                                    <span class="float-start">Address: {{$news->address}}</span>
                                </div>
                                <div class="form-group p-3 reviewpara">
                                    <span class="float-start">Contact: {{$news->contact_number}}</span>

                                </div>
                                <div class="form-group p-3 reviewpara">

                                    <span class="float-start">Website: {{$news->website}}</span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group py-4 reviewpara">
                                <p><?php if ($language == 2) {
                                    echo $news->description_ar;
                                } else {
                                    echo $news->description;
                                } ?></p>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($AllBannerImage as $imgbanner)
                            <div class="col-md-2">
                                <a href="" class="delete-img"><i class="fa fa-trash"></i></a>
                                <img class="img-fluid" src="<?php if($imgbanner->image!=''){ echo url("public/images/".$imgbanner->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt>

                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <?php if ($news->videourl != '') {
                                $urldata = explode("=",$news->videourl);
                                $urlf=end($urldata);
                            ?>
                                <div class="form-group">
                                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{$urlf}}"></iframe>

                                </div>
                            <?php } ?>
                        </div>



                        <div class="col-md-12">
                            <div class="form-group p-3 reviewpara">
                                <span class="float-start"><?php if ($language == 2) {
                                    echo $news->tag_name_ar;
                                } else {
                                    echo $news->tag_name;
                                } ?></span>
                                <span class="float-start"><img src="{{ url('public/images/'.$news->tag_icon) }}" width="100px" height="100px" /></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".delete").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/delete_magazine")}}',
                data: {
                    id: id,
                    _token: token
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $('.flexSwitchCheckChecked').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if ($(this).is(':checked')) {
            var status = 1;
        } else {
            var status = 0;
        }
        if (confirm("Are you sure want to change this status?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/change_magazine_status")}}',
                data: {
                    id: id,
                    _token: token,
                    status: status
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $('.flexSwitchCheckCheckedhome').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if ($(this).is(':checked')) {
            var status = 1;
        } else {
            var status = 0;
        }
        if (confirm("Are you sure want to add this on home screen?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/addhomemagazine")}}',
                data: {
                    id: id,
                    _token: token,
                    status: status
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $('.flexSwitchCheckCheckedbanner').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if ($(this).is(':checked')) {
            var status = 1;
        } else {
            var status = 0;
        }
        if (confirm("Are you sure want to add this on Banner?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/addbannermagazine")}}',
                data: {
                    id: id,
                    _token: token,
                    status: status
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Export',
                exportOptions: {
                    columns: [1, 2, 4, 5, 6]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Magazine' + n;
                },
                title: 'Magazine'
            }],
        });
    });
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(".row_position").sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $(".row_position>tr").each(function() {
                selectedData.push($(this).attr("id"))
            });
            console.log(selectedData);
            updateOrder(selectedData);
        }
    });

    function updateOrder(aData) {
        var token = "<?php echo csrf_token(); ?>";
        $.ajax({
            url: "{{url('updatemagzineOrder')}}",
            type: "POST",
            data: {
                allData: aData,
                _token: token
            },
            success: function(data) {
                console.log(data);
                $(window).scrollTop(0);
                $("#orderUpdateMsg").show();
                $("#orderUpdateMsg").fadeOut(3000);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        });
    }
</script>

@include('admin.includes.footer')
