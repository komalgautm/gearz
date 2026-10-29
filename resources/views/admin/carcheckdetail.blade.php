
@include('admin.includes.header')
<style>
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
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Car check detail</h4>

                    </div>
                    @if(Session::has('message'))
                    <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
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
                                <h3><?php if($language==2){ echo $data->name_ar;}else{ echo $data->name;} ?></h3>
                                <h4>Check List:</h4>
                                <ol>
                                    @php
                                    $i=1;
                                    @endphp
                                    @foreach($list as $value)
                                        <li><strong>{{$i}}.</strong> <?php if($language==2){ echo $value->name_ar;}else{ echo$value->name;} ?></p></li>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                </ol>
                                {{-- <p><?php if($language==2){ echo $data->content_ar;}else{ echo $data->content;} ?></p> --}}

                            </div>
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
