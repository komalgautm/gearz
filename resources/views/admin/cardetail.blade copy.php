@include('admin.includes.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/360.js@2.0.0/dist/360.min.css">
<style>
    .nav-link {
        color: black;
    }
    .nav-link:hover {
        color: #fae80b;
    }

</style>
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-md-10">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$brand_name}}. {{$model_name}}. {{$data->name}}. Car Detail</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Car Detail</li>
                </ol> -->
                <div class="card p-4">
                    <div class="card-body">
                        @if(Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
                        @endif
                        <!-- <a href="{{ url('admin/addBrand') }}" class="white_btn3">Create</a> -->
                        <!-- <button type="button" class="white_btn3" data-toggle="modal" data-target="#myModal"></button> -->
                        <!-- Button trigger modal -->
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Body</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Engine</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Mileage</button>
                                <button class="nav-link" id="nav-color-tab" data-bs-toggle="tab" data-bs-target="#nav-color" type="button" role="tab" aria-controls="nav-color" aria-selected="false">Color</button>
                                <button class="nav-link" id="nav-image-tab" data-bs-toggle="tab" data-bs-target="#nav-image" type="button" role="tab" aria-controls="nav-image" aria-selected="false">Image</button>
                                <button class="nav-link" id="nav-video-tab" data-bs-toggle="tab" data-bs-target="#nav-video" type="button" role="tab" aria-controls="nav-video" aria-selected="false">Video</button>
                                <button class="nav-link" id="nav-video360-tab" data-bs-toggle="tab" data-bs-target="#nav-video360" type="button" role="tab" aria-controls="nav-video360" aria-selected="false">360</button>
                            </div>
                        </nav>
                        <div class="tab-content p-3 border" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="QA_table ">
                                    <table class="table p-0">
                                        <tr>
                                            <td><strong>Make Name</strong></td>
                                            <td>{{$data->makeNameToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Model Name</strong></td>
                                            <td>{{$data->modelNameToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Name</strong></td>
                                            <td>{{$data->name}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Year</strong></td>
                                            <td>{{$data->year}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Number Of Doors</strong></td>
                                            <td>{{$data->numberOfDoorsToDisplay}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>shortBodyName</strong></td>
                                            <td>{{$data->shortBodyName}}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>Body Code</strong></td>
                                            <td>{{$data->bodyCodeToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Driven Wheels</strong></td>
                                            <td>{{$data->drivenWheelsToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fuel Type</strong></td>
                                            <td>{{$data->fuelTypeToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Secondary Fuel Type</strong></td>
                                            <td>{{$data->secondaryFuelTypeToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Transmission</strong></td>
                                            <td>{{$data->transmissionToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Derivative</strong></td>
                                            <td>{{$data->derivativeToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>TrimLevel</strong></td>
                                            <td>{{$data->trimLevelToDisplay}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Wheel Base Type</strong></td>
                                            <td>{{$data->wheelbaseTypeToDisplay}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>numberOfDoors</strong></td>
                                            <td>{{$data->numberOfDoors}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>bodyCode</strong></td>
                                            <td>{{$data->bodyCode}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>drivenWheels</strong></td>
                                            <td>{{$data->drivenWheels}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>fuelType</strong></td>
                                            <td>{{$data->fuelType}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>secondaryFuelType</strong></td>
                                            <td>{{$data->secondaryFuelType}}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>Derivative Global</strong></td>
                                            <td>{{$data->derivativeGlobal}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Derivative Local</strong></td>
                                            <td>{{$data->derivativeLocal}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Engine</strong></td>
                                            <td>{{$data->engine}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>transmission</strong></td>
                                            <td>{{$data->transmission}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>trimLevel</strong></td>
                                            <td>{{$data->trimLevel}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>seats</strong></td>
                                            <td>{{$data->seats}}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>Liters</strong></td>
                                            <td>{{$data->liters}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>manufacturerCode</strong></td>
                                            <td>{{$data->manufacturerCode}}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>Maximum PowerKw</strong></td>
                                            <td>{{$data->maximumPowerKw}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maximum PowerhpPs</strong></td>
                                            <td>{{$data->maximumPowerhpPs}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vehicle Type</strong></td>
                                            <td>{{$data->vehicleType}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Concluded Date</strong></td>
                                            <td>{{$data->concludedDate}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Availability</strong></td>
                                            <td>{{$data->availability}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cylinder Capacity</strong></td>
                                            <td>{{$data->cylinderCapacity}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>wheelbaseType</strong></td>
                                            <td>{{$data->wheelbaseType}}</td>
                                        </tr> --}}
                                        {{-- <tr>
                                            <td><strong>vehicleState</strong></td>
                                            <td>{{$data->vehicleState}}</td>
                                        </tr> --}}
                                        <tr>
                                            <td><strong>Roof Type</strong></td>
                                            <td>{{$data->roofType}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td><strong>modelYearToDisplay</strong></td>
                                            <td>{{$data->modelYearToDisplay}}</td>
                                        </tr> --}}

                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="QA_table ">
                                    <table class="table p-0" id="example">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Key</th>
                                                <th scope="col">value</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $e = 1;
                                            @endphp
                                            @foreach ($engine as $value_engine)
                                                <tr>
                                                    <td>{{ $e }}</td>
                                                    <td>{{ $value_engine->name }}</td>
                                                    <td>{{ $value_engine->value }}</td>
                                                    <td><a class="delete delete_engine"
                                                            data-id="{{ $value_engine->id }}"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                                @php
                                                    $e++;
                                                @endphp
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="QA_table ">
                                    <table class="table p-0" id="example">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Key</th>
                                                <th scope="col">value</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $m = 1;
                                            @endphp
                                            @foreach ($mileage as $value_mileage)
                                                <tr>
                                                    <td>{{ $m }}</td>
                                                    <td>{{ $value_mileage->name }}</td>
                                                    <td>{{ $value_mileage->value }}</td>
                                                    <td><a class="delete delete_mileage"
                                                            data-id="{{ $value_mileage->id }}"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                                @php
                                                    $m++;
                                                @endphp
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-color" role="tabpanel" aria-labelledby="nav-color-tab">
                                <div class="QA_table ">
                                    <table class="table p-0" id="example">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Color</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $c = 1;
                                            @endphp
                                            @foreach ($color as $value_color)
                                                <tr>
                                                    <td>{{ $c }}</td>
                                                    <td><div style="background-color:{{ $value_color->value }};width: 30px;height: 30px;border-radius: 50%;"></div></td>

                                                    <td><a class="delete delete_color"
                                                            data-id="{{ $value_color->id }}"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                                @php
                                                    $c++;
                                                @endphp
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab">
                                <div class="QA_table ">
                                    <table class="table p-0" id="example">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Image</th>
                                                <th scope="col">Image Type</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $im = 1;
                                            @endphp
                                            @foreach($carimage as $carval)
                                                <tr>
                                                    <td>{{ $im }}</td>
                                                    <td><?php if($carval->image_type==1){ ?>
                                                        <img class="img-fluid" src='{{url("public/images/car/".$carval->image)}}'>
                                                        <?php }else{ ?>
                                                            <img class="img-fluid" src='{{$carval->image}}'>
                                                            <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" data-id="{{$carval->id}}" value="1" <?php if((int)$carval->type === 1){ echo "checked"; } ?>>
                                                            <label class="form-check-label" for="inlineRadio2">Exterior Image</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" data-id="{{$carval->id}}" value="2" <?php if((int)$carval->type === 2){ echo "checked"; } ?>>
                                                            <label class="form-check-label" for="inlineRadio1" >Interior Image</label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" data-id="{{$carval->id}}" value="3" <?php if((int)$carval->type === 3){ echo "checked"; } ?>>
                                                            <label class="form-check-label" for="inlineRadio3">Test drive Image</label>
                                                        </div>

                                                    </td>

                                                    <td><a class="delete deleteimg"
                                                            data-id="{{ $carval->id }}"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                                @php
                                                    $im++;
                                                @endphp
                                            @endforeach

                                        </tbody>
                                    </table>

                                    {{--<div class="row">
                                         @foreach($carimage as $carval)
                                        <div class="col-md-2">
                                            <a class="close deleteimg" data-id="{{$carval->id}}"><i class="fa fa-times"></i></a>
                                            <?php if($carval->image_type==1){ ?>
                                            <img class="img-fluid" src='{{url("public/images/car/".$carval->image)}}'>
                                            <?php }else{ ?>
                                                <img class="img-fluid" src='{{$carval->image}}'>
                                                <?php } ?>
                                        </div>
                                        @endforeach
                                    </div> --}}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-video" role="tabpanel" aria-labelledby="nav-video-tab">
                                <div class="QA_table ">
                                    <div class="row">
                                        @foreach($carvideo as $carvval)
                                        <?php
                                        //echo $carvval->video;
                                        $ex = explode("https://www.youtube.com/watch?v=", $carvval->video);
                                        //echo end($ex);
                                        ?>
                                        <div class="col-md-2">
                                            <iframe src="https://www.youtube.com/embed/<?= end($ex) ?>"></iframe>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-video360" role="tabpanel" aria-labelledby="nav-video360-tab">
                                <div class="QA_table ">
                                    <div class="row">
                                        <div id="viewer" style="width: 100%; height: 500px;"></div>

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
<script src="https://cdn.jsdelivr.net/npm/360.js@2.0.0/dist/360.min.js"></script>
<?php if($car360->count()!=0){ ?>
    <script>
        var viewer = new Viewer({
            element: document.getElementById('viewer'),
            image: '{{$car360->first()  ->image}}', // replace with your 360 image URL
            width: '100%',
            height: '500px'
        });
    </script>
<?php } ?>

<script>
    $(".deleteimg").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/deleteimg")}}',
                data: {
                    id: id,
                    _token: token
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $(".delete_engine").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/delete_carengine")}}',
                data: {
                    id: id,
                    _token: token
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $(".delete_mileage").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/delete_carmileage")}}',
                data: {
                    id: id,
                    _token: token
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $(".delete_color").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{url("/admin/delete_carcolor")}}',
                data: {
                    id: id,
                    _token: token
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $(document).ready(function(){
        // When a radio button is changed
        $('input[type="radio"][name="inlineRadioOptions"]').change(function(){
            var status = $(this).val();  // Get the value of the selected radio button
            var id = $(this).data('id');
            // Display the selected status on the page (for testing)
            //$('#selectedStatus').text("Selected Status: " + status);

            // Send an AJAX request to update the value in the database
            $.ajax({
                url: "{{url('/admin/updateimagetype') }}",  // The route you want to call
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    status: status, // The new status value
                    id:id
                },
                success: function(response) {
                    // Handle success (optional)
                    console.log('Status updated successfully.');
                },
                error: function(xhr, status, error) {
                    // Handle error (optional)
                    console.error('Error: ' + error);
                }
            });
        });
    });
</script>


@include('admin.includes.footer')
