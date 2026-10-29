@include('admin.includes.header')
<style>
    .nav-link {
        color: black;
    }
    .image-border .image {
        text-align: center;
        border: 1px solid #ebebeb;
    }
    .detail_tabs2 .nav-link {
        width: 100px;
    }
    .detail_tabs2 .nav-link.active {
        color: #000000;
        background-color: #efd616;
        border-radius: 5px;
        font-weight: 700;
        border: 1px solid transparent;
        width: 100px;
    }

</style>
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-md-10">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$data->name}} Detail</h4>
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
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Profile</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Favorite Lists</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Car Compare</button>
                            </div>
                        </nav>
                        <div class="tab-content tab_content" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="QA_table table-responsive pb-3">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Name</strong></td>
                                            <td>{{$data->name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender</strong></td>
                                            <td><?php if($data->gender==1){ echo "Male"; }else if($data->gender==2){ echo "Female"; }else if($data->gender==3){ echo "Other"; } ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>{{$data->email}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mobile Number</strong></td>
                                            <td>{{$data->mobile_number}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Birth</strong></td>
                                            <td>{{$data->dob}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Age</strong></td>
                                            <td><?php if($data->dob!=''){ echo $diff = (date('Y') - date('Y',strtotime($data->dob)));}?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Profile Image</strong></td>
                                            <td><img class="img-fluid" src="<?php if($data->profileImage!=''){ echo url("public/images/".$data->profileImage);}else{ ?>../public/img/image-preview.png<?php } ?>" alt style="object-fit: cover; height: 90px; width: 90px;"></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Driver License</strong></td>
                                            <td>
                                                <?php
                                                if($data->DriverLicense!=''){
                                                    $DriverLicense = explode(".",$data->DriverLicense);
                                                    if(end($DriverLicense)=='pdf'){
                                                ?>
                                                    <a href="{{url('public/images/'.$data->DriverLicense)}}"><i class="fa fa-file-pdf" style="font-size: 25px;color: #b70909;"></i></a>
                                                <?php
                                                    }else{
                                                ?>
                                                <img src="<?php echo url("public/images/".$data->DriverLicense); ?>" alt style="object-fit: cover; height: 90px; width: 90px;">
                                                <img src="<?php echo url("public/images/".$data->DriverLicenseback); ?>" alt style="object-fit: cover; height: 90px; width: 90px;">
                                                <?php

                                                    }
                                                }
                                                ?>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>License Expire Date</strong></td>
                                            <td>{{$data->license_expire_date}}</td>
                                        </tr>

                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">                                
                                <div class="row">
                                    {{-- tab --}}
                                    <nav>
                                        <div class="nav nav-tabs mb-3 detail_tabs2" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-car-tab" data-bs-toggle="tab" data-bs-target="#nav-car" type="button" role="tab" aria-controls="nav-car" aria-selected="true">Car</button>
                                            <button class="nav-link" id="nav-brand-tab" data-bs-toggle="tab" data-bs-target="#nav-brand" type="button" role="tab" aria-controls="nav-brand" aria-selected="false">Brand</button>
                                            <button class="nav-link" id="nav-agent-tab" data-bs-toggle="tab" data-bs-target="#nav-agent" type="button" role="tab" aria-controls="nav-agent" aria-selected="false">Agent</button>
                                            <button class="nav-link" id="nav-magazine-tab" data-bs-toggle="tab" data-bs-target="#nav-magazine" type="button" role="tab" aria-controls="nav-magazine" aria-selected="false">Magazine</button>
                                        </div>
                                    </nav>
                                    <div class="tab-content tab_content2 p-3" id="nav-tabContent">
                                        <div class="tab-pane fade active show" id="nav-car" role="tabpanel" aria-labelledby="nav-car-tab">
                                            <div class="col-lg-12">
                                                <div class="white_card card_height_100 mb_20 ">
                                                    <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
                                                    <div class="white_card_body text-center">
                                                        <div class="QA_table table-responsive pb-3">
                                                            <table class="table p-0" id="carexample">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Image</th>
                                                                        <th scope="col">Name</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                $i=1;
                                                                @endphp
                                                                @foreach($favorite as $fev)
                                                                <?php
                                                                    $carimage = App\Models\CarImage::where([
                                                                        'make'=>$fev->make,
                                                                        'model'=>$fev->model,
                                                                        'trim'=>$fev->trim,
                                                                        'year'=>$fev->year,
                                                                    ]);
                                                                    if($carimage->count()==0){
                                                                        $car_image = "";
                                                                    }else{                                                                        
                                                                        if($carimage->first()->image_type==1){
                                                                            $car_image = url('public/images/car/'.$carimage->first()->image);
                                                                        }else{
                                                                            $car_image = $carimage->first()->image;
                                                                        }                                                                        
                                                                    }
                                                                ?>
                                                                <tr>
                                                                    <td>{{$i}}</td>
                                                                    <td><img src="<?php if($car_image!=''){ echo $car_image;}else{ ?>../public/img/image-preview.png<?php } ?>" alt style="object-fit: contain; width: 120px; height:100px;"></td>
                                                                    <td>{{$fev->make}} - {{$fev->model}} - {{$fev->trim}} - {{$fev->year}}</td>
                                                                </tr>
                                                                @php
                                                                $i++;
                                                                @endphp
                                                                @endforeach
                                                                    
                                                                </tbody>
                                                            </table>
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
                                                    <div class="white_card_body text-center">
                                                        <div class="QA_table table-responsive pb-3">
                                                            <table class="table p-0" id="brandexample">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Image</th>
                                                                        <th scope="col">Name</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                $j=1;
                                                                @endphp
                                                                @foreach($brandfavorite as $bfev)
                                                                <?php
                                                                $brandimage = App\Models\Brand::where('id',$bfev->brandID);
                                                                if($brandimage->count()==0){
                                                                    $brand_image = "";
                                                                    $brand_name = "";
                                                                }else{
                                                                    $brand_image = $brandimage->first()->logo;
                                                                    $brand_name = $brandimage->first()->name;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td>{{$j}}</td>
                                                                    <td><img src="<?php if($brand_image!=''){ echo url("public/images/".$brand_image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt style="object-fit: contain; width: 120px; height:100px;"></td>
                                                                    <td>{{$brand_name}}</td>
                                                                </tr>
                                                                @php
                                                                $j++;
                                                                @endphp
                                                                @endforeach
                                                                    
                                                                </tbody>
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
                                                            <table class="table p-0" id="agentexample">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Image</th>
                                                                        <th scope="col">Name</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                $k=1;
                                                                @endphp
                                                                @foreach($agentfavorite as $afev)
                                                                <?php
                                                                $agentdata = App\Models\Agent::where('id',$afev->agentID);
                                                                if($agentdata->count()==0){
                                                                    $agent_image = "";
                                                                    $agent_name = "";
                                                                }else{
                                                                    $agent_image = $agentdata->first()->logo;
                                                                    $agent_name = $agentdata->first()->name;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td>{{$k}}</td>
                                                                    <td><img src="<?php if($agent_image!=''){ echo url("public/images/".$agent_image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt style="object-fit: contain; width: 120px; height:100px;"></td>
                                                                    <td>{{$agent_name}}</td>
                                                                </tr>
                                                                @php
                                                                $k++;
                                                                @endphp
                                                                @endforeach
                                                                    
                                                                </tbody>
                                                            </table>
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
                                                            <table class="table p-0" id="newsexample">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Image</th>
                                                                        <th scope="col">Name</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                $l=1;
                                                                @endphp
                                                                @foreach($newsfavorite as $nfev)
                                                                <?php
                                                                $newsdata = App\Models\Magazine::where('id',$nfev->newsID);
                                                                if($newsdata->count()==0){
                                                                    $news_image = "";
                                                                    $news_name = "";
                                                                }else{
                                                                    $news_image = $newsdata->first()->image;
                                                                    $news_name = $newsdata->first()->heading;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td>{{$l}}</td>
                                                                    <td><img src="<?php if($news_image!=''){ echo url("public/images/".$news_image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt style="object-fit: contain; width: 120px; height:100px;"></td>
                                                                    <td>{{$news_name}}</td>
                                                                </tr>
                                                                @php
                                                                $l++;
                                                                @endphp
                                                                @endforeach
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- tab --}}
                                

                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="col-lg-12">
                                    <div class="white_card card_height_100 mb_20 ">
                                        <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
                                        <div class="white_card_body text-center">
                                            <div class="QA_table table-responsive pb-3">
                                                <table class="table p-0" id="CarCompareByUser">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Car Name</th>                                                                    
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $g=1;
                                                    @endphp
                                                    @foreach($CarCompareByUser as $CarCompareByUservalue)
                                                    <?php
                                                        $car = explode(",",$CarCompareByUservalue->carID);
                                                        $carname = "";
                                                        foreach($car as $carvalue){
                                                           $cname = App\Models\Vehicle::find($carvalue)?->name;
                                                            $carname .= ", ".$cname;
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td>{{$g}}</td>
                                                        <td>{{trim($carname,",")}}</td>
                                                    </tr>
                                                    @php
                                                    $g++;
                                                    @endphp
                                                    @endforeach
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- tab -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
       $('#newsexample').DataTable({
          dom: 'Bfrtip',
          buttons: [{
             extend: 'excel',
             text: 'Export', 
             exportOptions: {
                 columns: [ 0, 2]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return '<?=$data->name?> Magazine Favorite List' + n;
             },
             title:'<?=$data->name?> Magazine Favorite List'
          }],
       });
    });
 </script>
 <script>
    $(document).ready(function() {
       $('#carexample').DataTable({
          dom: 'Bfrtip',
          buttons: [{
             extend: 'excel',
             text: 'Export', 
             exportOptions: {
                 columns: [ 0, 2]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return '<?=$data->name?> Car Favorite List' + n;
             },
             title:'<?=$data->name?> Car Favorite List'
          }],
       });
    });
 </script>
 <script>
    $(document).ready(function() {
       $('#brandexample').DataTable({
          dom: 'Bfrtip',
          buttons: [{
             extend: 'excel',
             text: 'Export', 
             exportOptions: {
                 columns: [ 0, 2]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return '<?=$data->name?> Brand Favorite List' + n;
             },
             title:'<?=$data->name?> Brand Favorite List'
          }],
       });
    });
 </script>
 <script>
    $(document).ready(function() {
       $('#agentexample').DataTable({
          dom: 'Bfrtip',
          buttons: [{
             extend: 'excel',
             text: 'Export', 
             exportOptions: {
                 columns: [ 0, 2]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return '<?=$data->name?> Agent Favorite List' + n;
             },
             title:'<?=$data->name?> Agent Favorite List'
          }],
       });
    });
 </script>
 <script>
    $(document).ready(function() {
       $('#CarCompareByUser').DataTable({
          dom: 'Bfrtip',
          buttons: [{
             extend: 'excel',
             text: 'Export', 
             exportOptions: {
                 columns: [ 0, 1]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return '<?=$data->name?> CarCompareByUser' + n;
             },
             title:'<?=$data->name?> Car Compare '
          }],
       });
    });
 </script>

@include('admin.includes.footer')