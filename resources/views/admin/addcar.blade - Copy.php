@include('admin.includes.header')
<style>
  .img-bg {

background-repeat: no-repeat;

background-position: center;

background-size: cover;

position: relative;

padding-bottom: 100%;

}

.block input {
    width: 83%;
    /* display: block; */
    /* width: 100%; */
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    margin-bottom: 13px;
}

.step-circle {
    cursor: pointer;
  }

  .step {
    padding: 40px 0px;
  }

  .block .add {
    background: #fcef86;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
  }

  .block .remove i {
    color: red;
    font-size: 20px;
    padding-left: 15px;
    cursor: pointer;
    vertical-align: middle;
  }
  .upload__img-box {
    height: 100px;
    width: 100px;
    object-fit: cover;
}
.progress i {
  color: #212529;
}
</style>
<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Add Car</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/carlist') }}">Car</a></li>
              <li class="breadcrumb-item active">Add Car</li>
            </ol> -->
        <div class="card p-4">
          <div class="card-body">
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            <!-- <a href="#" class="white_btn3">Create Report</a> -->
        
            <!-- Horizontal Form -->
            <div id="container" class="container">
              <div class="progress my-3" style="height: 3px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="step-container d-flex justify-content-between">
                <div class="step-circle" onclick="displayStep(1)">Vehicle</div>
                <div class="step-circle" onclick="displayStep(2)">Body</div>
                <div class="step-circle" onclick="displayStep(3)">Engine</div>
                <div class="step-circle" onclick="displayStep(4)">Mileage</div>
                <div class="step-circle" onclick="displayStep(5)">Image</div>
                <div class="step-circle" onclick="displayStep(6)">Video</div>
              </div>
              <form id="multi-step-form">
                @csrf
                <div class="step step-1">
                  <!-- Step 1 form fields here -->
                  <h3 class="mb-3">Vehicle</h3>
                  <div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="name">Name</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control name" name="name" id="name" value="<?php if(isset($data->id)){ echo $data->name; } ?>"/>
                      </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                      <div class="col-md-2">
                        <label for="description">Description</label>
                      </div>
                      <div class="col-md-9">
                        <textarea class="form-control" name="description"><?php if(isset($data->id)){ echo $data->description; } ?></textarea>
                      </div>
                    </div>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                  </div>
                </div>
                <div class="step step-2">
                  <!-- Step 2 form fields here -->
                  <h3 class="mb-3">Body</h3>
                  <div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                          <label for="type">Type</label>
                      </div>
                      <div class="col-md-9">
                          <input type="text" class="form-control" name="type" id="type" value="<?php if(isset($data->id)){ echo $body->type; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="doors">Doors</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="doors" id="doors" value="<?php if(isset($data->id)){ echo $body->doors; } ?>"/>
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="seats">Seats</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="seats" id="seats" value="<?php if(isset($data->id)){ echo $body->seats; } ?>"/>
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="length">Length</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="length" id="length" value="<?php if(isset($data->id)){ echo $body->length; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="width">Width</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="width" id="width" value="<?php if(isset($data->id)){ echo $body->width; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Height</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="height" id="height" value="<?php if(isset($data->id)){ echo $body->height; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Wheel Base</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="wheel_base" id="wheel_base" value="<?php if(isset($data->id)){ echo $body->wheel_base; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Front Track</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="front_track" id="front_track" value="<?php if(isset($data->id)){ echo $body->front_track; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Rear Track</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="rear_track" id="rear_track" value="<?php if(isset($data->id)){ echo $body->rear_track; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Ground Clearance</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="ground_clearance" id="ground_clearance" value="<?php if(isset($data->id)){ echo $body->ground_clearance; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Cargo Capacity</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="cargo_capacity" id="cargo_capacity" value="<?php if(isset($data->id)){ echo $body->cargo_capacity; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Max Cargo Capacity</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="max_cargo_capacity" id="max_cargo_capacity" value="<?php if(isset($data->id)){ echo $body->max_cargo_capacity; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Curb Weight</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="curb_weight" id="curb_weight" value="<?php if(isset($data->id)){ echo $body->curb_weight; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Gross Weight</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="gross_weight" id="gross_weight" value="<?php if(isset($data->id)){ echo $body->gross_weight; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Max Payload</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="max_payload" id="max_payload" value="<?php if(isset($data->id)){ echo $body->max_payload; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                      <div class="col-md-2">
                        <label for="">Max Towing Capacity</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="max_towing_capacity" id="max_towing_capacity" value="<?php if(isset($data->id)){ echo $body->max_towing_capacity; } ?>" />
                      </div>
                    </div>                
                  </div>
                  <button type="button" class="btn btn-primary prev-step">Previous</button>
                  <button type="button" class="btn btn-primary next-step">Next</button>
                </div>

                <div class="step step-3">
                  <!-- Step 3 form fields here -->
                  <h3 class="mb-3">Engine</h3>
                  <div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Engine Type</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="engine_type" id="engine_type" value="<?php if(isset($data->id)){ echo $engine->engine_type; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Fuel Type</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="fuel_type" id="fuel_type" value="<?php if(isset($data->id)){ echo $engine->fuel_type; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                      <label for="">Cylinders</label>
                      </div>
                      <div class="col-md-9">
                      <input type="text" class="form-control" name="cylinders" id="cylinders" value="<?php if(isset($data->id)){ echo $engine->cylinders; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Size</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="size" id="size" value="<?php if(isset($data->id)){ echo $engine->size; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Horsepower HP</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="horsepower_hp" id="horsepower_hp" value="<?php if(isset($data->id)){ echo $engine->horsepower_hp; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Horsepower RPM</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="horsepower_rpm" id="horsepower_rpm" value="<?php if(isset($data->id)){ echo $engine->horsepower_rpm; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Torque ft lbs</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="torque_ft_lbs" id="torque_ft_lbs" value="<?php if(isset($data->id)){ echo $engine->torque_ft_lbs; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Torque rpm</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="torque_rpm" id="torque_rpm" value="<?php if(isset($data->id)){ echo $engine->torque_rpm; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Valves</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="valves" id="valves" value="<?php if(isset($data->id)){ echo $engine->valves; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Valve Timing</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="valve_timing" id="valve_timing" value="<?php if(isset($data->id)){ echo $engine->valve_timing; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Cam Type</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="cam_type" id="cam_type" value="<?php if(isset($data->id)){ echo $engine->cam_type; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-2">
                        <label for="">Drive Type</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="drive_type" id="drive_type" value="<?php if(isset($data->id)){ echo $engine->drive_type; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                      <div class="col-md-2">
                        <label for="">Transmission</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="transmission" id="transmission" value="<?php if(isset($data->id)){ echo $engine->transmission; } ?>" />
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary prev-step">Previous</button>
                  <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                <div class="step step-4">
                  <!-- Step 3 form fields here -->
                  <h3 class="mb-3">Mileage</h3>
                  <div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Fuel tank capacity</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="fuel_tank_capacity" id="fuel_tank_capacity" value="<?php if(isset($data->id)){ echo $mileage->fuel_tank_capacity; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Combined mpg</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="combined_mpg" id="combined_mpg" value="<?php if(isset($data->id)){ echo $mileage->combined_mpg; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa city mpg</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_city_mpg" id="epa_city_mpg" value="<?php if(isset($data->id)){ echo $mileage->epa_city_mpg; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa highway mpg</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_highway_mpg" id="epa_highway_mpg" value="<?php if(isset($data->id)){ echo $mileage->epa_highway_mpg; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Range city</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="range_city" id="range_city" value="<?php if(isset($data->id)){ echo $mileage->range_city; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Range highway</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="range_highway" id="range_highway" value="<?php if(isset($data->id)){ echo $mileage->range_highway; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Battery capacity electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="battery_capacity_electric" id="battery_capacity_electric" value="<?php if(isset($data->id)){ echo $mileage->battery_capacity_electric; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa time to charge hr 240v electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_time_to_charge_hr_240v_electric" id="epa_time_to_charge_hr_240v_electric" value="<?php if(isset($data->id)){ echo $mileage->epa_time_to_charge_hr_240v_electric; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa kwh 100 mi electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_kwh_100_mi_electric" id="epa_kwh_100_mi_electric" value="<?php if(isset($data->id)){ echo $mileage->epa_kwh_100_mi_electric; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Range electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="range_electric" id="range_electric" value="<?php if(isset($data->id)){ echo $mileage->range_electric; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa highway mpg electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_highway_mpg_electric" id="epa_highway_mpg_electric" value="<?php if(isset($data->id)){ echo $mileage->epa_highway_mpg_electric; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa city mpg electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_city_mpg_electric" id="epa_city_mpg_electric" value="<?php if(isset($data->id)){ echo $mileage->epa_city_mpg_electric; } ?>" />
                      </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                      <div class="col-md-3">
                        <label for="">Epa combined mpg electric</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" class="form-control" name="epa_combined_mpg_electric" id="epa_combined_mpg_electric" value="<?php if(isset($data->id)){ echo $mileage->epa_combined_mpg_electric; } ?>" />
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary prev-step">Previous</button>
                  <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                <!-- photo -->
                <div class="step step-5">
                  <!-- Step 1 form fields here -->
                  <h3 class="mb-3">Image</h3>
                  <div>
                    <div class="form-group">
                      <div class="upload__box">
                        <div class="upload__btn-box">
                          <div class="row mb-4 align-items-center">
                            <div class="col-md-3">
                              <label class="upload__btn">Interior images</label>
                            </div>
                            <div class="col-md-9">
                              <input type="file" multiple="" data-max_length="20" class="upload__inputfile form-control" name="interiorimg">
                              <div class="upload__img-wrap"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="upload__box">
                        <div class="upload__btn-box">
                          <div class="row mb-4 align-items-center">
                            <div class="col-md-3">
                              <label class="upload__btn"> Exterior images</label>
                            </div>
                            <div class="col-md-9">
                              <input type="file" multiple="" data-max_length="20" class="upload__inputfile form-control" name="exteriorimg">
                              <div class="upload__img-wrap"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="upload__box">
                        <div class="upload__btn-box">
                          <div class="row mb-4 align-items-center">
                            <div class="col-md-3">
                              <label class="upload__btn"> Road Test images </label>
                            </div>
                            <div class="col-md-9">
                              <input type="file" multiple="" data-max_length="20" class="upload__inputfile form-control" name="testimg">
                              <div class="upload__img-wrap"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>                  
                  <button type="button" class="btn btn-primary prev-step">Previous</button>
                  <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                <!-- photo -->
                <!-- video -->
                <div class="step step-6">
                  <!-- Step 1 form fields here -->
                  <h3>Video</h3>
                  <div class="mb-3">
                    <div class="form-group">
                      <label for="" class="mb-2 mt-3">Video URL</label>
                      <div class="optionBox">
                        <?php
                        if(isset($data->id)){
                          foreach($carvideo as $videoval){
                        ?>
                        <div class="block">                        
                          <input type="text" class="videourl" name="videourl[]" value="{{$videoval->video}}" />                            
                          <!-- <span class="remove">Remove Option</span> -->
                        </div>
                        <?php }} ?>
                        <div class="block">                        
                          <input type="text" class="videourl" name="videourl[]" value="" />                            
                          <!-- <span class="remove">Remove Option</span> -->
                        </div>                        
                        <div class="block my-3">
                            <span class="add">Add Option</span>
                        </div>
                      </div>
                       
                    </div>                                       
                  </div>                  
                  <input type="hidden" class="id" name="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                  <input type="hidden" class="bodyid" name="bodyid" value="@if(isset($data->id)) {{$body->id}} @endif">
                  <input type="hidden" class="engineid" name="engineid" value="@if(isset($data->id)) {{$engine->id}} @endif">
                  <input type="hidden" class="mileageid" name="mileageid" value="@if(isset($data->id)) {{$mileage->id}} @endif">
                  <input type="hidden" class="modelID" name="modelID" value="@if(isset($data->id)) {{base64_encode($data->modelID)}} @endif">
                  <button type="button" class="btn btn-primary prev-step">Previous</button>
                  <button type="button" class="btn btn-success submitdatas">Submit</button>
                </div>
                <!-- video -->
                <div class="alert alert-success text-center hide1"><span class="msg_success"></span></div>
                <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span></div>
              </form>
            </div>
            <!-- End Horizontal Form -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('.add').click(function() {
    $('.block:last').before('<div class="block"><input type="text" class="videourl" name="videourl[]" value="" /><span class="remove"><i class="fi fi-br-cross-circle"></i></span></div>');
  });
  $('.optionBox').on('click','.remove',function() {
    $(this).parent().remove();
  });
</script>
<script>
  var currentStep = 1;
var updateProgressBar;

  function displayStep(stepNumber) {
    if (stepNumber >= 1 && stepNumber <= 6) {
      $(".step-" + currentStep).hide();
      $(".step-" + stepNumber).show();
      currentStep = stepNumber;
      updateProgressBar();
    }
  }

  $(document).ready(function() {
    $('#multi-step-form').find('.step').slice(1).hide();
  
    $(".next-step").click(function() {
      if (currentStep < 6) {
        $(".step-" + currentStep).addClass("animate__animated animate__fadeOutLeft");
        currentStep++;
        setTimeout(function() {
          $(".step").removeClass("animate__animated animate__fadeOutLeft").hide();
          $(".step-" + currentStep).show().addClass("animate__animated animate__fadeInRight");
          updateProgressBar();
        }, 500);
      }
    });

    $(".prev-step").click(function() {
      if (currentStep > 1) {
        $(".step-" + currentStep).addClass("animate__animated animate__fadeOutRight");
        currentStep--;
        setTimeout(function() {
          $(".step").removeClass("animate__animated animate__fadeOutRight").hide();
          $(".step-" + currentStep).show().addClass("animate__animated animate__fadeInLeft");
          updateProgressBar();
        }, 500);
      }
    });

    updateProgressBar = function() {
      var progressPercentage = ((currentStep - 1) / 5) * 100;
      $(".progress-bar").css("width", progressPercentage + "%");
    }
  });
</script>
<script>
    $('.submitdatas').click(function(){
        var name  = $('.name').val();        
        var modelID  = $('.modelID').val();        
        if(name==''){
            $('.name').css('border','1px solid red');
        }else{
            $('.name').css('border','');
            $.ajax({
                type:'POST',
                url:'{{url("/admin/insert_car")}}',
                data  :new FormData( $("#multi-step-form")[0] ),
                async   : false,
                cache   : false,
                contentType : false,
                processData : false,
                success:function(data){
                  console.log(data);
                  if($.trim(data)=="1"){
                  $('.hide1').css('display','block');
                  $('.msg_success').text("Sucessfully submitted");
                  $(".alert-success").show('slow' , 'linear').delay(4000).fadeOut(function(){
                      window.location.href="{{URL::to('/admin/carlist?key=')}}"+modelID;
                  });

                  }
                  if($.trim(data)=="2"){
                  $('.hide2').css('display','block');
                  $('.msg_danger').text("Name already exist");
                  $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

                  }
                }
                
            });
        }
    })
</script>

<script>
  jQuery(document).ready(function () {
  ImgUpload();
});

function ImgUpload() {
  var imgWrap = "";
  var imgArray = [];

  $('.upload__inputfile').each(function () {
    $(this).on('change', function (e) {
      imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
      var maxLength = $(this).attr('data-max_length');

      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {

        if (!f.type.match('image.*')) {
          return;
        }

        if (imgArray.length > maxLength) {
          return false
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
          if (len > maxLength) {
            return false;
          } else {
            imgArray.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
              var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
              imgWrap.append(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });

  $('body').on('click', ".upload__img-close", function (e) {
    var file = $(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
    $(this).parent().parent().remove();
  });
}
</script>
<script>

    jQuery(document).ready(function () {

  ImgUpload();

});



// function ImgUpload() {

//   var imgWrap = "";

//   var imgArray = [];



//   $('.upload__inputfile').each(function () {

//     $(this).on('change', function (e) {

//       imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');

//       var maxLength = $(this).attr('data-max_length');



//       var files = e.target.files;

//       var filesArr = Array.prototype.slice.call(files);

//       var iterator = 0;

//       filesArr.forEach(function (f, index) {



//         if (!f.type.match('image.*')) {

//           return;

//         }



//         if (imgArray.length > maxLength) {

//           return false

//         } else {

//           var len = 0;

//           for (var i = 0; i < imgArray.length; i++) {

//             if (imgArray[i] !== undefined) {

//               len++;

//             }

//           }

//           if (len > maxLength) {

//             return false;

//           } else {

//             imgArray.push(f);



//             var reader = new FileReader();

//             reader.onload = function (e) {

//               var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";

//               imgWrap.append(html);

//               iterator++;

//             }

//             reader.readAsDataURL(f);

//           }

//         }

//       });

//     });

//   });



  $('body').on('click', ".upload__img-close", function (e) {

    var file = $(this).parent().data("file");

    for (var i = 0; i < imgArray.length; i++) {

      if (imgArray[i].name === file) {

        imgArray.splice(i, 1);

        break;

      }

    }

    $(this).parent().parent().remove();

  });

}

</script>


@include('admin.includes.footer')