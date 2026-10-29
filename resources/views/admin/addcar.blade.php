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
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
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
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button"
                        title="Back"><i class="fa fa-arrow-left"></i></a> Add Car</h4>
                <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/carlist') }}">Car</a></li>
              <li class="breadcrumb-item active">Add Car</li>
            </ol> -->
                <div class="card p-4">
                    <div class="card-body">
                        @if (Session::has('message'))
                            <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                                </span>{{ Session::get('message') }}</p>
                        @endif
                        <!-- <a href="#" class="white_btn3">Create Report</a> -->

                        <!-- Horizontal Form -->
                        <div id="container" class="container">
                            <div class="progress my-3" style="height: 3px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="step-container d-flex justify-content-between">
                                <div class="step-circle" onclick="displayStep(1)">Vehicle</div>
                                <div class="step-circle" onclick="displayStep(2)">Engine</div>
                                <div class="step-circle" onclick="displayStep(3)">Mileage</div>
                                <div class="step-circle" onclick="displayStep(4)">Color</div>
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
                                            <div class="col-md-6">
                                                <label for="name" class="col-form-label">Name (English)</label>
                                                <input type="text" class="form-control name" name="name"
                                                    id="name" value="<?php if (isset($data->id)) {
                                                        echo $data->name;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="name_ar" class="col-form-label">Name (Arabic)</label>
                                                <input type="text" class="form-control name_ar" name="name_ar"
                                                    id="name_ar" value="<?php if (isset($data->id)) {
                                                        echo $data->name_ar;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        {{--  --}}
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="year" class="col-form-label">Year</label>
                                                <input type="text" class="form-control year" name="year"
                                                    id="year" value="<?php if (isset($data->id)) {
                                                        echo $data->year;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="numberOfDoors" class="col-form-label">Number Of
                                                    Doors</label>
                                                <input type="text" class="form-control numberOfDoors"
                                                    name="numberOfDoors" id="numberOfDoors"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->numberOfDoors;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="fuelTypeToDisplay" class="col-form-label">Fuel Type</label>
                                                <input type="text" class="form-control fuelTypeToDisplay"
                                                    name="fuelTypeToDisplay" id="fuelTypeToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->fuelTypeToDisplay;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="secondaryFuelTypeToDisplay" class="col-form-label">Secondary
                                                    Fuel Type</label>
                                                <input type="text" class="form-control secondaryFuelTypeToDisplay"
                                                    name="secondaryFuelTypeToDisplay" id="secondaryFuelTypeToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->secondaryFuelTypeToDisplay;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="derivativeGlobal" class="col-form-label">Derivative
                                                    Global</label>
                                                <input type="text" class="form-control derivativeGlobal"
                                                    name="derivativeGlobal" id="derivativeGlobal"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->derivativeGlobal;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="derivativeLocal" class="col-form-label">Derivative
                                                    Local</label>
                                                <input type="text" class="form-control derivativeLocal"
                                                    name="derivativeLocal" id="derivativeLocal"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->derivativeLocal;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="drivenWheelsToDisplay" class="col-form-label">Driven
                                                    Wheels</label>
                                                <input type="text" class="form-control drivenWheelsToDisplay"
                                                    name="drivenWheelsToDisplay" id="drivenWheelsToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->drivenWheelsToDisplay;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="engine" class="col-form-label">Engine</label>
                                                <input type="text" class="form-control engine" name="engine"
                                                    id="engine" value="<?php if (isset($data->id)) {
                                                        echo $data->engine;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="transmissionToDisplay"
                                                    class="col-form-label">Transmission</label>
                                                <input type="text" class="form-control transmissionToDisplay"
                                                    name="transmissionToDisplay" id="transmissionToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->transmissionToDisplay;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="trimLevelToDisplay" class="col-form-label">Trim
                                                    Level</label>
                                                <input type="text" class="form-control trimLevelToDisplay"
                                                    name="trimLevelToDisplay" id="trimLevelToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->trimLevelToDisplay;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="liters" class="col-form-label">Liters</label>
                                                <input type="text" class="form-control liters" name="liters"
                                                    id="liters" value="<?php if (isset($data->id)) {
                                                        echo $data->liters;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="maximumPowerKw" class="col-form-label">Maximum
                                                    PowerKw</label>
                                                <input type="text" class="form-control maximumPowerKw"
                                                    name="maximumPowerKw" id="maximumPowerKw"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->maximumPowerKw;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="maximumPowerhpPs" class="col-form-label">Maximum
                                                    PowerhpPs</label>
                                                <input type="text" class="form-control maximumPowerhpPs"
                                                    name="maximumPowerhpPs" id="maximumPowerhpPs"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->maximumPowerhpPs;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="vehicleType" class="col-form-label">Vehicle Type</label>
                                                <input type="text" class="form-control vehicleType"
                                                    name="vehicleType" id="vehicleType"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->vehicleType;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="cylinderCapacity" class="col-form-label">Cylinder
                                                    Capacity</label>
                                                <input type="text" class="form-control cylinderCapacity"
                                                    name="cylinderCapacity" id="cylinderCapacity"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->cylinderCapacity;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="bodyCodeToDisplay" class="col-form-label">Body
                                                    Code</label>
                                                <input type="text" class="form-control bodyCodeToDisplay"
                                                    name="bodyCodeToDisplay" id="bodyCodeToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->bodyCodeToDisplay;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <label for="derivativeToDisplay"
                                                    class="col-form-label">Derivative</label>
                                                <input type="text" class="form-control derivativeToDisplay"
                                                    name="derivativeToDisplay" id="derivativeToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->derivativeToDisplay;
                                                    } ?>" />

                                            </div>
                                            <div class="col-md-6">
                                                <label for="wheelbaseTypeToDisplay"
                                                    class="col-form-label">wheelbaseTypeToDisplay</label>
                                                <input type="text" class="form-control wheelbaseTypeToDisplay"
                                                    name="wheelbaseTypeToDisplay" id="wheelbaseTypeToDisplay"
                                                    value="<?php if (isset($data->id)) {
                                                        echo $data->wheelbaseTypeToDisplay;
                                                    } ?>" />
                                            </div>
                                        </div>
                                        {{--  --}}
                                        <button type="button" class="btn btn-primary next-step">Next</button>
                                    </div>
                                </div>
                                <div class="step step-2">
                                    <!-- Step 2 form fields here -->
                                    <h3 class="mb-3">Engine</h3>
                                    <div>
                                        <?php if(count($engine)!=0){ ?>
                                            @php
                                            $i=1;
                                            @endphp
                                            @foreach($engine as $value)
                                            <input type="hidden" name="engineid[]" value="{{$value->id}}">
                                            <div class="row mb-3 customer_records_Engine" id="records{{$value->id}}">
                                                {{-- <input type="hidden" name="engineid[]" value=""> --}}
                                                <div class="col-md-6">
                                                    <label for="info" class="col-form-label">Key (English)</label>
                                                    <input type="text" name="key[]"
                                                        class="form-control key" placeholder="Key (English)" value="{{$value->name}}"
                                                        required>
                                                    <div class="text-danger" id="key_error"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="infoar" class="col-form-label">Value (English)</label>
                                                    <input type="text" name="valueengine[]"
                                                        class="form-control valueengine" placeholder="Value (English)"
                                                        value="{{$value->value}}" required>
                                                    <div class="text-danger" id="valueengine_error"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="info" class="col-form-label">Key (Arabic)</label>
                                                    <input type="text" name="keyar[]"
                                                        class="form-control keyar" placeholder="Key (Arabic)" value="{{$value->name_ar}}"
                                                        required>
                                                    <div class="text-danger" id="keyar_error"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="infoar" class="col-form-label">Value (Arabic)</label>
                                                    <input type="text" name="valueenginear[]"
                                                        class="form-control valueenginear" placeholder="Value (Arabic)"
                                                        value="{{$value->value_ar}}" required>
                                                    <div class="text-danger" id="valueenginear_error"></div>
                                                </div>

                                                <div class="col-md-2">
                                                    <?php if($i==1){ ?>
                                                        <button type="button" class="btn btn-primary addmorecheck extra-fields-engine">+</button>
                                                    <?php }else{ ?>
                                                        <button type="button" class="btn btn-danger addmorecheck removerow" id="{{$value->id}}">X</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            @php
                                            $i++;
                                            @endphp
                                            @endforeach

                                        <?php }else{ ?>

                                        <div class="row mb-3 customer_records_Engine">
                                            <input type="hidden" name="engineid[]" value="">
                                            <div class="col-md-6">
                                                <label for="info" class="col-form-label">Key (English)</label>
                                                <input type="text" name="key[]"
                                                    class="form-control key" placeholder="Key (English)" value=""
                                                    required>
                                                <div class="text-danger" id="key_error"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="infoar" class="col-form-label">Value (English)</label>
                                                <input type="text" name="valueengine[]"
                                                    class="form-control valueengine" placeholder="Value (English)"
                                                    value="" required>
                                                <div class="text-danger" id="valueengine_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="info" class="col-form-label">Key (Arabic)</label>
                                                <input type="text" name="keyar[]"
                                                    class="form-control keyar" placeholder="Key (Arabic)" value=""
                                                    required>
                                                <div class="text-danger" id="keyar_error"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="infoar" class="col-form-label">Value (Arabic)</label>
                                                <input type="text" name="valueenginear[]"
                                                    class="form-control valueenginear" placeholder="Value (Arabic)"
                                                    value="" required>
                                                <div class="text-danger" id="valueenginear_error"></div>
                                            </div>

                                            <div class="col-md-2">
                                                <button type="button"
                                                    class="btn btn-primary addmorecheck extra-fields-engine">+</button>
                                            </div>
                                        </div>
                                   <?php } ?>

                                        <div class="customer_records_dynamic_Engine"></div>
                                    </div>
                                    <button type="button" class="btn btn-primary prev-step">Previous</button>
                                    <button type="button" class="btn btn-primary next-step">Next</button>
                                </div>

                                <div class="step step-3">
                                    <!-- Step 3 form fields here -->
                                    <h3 class="mb-3">Mileage</h3>
                                    <div>
                                        <?php if(count($mileage)!=0){ ?>
                                            @php
                                            $j=1;
                                            @endphp
                                            @foreach($mileage as $value_mileage)
                                            <input type="hidden" name="mileage_id[]" value="{{$value_mileage->id}}">
                                            <div class="row mb-3 customer_records_mileage" id="records_mileage{{$value_mileage->id}}">
                                                {{-- <input type="hidden" name="mileage_id[]" value=""> --}}
                                                <div class="col-md-6">
                                                    <label for="info" class="col-form-label">Key (English)</label>
                                                    <input type="text" name="key_mileage[]"
                                                        class="form-control key_mileage" placeholder="Key (English)" value="{{$value_mileage->name}}"
                                                        required>
                                                    <div class="text-danger" id="key_mileage_error"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="infoar" class="col-form-label">Value (English)</label>
                                                    <input type="text" name="value_mileage[]"
                                                        class="form-control value_mileage" placeholder="Value (English)"
                                                        value="{{$value_mileage->value}}" required>
                                                    <div class="text-danger" id="value_mileage_error"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="info" class="col-form-label">Key (Arabic)</label>
                                                    <input type="text" name="key_mileage_ar[]"
                                                        class="form-control key_mileage_ar" placeholder="Key (Arabic)" value="{{$value_mileage->name_ar}}"
                                                        required>
                                                    <div class="text-danger" id="key_mileagear_error"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="infoar" class="col-form-label">Value (Arabic)</label>
                                                    <input type="text" name="value_mileage_ar[]"
                                                        class="form-control value_mileage_ar" placeholder="Value (Arabic)"
                                                        value="{{$value_mileage->value_ar}}" required>
                                                    <div class="text-danger" id="valu_emileage_error"></div>
                                                </div>

                                                <div class="col-md-2">
                                                    <?php if($j==1){ ?>
                                                        <button type="button" class="btn btn-primary addmorecheck extra-fields-mileage">+</button>
                                                    <?php }else{ ?>
                                                        <button type="button" class="btn btn-danger addmorecheck removerow_mileage" id="{{$value_mileage->id}}">X</button>
                                                    <?php } ?>
                                                    {{-- <button type="button"
                                                        class="btn btn-primary addmorecheck extra-fields-mileage">+</button> --}}
                                                </div>
                                            </div>
                                            @php
                                            $j++;
                                            @endphp
                                            @endforeach

                                        <?php }else{ ?>
                                        <div class="row mb-3 customer_records_mileage">
                                            <input type="hidden" name="mileage_id[]" value="">
                                            <div class="col-md-6">
                                                <label for="info" class="col-form-label">Key (English)</label>
                                                <input type="text" name="key_mileage[]"
                                                    class="form-control key_mileage" placeholder="Key (English)" value=""
                                                    required>
                                                <div class="text-danger" id="key_mileage_error"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="infoar" class="col-form-label">Value (English)</label>
                                                <input type="text" name="value_mileage[]"
                                                    class="form-control value_mileage" placeholder="Value (English)"
                                                    value="" required>
                                                <div class="text-danger" id="value_mileage_error"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="info" class="col-form-label">Key (Arabic)</label>
                                                <input type="text" name="key_mileage_ar[]"
                                                    class="form-control key_mileage_ar" placeholder="Key (Arabic)" value=""
                                                    required>
                                                <div class="text-danger" id="key_mileagear_error"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="infoar" class="col-form-label">Value (Arabic)</label>
                                                <input type="text" name="value_mileage_ar[]"
                                                    class="form-control value_mileage_ar" placeholder="Value (Arabic)"
                                                    value="" required>
                                                <div class="text-danger" id="valu_emileage_error"></div>
                                            </div>

                                            <div class="col-md-2">
                                                <button type="button"
                                                    class="btn btn-primary addmorecheck extra-fields-mileage">+</button>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="customer_records_dynamic_mileage"></div>


                                    </div>
                                    <button type="button" class="btn btn-primary prev-step">Previous</button>
                                    <button type="button" class="btn btn-primary next-step">Next</button>
                                </div>
                                <div class="step step-4">
                                    <!-- Step 3 form fields here -->
                                    <h3 class="mb-3">Color</h3>
                                    <div>
                                        <?php if(count($color)!=0){ ?>
                                            @php
                                            $c=1;
                                            @endphp
                                            @foreach($color as $value_color)
                                            <input type="hidden" name="colorID[]" value="{{$value_color->id}}">
                                            <div class="row mb-3 customer_records_color" id="records_color{{$value_color->id}}" >
                                                {{-- <input type="hidden" name="colorID[]" value=""> --}}
                                                <div class="col-md-6">
                                                    <label for="info" class="col-form-label">Choose a color</label>
                                                    <input type="color" name="key_color[]"
                                                        class="form-control key_color" placeholder="" value="{{$value_color->value}}"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <?php if($c==1){ ?>
                                                        <button type="button" class="btn btn-primary addmorecheck extra-fields-color">+</button>
                                                    <?php }else{ ?>
                                                        <button type="button" class="btn btn-danger addmorecheck removerow_color" id="{{$value_color->id}}">X</button>
                                                    <?php } ?>
                                                    {{-- <button type="button"
                                                        class="btn btn-primary addmorecheck extra-fields-color">+</button> --}}
                                                </div>
                                            </div>
                                            @php
                                            $c++;
                                            @endphp
                                            @endforeach

                                        <?php }else{ ?>

                                        <div class="row mb-3 customer_records_color">
                                            <input type="hidden" name="colorID[]" value="">
                                            <div class="col-md-6">
                                                <label for="info" class="col-form-label">Choose a color</label>
                                                <input type="color" name="key_color[]"
                                                    class="form-control key_color" placeholder="" value=""
                                                    required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button"
                                                    class="btn btn-primary addmorecheck extra-fields-color">+</button>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="customer_records_dynamic_color"></div>

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
                                                            <label class="upload__btn col-form-label">Interior images</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="file" multiple="" data-max_length="20"
                                                                class="upload__inputfile form-control"
                                                                name="interiorimg" accept=".jpeg, .jpg, .png,.gif">
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
                                                            <label class="upload__btn col-form-label"> Exterior images</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="file" multiple="" data-max_length="20"
                                                                class="upload__inputfile form-control"
                                                                name="exteriorimg" accept=".jpeg, .jpg, .png,.gif">
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
                                                            <label class="upload__btn col-form-label"> Road Test images </label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="file" multiple="" data-max_length="20"
                                                                class="upload__inputfile form-control" name="testimg" accept=".jpeg, .jpg, .png,.gif">
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
                                            <label for="" class="mb-2 mt-3 col-form-label">Video URL</label>
                                            <div class="optionBox">

                                                <div class="block">
                                                    <input type="text" class="videourl" name="videourl[]"
                                                        value="" />
                                                    <!-- <span class="remove">Remove Option</span> -->
                                                </div>
                                                <div class="block my-3">
                                                    <span class="add">Add Option</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label for="" class="mb-2 mt-3 col-form-label">360 View</label>
                                            <input type="file" name="video360" class="form-control" id="video360">

                                        </div>
                                    </div>
                                    <input type="hidden" class="id" name="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                                    <input type="hidden" class="modalid" name="modalid" value="@if(isset($data->id)) {{$data->modelID}} @endif">
                                    <button type="button" class="btn btn-primary prev-step">Previous</button>
                                    <button type="button" class="btn btn-success submitdatas">Submit</button>
                                </div>
                                <!-- video -->
                                <div class="alert alert-success text-center hide1"><span class="msg_success"></span>
                                </div>
                                <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span>
                                </div>
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
        $('.block:last').before(
            '<div class="block"><input type="text" class="videourl" name="videourl[]" value="" /><span class="remove"><i class="fi fi-br-cross-circle"></i></span></div>'
        );
    });
    $('.optionBox').on('click', '.remove', function() {
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
                    $(".step-" + currentStep).show().addClass(
                        "animate__animated animate__fadeInRight");
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
                    $(".step-" + currentStep).show().addClass(
                        "animate__animated animate__fadeInLeft");
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
    $('.submitdatas').click(function() {
        var name = $('.name').val();
        var modelID = $('.modalid').val();
        if (name == '') {
            $('.name').css('border', '1px solid red');
        } else {
            $('.name').css('border', '');
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/insert_car') }}',
                data: new FormData($("#multi-step-form")[0]),
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if ($.trim(data) == "1") {
                        $('.hide1').css('display', 'block');
                        $('.msg_success').text("Sucessfully submitted");
                        $(".alert-success").show('slow', 'linear').delay(4000).fadeOut(function() {
                            window.location.href = "{{ URL::to('/admin/carlist?key=') }}" +
                                modelID;
                        });

                    }
                    if ($.trim(data) == "2") {
                        $('.hide2').css('display', 'block');
                        $('.msg_danger').text("Name already exist");
                        $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

                    }
                }

            });
        }
    })
