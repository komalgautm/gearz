@include('admin.includes.header')
<style>
    a.delete-img {
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
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> {{ $title }}</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol> -->
                    </div>
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body">
                {{-- <div class="QA_table table-responsive pb-3"> --}}
                {{-- image --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="advice_heading">

                            <h3><?php if ($language == 2) {
                                echo $data->titlear;
                            } else {
                                echo $data->title;
                            } ?></h3>
                            <p><?php if ($language == 2) {
                                echo $data->messagear;
                            } else {
                                echo $data->message;
                            } ?></p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="advice_heading">

                            <h3>Keyword</h3>
                            <p><?php if ($language == 2) {
                                echo $data->searchkeyword_ar;
                            } else {
                                echo $data->searchkeyword;
                            } ?></p>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <img class="img-fluid"
                            src="<?php if($data->image!=''){ echo url("public/images/".$data->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt style="
                            height: 417px;
                            object-fit: contain;
                        ">
                    </div>


                </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

@include('admin.includes.footer')
