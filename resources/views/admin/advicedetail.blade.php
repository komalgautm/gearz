
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
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                        </ol> -->
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
                        <div class="col-md-9">
                            <div class="advice_heading">

                            <h3><?php if($language==2){ echo $data->title_ar;}else{ echo $data->title;} ?></h3>
                            <p><?php if($language==2){ echo $data->message_ar;}else{ echo $data->message;} ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-end align-items-end">
                            <div>
                                <h6>Sponser Image: <span><?php if($language==2){ echo $data->sponser_name_ar;}else{ echo $data->sponser_name;} ?></span></h6>
                                <div class="advice_img_20">
                                    <img class="img-fluid" src="<?php if($data->sponser_icon!=''){ echo url("public/images/".$data->sponser_icon);}else{ ?>../public/img/image-preview.png<?php } ?>" alt >
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            @foreach($AllBannerImage as $imgbanner)
                            <div class="col-md-2">
                                {{-- <a href="" class="delete-img"><i class="fa fa-trash"></i></a> --}}
                                <img class="img-fluid" src="<?php if($imgbanner->image!=''){ echo url("public/images/".$imgbanner->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt>
                                
                            </div>
                            @endforeach                           
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h2 class="mt-5 mb-4">User</h2>
                            </div>
                            <div class="QA_table table-responsive pb-3">
                                <table class="table p-0" id="example">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach($userlist as $value)
                                            <tr>                                               
                                                <td>{{$i}}</td>                 
                                                <td><?php if($value->userID==0){echo "All"; }else{echo App\Models\User::find($value->userID)->name; } ?></td>                                                                               
                                            </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h2 class="mt-5 mb-4">Comment</h2>
                            </div>
                            <!-- @foreach ($review as $val)
                                <div class="form-group">
                                    <h4>{{$val['title']}}</h4>
                                    <p>{{$val['message']}}</p>
                                </div>
                            @endforeach -->
                            @foreach ($review as $val)
                            <div class="comment_section">
                                <div class="comment_detail">
                                    <div class="comment_inner1">
                                        <p><?php
                                        $string = App\Models\User::find($val['userID'])->name;
                                        preg_match_all('/\b\w/', $string, $matches);
                                        echo $firstLetters = implode('', $matches[0]);
                                            ?></p>
                                    </div>
                                    <div class="comment_inner2">
                                        <h5 class="mb-0"><?php if($val['userID']==0){echo "All"; }else{echo App\Models\User::find($val['userID'])->name; } ?></h5>
                                        <h6 class="mb-1">{{$val['title']}}</h6>
                                    </div>
                                </div>
                                <p class="px-4">{{$val['message']}}</p>
                            </div>
                            @endforeach
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