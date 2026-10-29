
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
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> <?php if($language==2){ echo $data->name_ar;}else{ echo $data->name;} ?> {{$title}}</h4>
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
                <div class="QA_table table-responsive pb-3">
                    <table class="table p-0" id="example">
                        <tr>
                            <td>Logo</td>
                            <td><img class="img-fluid" src="<?php if($data->logo!=''){ echo url("public/images/".$data->logo);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>                            
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><?php if($language==2){ echo $data->name_ar;}else{ echo $data->name;} ?></td>                            
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><?php if($language==2){ echo $data->description_ar;}else{ echo $data->description;} ?></td>                            
                        </tr>
                        <tr>
                            <td>Website</td>
                            <td><?php  echo $data->website; ?></td>                            
                        </tr>
                        <tr>
                            <td>Youtube</td>
                            <td><?php  echo $data->youtube; ?></td>                            
                        </tr>
                        <tr>
                            <td>Twitter</td>
                            <td><?php  echo $data->twitter; ?></td>                            
                        </tr>
                        <tr>
                            <td>Linkedin</td>
                            <td><?php  echo $data->linkedin; ?></td>                            
                        </tr>
                        <tr>
                            <td>Facebook</td>
                            <td><?php  echo $data->facebook; ?></td>                            
                        </tr>
                        <tr>
                            <td>Instagram</td>
                            <td><?php  echo $data->instagram; ?></td>                            
                        </tr>                        
                        <tr>
                            <td>Tag Name</td>
                            <td><?php if($language==2){ echo $data->tag_name_ar;}else{ echo $data->tag_name;} ?></td>                            
                        </tr>
                        <tr>
                            <td>Tag Icon</td>
                            <td><img class="img-fluid" src="<?php if($data->tag_icon!=''){ echo url("public/images/".$data->tag_icon);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>                            
                        </tr>
                        <tr>
                            <td>Sponser Name</td>
                            <td><?php if($language==2){ echo $data->sponser_name_ar;}else{ echo $data->sponser_name;} ?></td>                            
                        </tr>
                        <tr>
                            <td>Sponser Icon</td>
                            <td><img class="img-fluid" src="<?php if($data->sponser_icon!=''){ echo url("public/images/".$data->sponser_icon);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>                            
                        </tr>
                        <tr>
                            <td>Banner Image</td>
                            <td>
                                <div class="row mt-4">
                                    @foreach($brandbanner as $imgbanner)
                                    <div class="col-md-2">
                                        {{-- <a href="" class="delete-img"><i class="fa fa-trash"></i></a> --}}
                                        <img class="img-fluid" src="<?php if($imgbanner->image!=''){ echo url("public/images/".$imgbanner->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt>
                                        
                                    </div>
                                    @endforeach                           
                                </div>
                            </td>                            
                        </tr>                       
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>


@include('admin.includes.footer')