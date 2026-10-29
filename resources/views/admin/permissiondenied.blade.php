
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex align-items-center justify-content-between">
            <div class="page_title_left">
                <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a></h4>
                <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Brands</li>
                </ol> -->
            </div>
            
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            {{-- <h2 class="card-title text-center p-5">Permission denied</h2> --}}
            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <h2 class="card-title text-center p-5">Permission denied</h2>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer')