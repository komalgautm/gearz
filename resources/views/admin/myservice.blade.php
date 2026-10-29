@include('admin.includes.header')
<style>
    .nav-link {
        color: black;
    }
    .image-border .image {
        border: 1px solid #00000038;
    }
    .card-body img {
        object-fit: contain;
        height: 100px;
        width: 100px;
    }
    .detail_tabs2 .nav-link {
        border: 1px solid transparent;
        width: 127px;
    }
    .detail_tabs2 .nav-link.active {
        color: #495057;
        background-color: #f3f7fe;
        border-radius: 5px;
        font-weight: 700;
        border: 1px solid transparent;
        width: 127px;
    }

</style>
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-md-10">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$data->car_nick_name}} Detail</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Car Detail</li>
                </ol> -->
                <div class="card p-3">
                    <div class="card-body">
                        @if(Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
                        @endif
                        <!-- <a href="{{ url('admin/addBrand') }}" class="white_btn3">Create</a> -->
                        <!-- <button type="button" class="white_btn3" data-toggle="modal" data-target="#myModal"></button> -->
                        <!-- Button trigger modal -->
                        <nav>
                            <div class="nav nav-tabs mb-3 detail_tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Details</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Service</button>
                                <!-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Comment</button> -->
                            </div>
                        </nav>
                        <div class="tab-content tab_content" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                {{-- tab --}}
                                <div class="row">
                                    {{-- tab --}}
                                    <nav>
                                        <div class="nav nav-tabs mb-3 detail_tabs2" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-car-tab" data-bs-toggle="tab" data-bs-target="#nav-car" type="button" role="tab" aria-controls="nav-car" aria-selected="true">General Information</button>
                                            <button class="nav-link" id="nav-brand-tab" data-bs-toggle="tab" data-bs-target="#nav-brand" type="button" role="tab" aria-controls="nav-brand" aria-selected="false">1st Information</button>
                                            <button class="nav-link" id="nav-agent-tab" data-bs-toggle="tab" data-bs-target="#nav-agent" type="button" role="tab" aria-controls="nav-agent" aria-selected="false">2nd Information</button>
                                            <button class="nav-link" id="nav-car-tab" data-bs-toggle="tab" data-bs-target="#nav-car" type="button" role="tab" aria-controls="nav-car" aria-selected="false">3rd Information</button>
                                            <button class="nav-link" id="nav-car1-tab" data-bs-toggle="tab" data-bs-target="#nav-car1" type="button" role="tab" aria-controls="nav-car1" aria-selected="false">4th Information</button>
                                            <button class="nav-link" id="nav-car2-tab" data-bs-toggle="tab" data-bs-target="#nav-car2" type="button" role="tab" aria-controls="nav-car2" aria-selected="false">5th Information</button>
                                            <button class="nav-link" id="nav-car3-tab" data-bs-toggle="tab" data-bs-target="#nav-car3" type="button" role="tab" aria-controls="nav-car3" aria-selected="false">6th Information</button>
                                            <button class="nav-link" id="nav-car4-tab" data-bs-toggle="tab" data-bs-target="#nav-car4" type="button" role="tab" aria-controls="nav-car4" aria-selected="false">7th Information</button>
                                            <button class="nav-link" id="nav-carimage-tab" data-bs-toggle="tab" data-bs-target="#nav-carimage" type="button" role="tab" aria-controls="nav-carimage" aria-selected="false">Car Image</button>
                                        </div>
                                    </nav>
                                    <div class="tab-content tab_content2 p-3" id="nav-tabContent">
                                        <div class="tab-pane fade active show" id="nav-car" role="tabpanel" aria-labelledby="nav-car-tab">
                                            <div class="col-lg-12">
                                                <div class="white_card card_height_100 mb_20 ">
                                                    <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
                                                    <div class="white_card_body text-left">
                                                        <div class="QA_table table-responsive pb-3">
                                                            <div class="QA_table ">
                                                                <table class="table p-0">
                                                                    <tr>
                                                                        <td><strong>Car Nick Name</strong></td>
                                                                        <td><?=$data->car_nick_name?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Make</strong></td>
                                                                        <td><?=$data->make?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Model</strong></td>
                                                                        <td><?=$data->model?></td>
                                                                    </tr>                                  
                                                                    <tr>
                                                                        <td><strong>Sub-Model</strong></td>
                                                                        <td><?=$data->submodel?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Year</strong></td>
                                                                        <td><?=$data->year?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Engine</strong></td>
                                                                        <td><?=$data->engine?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Color</strong></td>
                                                                        <td><?=$data->color?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Owner</strong></td>
                                                                        <td><?=$data->owner?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Licence</strong></td>
                                                                        <td><img class="img-fluid" src="<?php if($data->licence!=''){ echo url("public/images/mycar/".$data->licence);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>License Back</strong></td>
                                                                        <td><img class="img-fluid" src="<?php if($data->license_back!=''){ echo url("public/images/mycar/".$data->license_back);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>VIN</strong></td>
                                                                        <td><?=$data->vin?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Insurance</strong></td>
                                                                        <td><img class="img-fluid" src="<?php if($data->insurance!=''){ echo url("public/images/mycar/".$data->insurance);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Fuel Type</strong></td>
                                                                        <td><?=$data->fuel_type?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Car Min Value</strong></td>
                                                                        <td><?=$data->carminvalue?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Car Max Value</strong></td>
                                                                        <td><?=$data->carmaxvalue?></td>
                                                                    </tr>                                        
                                                                    <tr>
                                                                        <td><strong>Notes</strong></td>
                                                                        <td><?=$data->notes?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Millage</strong></td>
                                                                        <td><?=$data->millage?></td>
                                                                    </tr>                             
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- brand --}}
                                        <div class="tab-pane fade" id="nav-brand" role="tabpanel" aria-labelledby="nav-brand-tab">
                                            <div class="col-lg-12">
                                                <div class="white_card card_height_100 mb_20 ">
                                                    <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
                                                    <div class="white_card_body text-left">
                                                        <div class="QA_table ">
                                                            <table class="table p-0"> 
                                                                <?php
                                                                $MycarSpecificationt = App\Models\MycarspecificationEn::where('language_id',1)->where('vin',$data->vin);
                                                                if($MycarSpecificationt->count()!=0){
                                                                    $spec = $MycarSpecificationt->first();
                                                                ?>                                      
                                                                <tr>
                                                                    <td><strong>Trim</strong></td>
                                                                    <td><?=$spec->trim?></td>
                                                                </tr>                                        
                                                                <tr>
                                                                    <td><strong>Style</strong></td>
                                                                    <td><?=$spec->style?></td>
                                                                </tr>                                        
                                                                <tr>
                                                                    <td><strong>Type</strong></td>
                                                                    <td><?=$spec->type?></td>
                                                                </tr>                                        
                                                                <tr>
                                                                    <td><strong>Size</strong></td>
                                                                    <td><?=$spec->size?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Category</strong></td>
                                                                    <td><?=$spec->category?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Made in</strong></td>
                                                                    <td><?=$spec->made_in?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Made in city</strong></td>
                                                                    <td><?=$spec->made_in_city?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Doors</strong></td>
                                                                    <td><?=$spec->doors?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Fuel Type</strong></td>
                                                                    <td><?=$spec->fuel_type?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Fuel Capacity</strong></td>
                                                                    <td><?=$spec->fuel_capacity?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>city_mileage</strong></td>
                                                                    <td><?=$spec->city_mileage?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>highway_mileage</strong></td>
                                                                    <td><?=$spec->highway_mileage?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>engine</strong></td>
                                                                    <td><?=$spec->engine?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>engine_size</strong></td>
                                                                    <td><?=$spec->engine_size?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>engine_cylinders</strong></td>
                                                                    <td><?=$spec->engine_cylinders?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>transmission</strong></td>
                                                                    <td><?=$spec->transmission?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>transmission_short</strong></td>
                                                                    <td><?=$spec->transmission_short?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>transmission_type</strong></td>
                                                                    <td><?=$spec->transmission_type?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>transmission_speeds</strong></td>
                                                                    <td><?=$spec->transmission_speeds?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>drivetrain</strong></td>
                                                                    <td><?=$spec->drivetrain?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>anti_brake_system</strong></td>
                                                                    <td><?=$spec->anti_brake_system?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>steering_type</strong></td>
                                                                    <td><?=$spec->steering_type?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>curb_weight</strong></td>
                                                                    <td><?=$spec->curb_weight?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>gross_vehicle_weight_rating</strong></td>
                                                                    <td><?=$spec->gross_vehicle_weight_rating?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>overall_height</strong></td>
                                                                    <td><?=$spec->overall_height?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>overall_length</strong></td>
                                                                    <td><?=$spec->overall_length?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>overall_width</strong></td>
                                                                    <td><?=$spec->overall_width?></td>
                                                                </tr>
                                                                <?php } ?>                              
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Agent --}}
                                        <div class="tab-pane fade" id="nav-agent" role="tabpanel" aria-labelledby="nav-agent-tab">
                                            <div class="col-lg-12">
                                                <div class="white_card card_height_100 mb_20 ">
                                                    <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
                                                    <div class="white_card_body text-center">
                                                        <div class="QA_table table-responsive pb-3">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Magazine --}}
                                        <div class="tab-pane fade" id="nav-magazine" role="tabpanel" aria-labelledby="nav-magazine-tab">
                                            <div class="col-lg-12">
                                                <div class="white_card card_height_100 mb_20 ">
                                                    <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
                                                    <div class="white_card_body text-center">
                                                        <div class="QA_table table-responsive pb-3">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- tab --}}
                                

                                </div>
                                {{-- tab --}}
                                
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">                                
                                <div class="row">
                                    {{-- tab --}}
                                    
                                    <div class="QA_table ">
                                        <table class="table p-0">
                                            @foreach($MycarService as $value) 
                                            <?php
                                            if($value->service != ''){
                                                $serv = explode(",",$value->service);
                                                $sername = "";
                                                foreach($serv as $se){
                                                    $ser = App\Models\MycarServiceTask::find($se)->name;
                                                    $sername = $sername.','.$sername;;
                                                }
                                                $service_name = $sername;
                                            }else{
                                                $service_name = "";
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <p><strong>{{$value->serviceDate}}</strong> <span>{{$value->serviceCenter}}</span> <span><?php if($value->totalcost!=''){ ?>{{$value->totalcost}} USD<?php } ?></span></p>
                                                    <p><strong>ODO:{{$value->odometer}}</strong> <span>{{$service_name}}</span></p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    {{-- tab --}}
                                

                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Fuel Tank Capacity</strong></td>
                                            <td></td>
                                        </tr>
                                        
                                    </table>
                                </div>
                            </div>                            -->
                        </div>
                        <!-- tab -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.includes.footer')