</script>

<script>
    jQuery(document).ready(function() {
        ImgUpload();
    });

    function ImgUpload() {
        var imgWrap = "";
        var imgArray = [];

        $('.upload__inputfile').each(function() {
            $(this).on('change', function(e) {
                imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                var maxLength = $(this).attr('data-max_length');

                var files = e.target.files;
                var filesArr = Array.prototype.slice.call(files);
                var iterator = 0;
                filesArr.forEach(function(f, index) {

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
                            reader.onload = function(e) {
                                var html =
                                    "<div class='upload__img-box'><div style='background-image: url(" +
                                    e.target.result + ")' data-number='" + $(
                                        ".upload__img-close").length + "' data-file='" + f
                                    .name +
                                    "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                imgWrap.append(html);
                                iterator++;
                            }
                            reader.readAsDataURL(f);
                        }
                    }
                });
            });
        });

        $('body').on('click', ".upload__img-close", function(e) {
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
    jQuery(document).ready(function() {

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



    $('body').on('click', ".upload__img-close", function(e) {

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
    $(document).ready(function() {

        var i = 1;
        var length;
        $(".extra-fields-engine").click(function() {
            i++;
            $('.customer_records_dynamic_Engine').append('<div class="row mb-3 customer_records_Engine" id="records' + i +'"><div class="col-md-6"><label for="info" class="col-form-label">Key (English)</label><input type="text" name="key[]" class="form-control key" placeholder="Key (English)" value="" required><div class="text-danger" id="key_error"></div></div><div class="col-md-4"><label for="infoar" class="col-form-label">Value (English)</label><input type="text" name="valueengine[]"    class="form-control valueengine" placeholder="Value (English)" value="" required><div class="text-danger" id="valueengine_error"></div></div><div class="col-md-6"><label for="info" class="col-form-label">Key (Arabic)</label><input type="text" name="keyar[]" class="form-control keyar" placeholder="Key (Arabic)" value="" required><div class="text-danger" id="keyar_error"></div></div><div class="col-md-4"><label for="infoar" class="col-form-label">Value (Arabic)</label><input type="text" name="valueenginear[]" class="form-control valueenginear" placeholder="Value (Arabic)" value="" required><div class="text-danger" id="valueenginear_error"></div></div><div class="col-md-2"><button type="button" class="btn btn-danger addmorecheck btn_remove" id="' + i + '">X</button></div></div>');
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#records' + button_id + '').remove();
        });
    });
</script>
<script>
    $(document).ready(function() {

        var m = 1;
        var length;
        $(".extra-fields-mileage").click(function() {
            m++;
            $('.customer_records_dynamic_mileage').append('<div class="row mb-3 customer_records_mileage" id="records_mileage' + m +'"><div class="col-md-6"><label for="info" class="col-form-label">Key (English)</label><input type="text" name="key_mileage[]" class="form-control key_mileage" placeholder="Key (English)" value="" required><div class="text-danger" id="key_mileage_error"></div></div><div class="col-md-4"><label for="infoar" class="col-form-label">Value (English)</label><input type="text" name="value_mileage[]"    class="form-control value_mileage" placeholder="Value (English)" value="" required><div class="text-danger" id="value_mileage_error"></div></div><div class="col-md-6"><label for="info" class="col-form-label">Key (Arabic)</label><input type="text" name="key_mileage_ar[]" class="form-control key_mileage_ar" placeholder="Key (Arabic)" value="" required><div class="text-danger" id="key_mileage_ar_error"></div></div><div class="col-md-4"><label for="infoar" class="col-form-label">Value (Arabic)</label><input type="text" name="value_mileage_ar[]" class="form-control value_mileage_ar" placeholder="Value (Arabic)" value="" required><div class="text-danger" id="value_mileage_ar_error"></div></div><div class="col-md-2"><button type="button" class="btn btn-danger addmorecheck btn_removemileage" id="' + m + '">X</button></div></div>');
        });

        $(document).on('click', '.btn_removemileage', function() {
            var button_id = $(this).attr("id");
            $('#records_mileage' + button_id + '').remove();
        });
    });
</script>
<script>
    $(document).ready(function() {

        var c = 1;
        var length;
        $(".extra-fields-color").click(function() {
            c++;
            $('.customer_records_dynamic_color').append('<div class="row mb-3 customer_records_color" id="records_color' + c +'"><div class="col-md-6"><label for="info" class="col-form-label">Choose a color</label><input type="color" name="key_color[]" class="form-control key_color" placeholder="" value="" required></div><div class="col-md-2"><button type="button" class="btn btn-danger addmorecheck btn_removecolor" id="' + c + '">X</button></div></div>');
        });

        $(document).on('click', '.btn_removecolor', function() {
            var button_id = $(this).attr("id");
            $('#records_mileage' + button_id + '').remove();
        });
    });
</script>
{{-- remove --}}
<script>
    $(document).on('click', '.removerow', function(){
        var button_id = $(this).attr("id");

         var token = "<?=csrf_token()?>";
        if(confirm("Are you sure want to delete this?")){
            $('#records'+button_id+'').remove();
        $.ajax({
            type:'POST',
            url:'{{url("/admin/delete_carengine")}}',
            data  :{id:button_id,_token:token},
            success:function(data){
                $('#records' + button_id + '').remove();
            }

        });
        }
     });
</script>
<script>
    $(document).on('click', '.removerow_mileage', function(){
        var button_id = $(this).attr("id");

         var token = "<?=csrf_token()?>";
        if(confirm("Are you sure want to delete this?")){
            $('#records'+button_id+'').remove();
        $.ajax({
            type:'POST',
            url:'{{url("/admin/delete_carmileage")}}',
            data  :{id:button_id,_token:token},
            success:function(data){
                $('#records_mileage' + button_id + '').remove();
            }

        });
        }
     });
</script>
<script>
    $(document).on('click', '.removerow_color', function(){
        var button_id = $(this).attr("id");

         var token = "<?=csrf_token()?>";
        if(confirm("Are you sure want to delete this?")){
            $('#records'+button_id+'').remove();
        $.ajax({
            type:'POST',
            url:'{{url("/admin/delete_carcolor")}}',
            data  :{id:button_id,_token:token},
            success:function(data){
                $('#records_mileage' + button_id + '').remove();
            }

        });
        }
     });
</script>


@include('admin.includes.footer')
