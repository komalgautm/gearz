@include('admin.includes.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/360.js@2.0.0/dist/360.min.css">
<style>
    .nav-link {
        color: black;
    }
    .nav-link:hover {
        color: #fae80b;
    }
    a-scene.fullscreen .a-canvas {
        position: relative !important;
    }
   
    .img-div {
        position: relative;
        /* width: 46%; */
        float:left;
    }

    .image {
        opacity: 1;
        display: block;
        width: 100%;
        max-width: auto;
        transition: .5s ease;
        backface-visibility: hidden;
        object-fit: unset !important;
        height: auto !important;
    }

    .middle {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .img-div:hover .image {
        opacity: 0.3;
    }

    .img-div:hover .middle {
        opacity: 1;
    }
    
    .containerCar {
        width: 100%;
        height: 100%;
        /* max-width: 726px; */
        min-width: 320px;
        margin: 0 auto;
    }
    .img-container {
        border: 1px solid #ccc;
        width: 100%;
        /* max-width:640px; */
        margin: 0 auto;
        padding:0;
        cursor: ew-resize;
    }
    .img-container .img360 {
        width: 100%;
        margin: 0;
        padding: 0;
        line-height: 0;
    }
    .img-container img {
        width: 100%;
        margin: 0;
        padding: 0;
    }
    #preload-imgs {
        display:none;
    }
    .card-body .img-container img {
        object-fit: contain;
        height:    auto;
        width: 100%;
    }
</style>

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-md-12">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Car Details</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Car Detail</li>
                </ol> -->
                <div class="card p-4">
                    <div class="card-body">
                        @if(Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
                        @endif
                        @if(Session::has('error'))
                        <p class="alert alert-danger"><span style="font-weight: 600;"> Failed !! </span>{{ Session::get('error') }}</p>
                        @endif
                        <p class="alert alert-success" id="deleteId" style="display: none"><span style="font-weight: 600;"> Success !! </span>Deleted successfully</p>
                        <p class="alert alert-success" id="updateSequenceId" style="display: none"><span style="font-weight: 600;"> Success !! </span>360 Image Sequence updated successfully</p>
                        <!-- <a href="{{ url('admin/addBrand') }}" class="white_btn3">Create</a> -->
                        <!-- <button type="button" class="white_btn3" data-toggle="modal" data-target="#myModal"></button> -->
                        <!-- Button trigger modal -->
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Performance</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Safe & Efficiency</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Safety</button>
                                <button class="nav-link" id="nav-color-tab" data-bs-toggle="tab" data-bs-target="#nav-color" type="button" role="tab" aria-controls="nav-color" aria-selected="false">Comfort & Tech</button>
                                <button class="nav-link" id="nav-design-tab" data-bs-toggle="tab" data-bs-target="#nav-design" type="button" role="tab" aria-controls="nav-design" aria-selected="false">Design</button>
                                <button class="nav-link" id="nav-owner-tab" data-bs-toggle="tab" data-bs-target="#nav-owner" type="button" role="tab" aria-controls="nav-owner" aria-selected="false">Ownership</button>
                                <button class="nav-link" id="nav-image-tab" data-bs-toggle="tab" data-bs-target="#nav-image" type="button" role="tab" aria-controls="nav-image" aria-selected="false">Images</button>
                                <button class="nav-link" id="nav-video-tab" data-bs-toggle="tab" data-bs-target="#nav-video" type="button" role="tab" aria-controls="nav-video" aria-selected="false">Videos</button>
                                <button class="nav-link" id="nav-360-tab" data-bs-toggle="tab" data-bs-target="#nav-360" type="button" role="tab" aria-controls="nav-360" aria-selected="false">360 View</button>
                            </div>
                        </nav>
                        <div class="tab-content p-3 border" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Make Name</strong></td>
                                            <td>{{$data['Make']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Model Name</strong></td>
                                            <td>{{$data['Model']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trim</strong></td>
                                            <td>{{$data['Trim']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Year</strong></td>
                                            <td>{{$data['Model_Year']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Engine Type</strong></td>
                                            <td>{{$data['Engine_Type']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Engine Displacement (cc)</strong></td>
                                            <td>{{$data['Engine_Displacement_(cc)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Transmission Type</strong></td>
                                            <td>{{$data['Transmission_Type']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cylinders</strong></td>
                                            <td>{{$data['Cylinders']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Top Speed (km/h)</strong></td>
                                            <td>{{$data['Top_Speed_(kmh)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Number of Gears</strong></td>
                                            <td>{{$data['Number_of_Gears']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Horsepower (hp)</strong></td>
                                            <td>{{$data['Horsepower_(hp)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Torque (Nm)</strong></td>
                                            <td>{{$data['Torque_(Nm)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Towing Capacity (kg)</strong></td>
                                            <td>{{$data['Towing_Capacity_(kg)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Drivetrain</strong></td>
                                            <td>{{$data['Drivetrain']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>0-100 km/h (seconds)</strong></td>
                                            <td>{{$data['0-100_kmh_(seconds)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fuel Type</strong></td>
                                            <td>{{$data['Fuel_Type']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Charging Time (hours)</strong></td>
                                            <td>{{$data['Charging_Time_(hours)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>CO2 Emissions (g/km)</strong></td>
                                            <td>{{$data['CO2_Emissions_(gkm)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Emission Standard</strong></td>
                                            <td>{{$data['Emission_Standard']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Electric Range (km)</strong></td>
                                            <td>{{$data['Electric_Range_(km)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Battery & Group Size</strong></td>
                                            <td>{{$data['Battery_&_Group_Size']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Battery Health Indicator</strong></td>
                                            <td>{{$data['Battery_Health_Indicator']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Wheelbase (mm)</strong></td>
                                            <td>{{$data['Wheelbase_(mm)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Height (mm)</strong></td>
                                            <td>{{$data['Height_(mm)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Length (mm)</strong></td>
                                            <td>{{$data['Length_(mm)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Width (mm)</strong></td>
                                            <td>{{$data['Width_(mm)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fuel Tank Capacity (L)</strong></td>
                                            <td>{{$data['Fuel_Tank_Capacity_(L)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fuel Economy Avg (km/L)</strong></td>
                                            <td>{{$data['Fuel_Economy_Combined_(kmL)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gross Vehicle Weight (kg)</strong></td>
                                            <td>{{$data['Gross_Vehicle_Weight_(kg)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cargo Volume (L)</strong></td>
                                            <td>{{$data['Cargo_Volume_(L)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Turning Circle (m)</strong></td>
                                            <td>{{$data['Turning_Circle_(m)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ground Clearance (mm)</strong></td>
                                            <td>{{$data['Ground_Clearance_(mm)']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Safety Rating</strong></td>
                                            <td>{{$data['Safety_Rating']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Automatic Emergency Braking</strong></td>
                                            <td>{{$data['Automatic_Emergency_Braking']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Number of Airbags</strong></td>
                                            <td>{{$data['Number_of_Airbags']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Blind Spot Monitoring</strong></td>
                                            <td>{{$data['Blind_Spot_Monitoring']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Parking Assist / Rear Camera</strong></td>
                                            <td>{{$data['Parking_Assist__Rear_Camera']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Adaptive Cruise Control</strong></td>
                                            <td>{{$data['Adaptive_Cruise_Control']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Lane Keeping Assist</strong></td>
                                            <td>{{$data['Lane_Keeping_Assist']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rain-Sensing Wipers</strong></td>
                                            <td>{{$data['Rain-Sensing_Wipers']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-color" role="tabpanel" aria-labelledby="nav-color-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Apple CarPlay / Android Auto</strong></td>
                                            <td>{{$data['Apple_CarPlay__Android_Auto']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Digital Instrument Cluster / Display Size</strong></td>
                                            <td>{{$data['Digital_Instrument_Cluster__Display_Size']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dual-Zone / Tri-Zone Climate Control</strong></td>
                                            <td>{{$data['Dual-Zone__Tri-Zone_Climate_Control']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Information system Size</strong></td>
                                            <td>{{$data['Information_system_Size']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Number of USB Ports</strong></td>
                                            <td>{{$data['Number_of_USB_Ports']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remote Start / Keyless Entry</strong></td>
                                            <td>{{$data['Remote_Start__Keyless_Entry']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Number of Speakers</strong></td>
                                            <td>{{$data['Number_of_Speakers']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Power Tailgate</strong></td>
                                            <td>{{$data['Power_Tailgate']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sound System Brand & Specs</strong></td>
                                            <td>{{$data['Sound_System_Brand_&_Specs']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Wireless Charging</strong></td>
                                            <td>{{$data['Wireless_Charging']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Seat Material</strong></td>
                                            <td>{{$data['Seat_Material']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sunroof / Panoramic Roof</strong></td>
                                            <td>{{$data['Sunroof__Panoramic_Roof']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Seating Capacity</strong></td>
                                            <td>{{$data['Seating_Capacity']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Interior Color Combinations</strong></td>
                                            <td>{{$data['Interior_Color_Combinations']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fold-Flat Rear Seats / Split Ratio</strong></td>
                                            <td>{{$data['Fold-Flat_Rear_Seats__Split_Ratio']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Heated/Ventilated Seats</strong></td>
                                            <td>{{$data['HeatedVentilated_Seats']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ambient Lighting</strong></td>
                                            <td>{{$data['Ambient_Lighting']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fog Lights</strong></td>
                                            <td>{{$data['Fog_Lights']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-design" role="tabpanel" aria-labelledby="nav-design-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Wheel Size (inches)</strong></td>
                                            <td>{{$data['Wheel_Size_(inches)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tire Size</strong></td>
                                            <td>{{$data['Tire_Size']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Wheel Type</strong></td>
                                            <td>{{$data['Wheel_Type']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Taillights Type</strong></td>
                                            <td>{{$data['Taillights_Type']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Headlights Type</strong></td>
                                            <td>{{$data['Headlights_Type']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Side Mirror Functions</strong></td>
                                            <td>{{$data['Side_Mirror_Functions']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Available Packages</strong></td>
                                            <td>{{$data['Available_Packages']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-owner" role="tabpanel" aria-labelledby="nav-owner-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Owner Ratings</strong></td>
                                            <td>{{$data['Owner_Ratings']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Average Resale Value (5 years)</strong></td>
                                            <td>{{$data['Average_Resale_Value_(5_years)']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Parts Availability Index</strong></td>
                                            <td>{{$data['Parts_Availability_Index']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Common Problems/Recalls</strong></td>
                                            <td>{{$data['Common_ProblemsRecalls']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab">
                                <div class="row justify-content-center mb-5">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <label class="col-form-label mb-0">Add Images</label>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ url('admin/upload_car_images') }}" method="post" enctype="multipart/form-data">
                                                    @csrf

                                                    <input type="hidden" name="brand_id" value="{{ $brand_id }}">
                                                    <input type="hidden" name="make" value="{{ $make }}">
                                                    <input type="hidden" name="model" value="{{ $model }}">
                                                    <input type="hidden" name="trim" value="{{ $trim }}">
                                                    <input type="hidden" name="year" value="{{ $year }}">

                                                    <label for="imageInput" class="col-form-label mb-0">Upload Image</label>
                                                    <input type="file" class="form-control @error('carimages.*') is-invalid @enderror" 
                                                        name="carimages[]" id="imageInput" accept="image/*" multiple required>

                                                    <span class="text-danger d-block mt-1"><b>Note:</b> Recommended image size is 16:9 aspect ratio (e.g., 1280×720 pixels). Maximum file size: 2MB per image.</span>

                                                    <input type="submit" class="btn btn-primary mt-2" value="Submit">
                                                    
                                                    @error('carimages.*')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row justify-content-center text-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <label class="col-form-label mb-0">Uploaded Image [ {{count($carimages)}} ]</label>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($carimages as $i => $carimage)
                                                        <div class='img-div col-md-3'>
                                                            <img src="{{ url('public/images/' . $carimage->image) }}" class='img-responsive image img-thumbnail' title='Car Image'>
                                                            <div class='middle'>
                                                                <button id='action-icon' class='btn' type="button" onclick="carimagedelete({{ $carimage->id }})">
                                                                    <i class='fa fa-trash' title="Delete"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-center mb-5">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <label class="col-form-label mb-0">Fetch Images From API</label>
                                            </div>
                                            @if(count($apiTrimList) > 0)
                                            <div class="card-body">
                                                <input type="hidden" name="brand_id" value="{{ $brand_id }}">
                                                <input type="hidden" name="fetchapimake" value="{{ $make }}">
                                                <input type="hidden" name="fetchapimodel" value="{{ $model }}">
                                                <input type="hidden" name="fetchapiyear" value="{{ $year }}">
                                                <!-- <input type="hidden" name="fetchapimake" value="BMW">
                                                <input type="hidden" name="fetchapimodel" value="X5">
                                                <input type="hidden" name="fetchapiyear" value="2004"> -->
                                                
                                                <label for="trim" class="col-form-label mb-0">Select Trim</label>
                                                <select name="trim" id="trim" class="form-control" required>
                                                    <option selected disabled style="display: none;">--Select Trim--</option>
                                                    @foreach($apiTrimList as $trimData)
                                                    <option value="{{ $trimData->trim }}">{{ $trimData->trim }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="submit" class="btn btn-primary mt-2" id="submitfetchImageBtn" value="Submit">
                                            </div>
                                            @else
                                            <div class="card-body text-center">
                                                <p class='text-danger'>No trim found.</p>
                                            </div>
                                            @endif
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                @if(count($apiTrimList) > 0)
                                <div class="row justify-content-center text-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <label class="col-form-label mb-0">API Stored Image</label>
                                                <!-- <a href="" class="btn btn-primary">Refresh API Data</a> -->
                                            </div>
                                            <div class="card-body">
                                                <div id="car-images" class="row">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- <div class="row">
                                    @php 
                                    $num = 8;
                                    @endphp
                                    @for($i=0;$i<$num;$i++)
                                    <div class="col-md-6 mb-3">
                                        <img src="{{ url('public/img/1714826677.jpeg') }}" style="height: 100%" alt="Image">
                                    </div>
                                    @endfor
                                </div> -->
                            </div>
                            <div class="tab-pane fade" id="nav-video" role="tabpanel" aria-labelledby="nav-video-tab">
                                <div class="row justify-content-center mb-5">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <label class="col-form-label mb-0">Add Videos</label>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ url('admin/upload_car_videos') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="brand_id" value="{{ $brand_id }}">
                                                    <input type="hidden" name="make" value="{{ $make }}">
                                                    <input type="hidden" name="model" value="{{ $model }}">
                                                    <input type="hidden" name="trim" value="{{ $trim }}">
                                                    <input type="hidden" name="year" value="{{ $year }}">
                                                    
                                                    <label for="imageInput" class="col-form-label">Video</label>
                                                    <input type="file" class="form-control" name="carvideo" id="imageInput" accept="video/mp4" required>
                                                    <span class="text-danger"><b>Note : </b>Recommended size will be 3:2</span><br>
                                                    <label for="description" class="col-form-label">Description</label>
                                                    <textarea name="description" class="form-control" id="description" required></textarea>
                                                    <input type="submit" class="btn btn-primary mt-2" id="submitVideoBtn" value="Submit">
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row justify-content-center text-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <label class="col-form-label mb-0">Uploaded Video [ {{count($carvideos)}} ]</label>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($carvideos as $i => $carvideo)
                                                        <div class='img-div col-md-3'>
                                                            <video width="100%" height="200" class="image" controls autoplay muted playsinline>
                                                                <source src="{{ url('public/videos/' . $carvideo->video) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                            <div class='middle mt-2'>
                                                                <button id='action-icon' class='btn' type="button" onclick="carvideodelete({{ $carvideo->id }})">
                                                                    <i class='fa fa-trash' title="Delete"></i>
                                                                </button>
                                                            </div>
                                                            <p>{{$carvideo->description}}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    @php 
                                    $num = 8;
                                    @endphp
                                    @for($i=0;$i<$num;$i++)
                                    <div class="col-md-6 mb-3">
                                        <video src="{{ url('public/video/intro_video/intro_video_1749448316.mp4') }}" controls autoplay muted playsinline width="430">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    @endfor
                                </div> -->
                            </div>
                            <div class="tab-pane fade" id="nav-360" role="tabpanel" aria-labelledby="nav-360-tab">
                                <button class="btn btn-primary" id="btnUploadImage">Upload Images</button>
                                <div class="row justify-content-center mb-5" id="uploadImageSection" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header text-center">
                                                <label class="col-form-label mb-0">Upload 360 View Images</label>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ url('admin/upload_car_360_image') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="brand_id" value="{{ $brand_id }}">
                                                    <input type="hidden" name="make" value="{{ $make }}">
                                                    <input type="hidden" name="model" value="{{ $model }}">
                                                    <input type="hidden" name="trim" value="{{ $trim }}">
                                                    <input type="hidden" name="year" value="{{ $year }}">
                                                    <div class="row">
                                                        @php $n = 0; @endphp
                                                        @for ($i = 0; $i < 360; $i += 10)
                                                            <div class="col-md-4 mb-3">
                                                                <label class="col-form-label">{{ $i }}° Image</label>
                                                                <input type="hidden" name="car360_images[{{$n}}][sequence]" value="{{ $n }}">
                                                                <input type="file" class="form-control" name="car360_images[{{$n}}][image]" accept="image/*">
                                                                
                                                                @php
                                                                    $matchedImage = $car360Images->firstWhere('sequence', $n);
                                                                @endphp

                                                                @if ($matchedImage)
                                                                <div class='img-div'>
                                                                    <img src="{{ url('public/images/' . $matchedImage->image) }}" class="img-responsive img-thumbnail mt-2" style="height: auto;" title="Car 360 Image">
                                                                </div>
                                                                @endif
                                                            </div>
                                                            @php $n++; @endphp
                                                        @endfor
                                                    </div>
                                                    <span class="text-danger"><b>Note : </b>Please upload 36 high-quality images showing the car from all angles (every 10° recommended). Recommended size will be 3:2.</span><br>
                                                    <input type="submit" class="btn btn-primary mt-2" id="submit360Btn" value="Submit">
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul class="mb-0">
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <!-- <div class="row justify-content-center text-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <label class="col-form-label mb-0">Uploaded 360 Image [ {{count($car360Images)}} ]</label>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($car360Images as $i => $car360Image)
                                                        <div class='img-div col-md-3'>
                                                            <select name="updateSequence" id="updateSequence{{ $car360Image->id }}" title="You can update sequence from here" class="form-control" onchange="updateSequence({{ $car360Image->id }})">
                                                                @foreach ($car360Images as $value)
                                                                <option value="{{ $value->sequence }}" @if($car360Image->sequence == $value->sequence) selected @endif>{{ $value->sequence }}</option>
                                                                @endforeach
                                                            </select>
                                                            <img src="{{ url('public/images/' . $car360Image->image) }}" class='img-responsive image img-thumbnail' title='Car 360 Image'>
                                                            <div class='middle'>
                                                                <button id='action-icon' class='btn' type="button" onclick="car360imagedelete({{ $car360Image->id }})">
                                                                    <i class='fa fa-trash' title="Delete"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function updateSequence(id){
                                                                var sequence = $('#updateSequence'+id).val();
                                                                var token = "<?=csrf_token()?>";
                                                                $.ajax({
                                                                    type:'POST',
                                                                    url:'{{url("/admin/updateSequence")}}',
                                                                    data  :{id:id,sequence:sequence,_token:token},
                                                                    success:function(data){
                                                                        console.log(data);
                                                                        if ($.trim(data) == "1") {
                                                                            localStorage.setItem("open360Tab", "true");
                                                                            $('#updateSequenceId').css('display', 'block');
                                                                            $('html, body').animate({
                                                                                scrollTop: $('#updateSequenceId').offset().top - 300
                                                                            }, 500);
                                                                            setTimeout(() => {
                                                                                location.reload();
                                                                            }, 3000);
                                                                        }
                                                                    }

                                                                });
                                                            }
                                                        </script>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row justify-content-center text-center">
                                    <div class="col-md-12">
                                        <!-- <h3>Click to show 360 View</h3>
                                        <i class="fa fa-arrow-down"></i> -->
                                        <div class="card">
                                            <!-- <div class="card-header">
                                                <label class="col-form-label mb-0">360 View</label>
                                            </div> -->
                                            <div class="card-body">
                                                <!-- <div class="row"> -->
                                                    <!-- <div class="containerCar">
                                                        <div class="img-container">
                                                            <div class="img360">
                                                            <img id="myImg" src="{{ url('public/images')}}" />
                                                            </div>
                                                        </div>
                                                        <div id="preload-imgs"></div>
                                                    </div> -->
                                                    <!-- <iframe src="https://assets.spyne.ai/360?sku_id=789573a8cd214d80a17a7cac7fbc43cb&showHotspot=true" height="484px" width="100%" title="Iframe Example"></iframe> -->
                                                    <iframe src="{{ $url360 }}" height="484px" width="100%" title="Iframe Example"></iframe>
                                                    <!-- <div class="containerCar">
                                                        <div class="img-container">
                                                            <div class="img360">
                                                                <img id="myImg" src="{{ asset('public/images/' . ($car360Images[0]->image ?? 'default.jpg')) }}" />
                                                            </div>
                                                        </div>
                                                        <div id="preload-imgs" style="display: none;">
                                                            @foreach ($car360Images as $img)
                                                                <img src="{{ asset('public/images/' . $img->image) }}">
                                                            @endforeach
                                                        </div>
                                                    </div> -->
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- <a-scene embedded vr-mode-ui="enabled: false" style="height: 400px; width: 100%;">
                                    <a-sky src="{{ url('public/img/1714826677.jpeg') }}"></a-sky>
                                </a-scene> -->
                            </div>
                        </div>
                        <!-- tab -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal (only once on page) -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center p-3">
        <img id="modalImage" src="" class="img-fluid" style="max-height: 80vh;" alt="Large Image">
        </div>
    </div>
</div>
<script src="https://aframe.io/releases/0.8.0/aframe.min.js"></script>
<script>
    document.getElementById("nav-360-tab").addEventListener("click", function () {
    setTimeout(() => {
        const scene = document.querySelector('a-scene');
        if (scene && scene.hasLoaded) {
            scene.resize();
        }
    }, 300); // slight delay to let DOM render
});
</script>
<script>
    $('#submitBtn').click(function(){
        localStorage.setItem("openImageTab", "true");
    });
    $('#submitVideoBtn').click(function(){
        localStorage.setItem("openVideoTab", "true");
    });
    $('#submit360Btn').click(function(){
        localStorage.setItem("open360Tab", "true");
    });
  function carimagedelete(th){
    var id = th;
    var token = "<?=csrf_token()?>";
    $.ajax({
        type:'POST',
        url:'{{url("/admin/deleteimg")}}',
        data  :{id:id,_token:token},
        success:function(data){
            console.log(data);
            if ($.trim(data) == "1") {
                localStorage.setItem("openImageTab", "true");
                $('#deleteId').css('display', 'block');
                $('html, body').animate({
                    scrollTop: $('#deleteId').offset().top - 300
                }, 500);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
            if ($.trim(data) == "0") {
                alert('something went wrong');
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        }

    });
  }
  function carvideodelete(th){
    var id = th;
    var token = "<?=csrf_token()?>";
    $.ajax({
        type:'POST',
        url:'{{url("/admin/deletevideo")}}',
        data  :{id:id,_token:token},
        success:function(data){
            console.log(data);
            if ($.trim(data) == "1") {
                localStorage.setItem("openVideoTab", "true");
                $('#deleteId').css('display', 'block');
                $('html, body').animate({
                    scrollTop: $('#deleteId').offset().top - 300
                }, 500);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
            if ($.trim(data) == "0") {
                alert('something went wrong');
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        }

    });
  }
  function car360imagedelete(th){
    var id = th;
    var token = "<?=csrf_token()?>";
    $.ajax({
        type:'POST',
        url:'{{url("/admin/deleteimg")}}',
        data  :{id:id,_token:token},
        success:function(data){
            console.log(data);
            if ($.trim(data) == "1") {
                localStorage.setItem("open360Tab", "true");
                $('#deleteId').css('display', 'block');
                $('html, body').animate({
                    scrollTop: $('#deleteId').offset().top - 300
                }, 500);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
            if ($.trim(data) == "0") {
                alert('something went wrong');
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        }

    });
  }
  $(document).ready(function () {
    if (localStorage.getItem("openImageTab") === "true") {
        $('#nav-image-tab').trigger('click');
        localStorage.removeItem("openImageTab");
    }
    if (localStorage.getItem("openVideoTab") === "true") {
        $('#nav-video-tab').trigger('click');
        localStorage.removeItem("openVideoTab");
    }
    if (localStorage.getItem("open360Tab") === "true") {
        $('#nav-360-tab').trigger('click');
        localStorage.removeItem("open360Tab");
    }
  });
</script>

@include('admin.includes.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.19/jquery.touchSwipe.min.js"></script>

<!-- <script>
$(function() {
    const numImages = 36;
    let currentIndex = 1;

    // Preload all the images
    for (let i = 1; i <= numImages; i++) {
        const img = document.createElement('img');
        img.src = `http://woosterwebdesign.com/experiments/images/car_slides/car_(${i}).jpg`;
        document.getElementById('preload-imgs').appendChild(img);
    }

    // Swipe options
    $(".img-container").swipe({
        triggerOnTouchEnd: true,
        swipeStatus: function(event, phase, direction, distance) {
            if (phase === "move" || phase === "end") {
                if (direction === "left") {
                    nextImage();
                } else if (direction === "right") {
                    prevImage();
                }
            }
        },
        allowPageScroll: "vertical",
        threshold: 75
    });

    function nextImage() {
        currentIndex++;
        if (currentIndex > numImages) currentIndex = 1;
        updateImage();
    }

    function prevImage() {
        currentIndex--;
        if (currentIndex < 1) currentIndex = numImages;
        updateImage();
    }

    function updateImage() {
        document.getElementById("myImg").src = `http://woosterwebdesign.com/experiments/images/car_slides/car_(${currentIndex}).jpg`;
    }
});
</script> -->
<script>
    $(function() {
        const images = @json($car360Images->pluck('image'));
        let currentIndex = 0;

        function updateImage() {
            $('#myImg').attr('src', "{{ asset('public/images') }}/" + images[currentIndex]);
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            updateImage();
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateImage();
        }

        $(".img-container").swipe({
            triggerOnTouchEnd: true,
            swipeStatus: function(event, phase, direction, distance) {
                if (phase === "move" || phase === "end") {
                    if (direction === "left") nextImage();
                    else if (direction === "right") prevImage();
                }
            },
            allowPageScroll: "vertical",
            threshold: 75
        });

        updateImage(); // Show first image
    });
</script>
<script>
$(document).ready(function() {
    $('#submitfetchImageBtn').on('click', function(e) {
        e.preventDefault();

        let make = $('input[name="fetchapimake"]').val();
        let model = $('input[name="fetchapimodel"]').val();
        let year = $('input[name="fetchapiyear"]').val();
        let trim = $('#trim').val();

        $.ajax({
            url: "{{ route('fetch.api.car.images') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                make: make,
                model: model,
                year: year,
                trim: trim
            },
            beforeSend: function() {
                $('#submitfetchImageBtn').val('Loading...');
            },
            success: function(response) {
                $('#submitfetchImageBtn').val('Submit');
                $('#car-images').empty();

                if (response.images && response.images.length > 0) {
                    response.images.forEach(function(url) {
                        $('#car-images').append(`
                            <div class='col-md-3 mb-3'>
                                <img src="${url}" class="img-responsive image img-thumbnail"
                                    title="Click to view in Zoom"
                                    data-bs-toggle="modal" data-bs-target="#imageModal"
                                    onclick="showImageInModal('${url}')">
                            </div>
                        `);
                    });
                } else {
                    $('#car-images').html("<p class='text-danger'>No images found.</p>");
                }
            },
            error: function(xhr) {
                $('#submitfetchImageBtn').val('Submit');
                alert("Error fetching images. Please try again.");
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
<script>
    function showImageInModal(imageUrl) {
        $('#modalImage').attr('src', imageUrl);
    }
    $('#btnUploadImage').click(function (){
        $('#uploadImageSection').toggle();
    });
</script>



