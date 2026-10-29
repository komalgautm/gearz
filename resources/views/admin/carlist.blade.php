@include('admin.includes.header')
<style>
    .fa-file {
        font-size: 20px;
        color: brown;
    }
</style>

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> {{ $brand_name ?? '' }} Brand Car List</h4>

                    </div>
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                    <!-- <button type="button"class="white_btn3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Create </button> -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <table class="table p-0" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Make</th>
                                <th scope="col">Model</th>
                                <th scope="col">Trim</th>
                                <th scope="col">Body Style</th>
                                <th scope="col">Year</th>
                                <th scope="col">Popular</th>
                                <th scope="col">Details</th>
                            </tr>
                        </thead>
                        <!-- [brand_id] => 7 [make] => Abarth [model] => 500 [trim] => Esseesse [body_style] => Hatchback [year] => 2008 [image] => -->
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                                @php
                                $data = App\Models\PopularCar::where(['brand_id' => $value['brand_id'], 'make' => $value['make'], 'model' => $value['model'], 'trim' => $value['trim'], 'year' => $value['year']])->first();
                                if($data){ $popular = 1; }else{ $popular = 0; }
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td> 
                                    <td>{{ $value['make'] }}</td>
                                    <td>{{ $value['model'] }}</td>
                                    <td>{{ $value['trim'] }}</td>
                                    <td>{{ $value['body_style'] }}</td>
                                    <td>{{ $value['year'] }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedpopular"
                                                type="checkbox" role="switch" id="flexSwitchCheckCheckedpopular"
                                                data-brandid="{{ $value['brand_id'] }}" data-make="{{ $value['make'] }}" data-model="{{ $value['model'] }}" data-trim="{{ $value['trim'] }}" data-year="{{ $value['year'] }}" <?php if ($popular == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/cardetail?key=' . base64_encode($value['brand_id']) . '&make=' . $value['make']. '&model=' . $value['model']. '&trim=' . $value['trim']. '&year=' . $value['year']) }}">
                                            <i class="fa fa-eye"></i>
                                        </a> 
                                    </td>
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
<!-- model -->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Car</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="saveform" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-form-label">Make</label>
                        <input type="text" name="make" id="make" class="form-control"
                            value="" readonly>
                        <input type="hidden" name="brandID" id="brandID" value="">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Model</label>
                        <input type="text" name="model" id="model" class="form-control"
                            value="" readonly>
                        <input type="hidden" name="modelid" id="modelid" value="">
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Year</label>
                        <select name="yearID" id="yearID" class="form-select">
                                <option value=""></option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="loadButton" type="button" class="btn btn-primary submitdata">Submit</button>
                    <div class="alert alert-success text-center hide1"><span class="msg_success"></span></div>
                    <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span></div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="overlay" id="overlay">
    <div id="loader" class="loader"></div>
</div>

<!-- model -->
{{-- model2 --}}
<div class="modal fade" id="staticBackdropbro" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabelbro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Brochure</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="saveformbrochure" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12  mb-3">
                        <label for="brochure" class="col-form-label">Brochure</label>
                        <input type="file" name="brochure" id="brochure" class="form-control file"
                            placeholder="Brochure" accept=".pdf">
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" class="broid" name="broid" value="">
                    <button id="loadButton" type="button" class="btn btn-primary submitbrodata">Submit</button>
                    <div class="alert alert-success text-center hide1"><span class="msg_success"></span></div>
                    <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span></div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- model2 --}}
<script>
    function brochure(id) {
        $('.broid').val(id);
        $('#brochure').val('');
        $('#staticBackdropbro').modal('show');
    }
</script>
<script>
    $('.submitbrodata').click(function() {
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/insertbrochure') }}',
            data: new FormData($("#saveformbrochure")[0]),
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
                        location.reload();
                    });

                }
                if ($.trim(data) == "2") {
                    $('.hide2').css('display', 'block');
                    $('.msg_danger').text("Name already exist");
                    $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

                }
            }

        });

    })
</script>

<script>
    $(".delete").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/delete_car') }}',
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
    $('.flexSwitchCheckChecked').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        var checkbox = $(this);
        var status = checkbox.is(':checked') ? 1 : 0;
        if (!confirm("Are you sure you want to change this status?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkbox.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/change_car_status') }}',
            data: {
                id: id,
                _token: token,
                status: status
            },
            success: function(data) {
                location.reload();
            }

        });

    })
</script>
<script>
    $('.flexSwitchCheckCheckedpopular').click(function() {
        var brand_id = $(this).data('brandid');
        var make = $(this).data('make');
        var model = $(this).data('model');
        var trim = $(this).data('trim');
        var year = $(this).data('year');
        var token = "<?= csrf_token() ?>";
        var checkboxp = $(this);
        var status = checkboxp.is(':checked') ? 1 : 0;
        var action = status == 1 ? 'add this to' : 'remove this from';
        if (!confirm("Are you sure you want to "+ action +" the popular list?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkboxp.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/add_popular_car') }}',
            data: {
                _token: token,
                brand_id: brand_id,
                // status: status,
                make: make,
                model: model,
                trim: trim,
                year: year,
            },
            success: function(data) {
                location.reload();
            }
        });

    })
</script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Export',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Car List' + n;
                },
                title: 'Car List'
            }],
        });
    });
</script>
<script>
    $('.submitdata').click(function() {
        var brandID = $('#brandID').val();
        var modelid = $('#modelid').val();
        var yearID = $('#yearID').val();
        var CarClasses = $('#CarClasses').val();
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/insertcar') }}',
            data: new FormData($("#saveform")[0]),
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
                            btoa(modelid);
                    });

                }
                if ($.trim(data) == "2") {
                    $('.hide2').css('display', 'block');
                    $('.msg_danger').text("Something went wrong");
                    $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

                }
            }

        });

    })
</script>

@include('admin.includes.footer')
