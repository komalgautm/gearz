@include('admin.includes.header')
<style>
    .brand {
        display: none;
    }

    .time {
        display: none;
    }
    .inetrnalpage {
        display: none;
    }

    .form-check-input:checked {
        background-color: #766c2c;
        border-color: #766c2c;
        padding: 0px;
        width: 15px;
        height: 15px;
    }
</style>
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-md-10">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button"
                        title="Back"><i class="fa fa-arrow-left"></i></a> {{ $title }}</h4>
                <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/notification') }}">{{ $title }}</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol> -->
                <div class="card p-4">
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                    <!-- <a href="#" class="white_btn3">Create Report</a> -->
                    <div class="card-body">
                        <!-- <h2 class="card-title text-center pb-5">Add Brand</h2> -->
                        <form method="post" id="saveform" enctype="multipart/form-data">
                            @csrf
                            <div class="row ui">
                                <div class="col-sm-12">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="notifytype"
                                                    id="exampleRadios1" value="1" checked>
                                                <label class="form-check-label" for="exampleRadios1">
                                                    Push Notification
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="notifytype"
                                                    id="exampleRadios2" value="2">
                                                <label class="form-check-label" for="exampleRadios2">
                                                    PNG Notification
                                                </label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3 time">
                                            <label for="notify_type_time" class="col-form-label">Time (Second)</label>
                                            <select name="notify_type_time" id="notify_type_time" class="form-select">
                                                <?php for ($i=1; $i <=10; $i++) { ?>
                                                <option value="{{$i}}">{{$i}}</option>
                                            <?php } ?>
                                            </select>
                                            
                                            <div class="text-danger" id="image_time_error"></div>
                                        </div> --}}
                                    </div>
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Push Notification
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                                        <label class="form-check-label" for="exampleRadios2">
                                            PNG Notification
                                        </label>
                                    </div> --}}


                                </div>
                                <div class="col-sm-6 utype">
                                    <label for="usertype" class=" col-form-label">Users Type</label>
                                    <select name="usertype" id="usertype" class="form-select">
                                        <option value="1">All user</option>
                                        <option value="2">IOS user</option>
                                        <option value="3">Android user</option>
                                        <option value="4">Active user</option>
                                        <option value="5">Inactive user</option>
                                        <option value="6">Group user</option>
                                    </select>
                                    <div class="text-danger" id="usertype_error"></div>
                                </div>
                                <div class="col-sm-6 brand">
                                    <label for="brand" class=" col-form-label">Brand</label>
                                    <select name="brand" id="brand" class="form-select">
                                        @foreach ($brand as $val)
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger" id="brand_error"></div>
                                </div>

                            </div>
                            <div class="row ui upush">
                                <div class="col-sm-6">
                                    <label for="website" class=" col-form-label">User</label>
                                    <select name="user[]" multiple="" id="user"
                                        class="js-example-basic-single form-control">
                                        <option value="0">All</option>
                                        @foreach ($user as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger" id="user_error"></div>
                                </div>
                            </div>
                            <div class="row ui">
                                <div class="col-sm-6">
                                    <label for="name" class=" col-form-label">Title (English)</label>
                                    <input type="text" name="name" class="form-control name"
                                        placeholder="Title (English)" id="name"
                                        value="@if (isset($data->id)) {{ $data->name }} @endif" required>
                                    <div class="text-danger" id="name_error"></div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="namear" class=" col-form-label">Title (Arabic)</label>
                                    <input type="text" name="namear" class="form-control namear"
                                        placeholder="Title (Arabic)" id="namear"
                                        value="@if (isset($data->id)) {{ $data->name_ar }} @endif"
                                        required>
                                    <div class="text-danger" id="name_error"></div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 umpush">
                                    <label for="info" class=" col-form-label">Message (English)</label>
                                    <textarea name="info" id="info" cols="30" rows="5" class="form-control"
                                        placeholder="Message (English)">
@if (isset($data->id))
{{ $data->description }}
@endif
</textarea>
                                    <div class="text-danger" id="info_error"></div>
                                </div>
                                <div class="col-sm-12 umpush">
                                    <label for="info" class=" col-form-label">Message (Arabic)</label>
                                    <textarea name="infoar" id="info" cols="30" rows="5" class="form-control"
                                        placeholder="Message (Arabic)">
@if (isset($data->id))
{{ $data->description_ar }}
@endif
</textarea>
                                    <div class="text-danger" id="info_error"></div>
                                </div>
                                <div class="row ui">
                                    {{-- <div class="col-sm-6">
                                        <label for="url" class="col-form-label">URL</label>
                                        <input type="url" name="url" class="form-control" placeholder="URL"
                                            id="url" value="">
                                        <div class="text-danger" id="url_error"></div>
                                    </div> --}}
                                    <div class="col-sm-6">
                                        <label for="image" class="col-form-label">Upload Image</label>
                                        <input type="file" name="image" accept="image/*" class="form-control" id="image">
                                        <span class="text-danger"><b>Note : </b>Recommended size will be 3:2</span><br>
                                        <img id="previewImg" src="../public/img/image-preview.png" alt="Placeholder"
                                            width="100px">
                                        <div class="text-danger" id="image_error"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{-- radio button --}}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-inputs" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio1" value="1" checked>
                                            <label class="form-check-label" for="inlineRadio1">External URL</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-inputs" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio2" value="2">
                                            <label class="form-check-label" for="inlineRadio2">Internal Page</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 externalpage">
                                    <div class="col-sm-12">
                                        <label for="url" class="col-form-label">URL</label>
                                        <input type="url" name="url" class="form-control" placeholder="URL"
                                            id="url" value="<?php if (isset($banner)) {
                                                if ($banner->internal_external == 2) {
                                                    echo '';
                                                } else {
                                                    echo $banner->url;
                                                }
                                            } ?>">
                                        <div class="text-danger" id="url_error"></div>
                                    </div>
                                </div>
                                {{-- page --}}
                                <div class="row mb-3 inetrnalpage">
                                    <div class="col-sm-12">
                                        <label for="url" class="col-form-label">Page</label>
                                        <Select class="form-select" name="page" id="page">
                                            <option value="">Choose</option>
                                            @foreach ($InternalAppPage as $page)
                                                <option <?php if (isset($banner)) {
                                                    if ($banner->internal_external == 2) {
                                                        if ($banner->url == $page->id) {
                                                            echo 'Selected';
                                                        }
                                                    }
                                                } ?> value="{{ $page->id }}">
                                                    {{ $page->name }}</option>
                                            @endforeach

                                        </Select>
                                    </div>
                                </div>
                                {{-- page --}}
                                <div class="row ui upushtime">
                                    <div class="col-sm-6">
                                        <label for="end_date" class="col-form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control"
                                            min="<?= date('Y-m-d') ?>" placeholder="End Date" id="end_date"
                                            value="">
                                        <div class="text-danger" id="url_error"></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="end_time" class="col-form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control"
                                            placeholder="End Time" id="end_time" value="">
                                        <div class="text-danger" id="end_time_error"></div>
                                    </div>
                                </div>

                                <div class="col-sm-12 text-center mt-4">
                                    <input type="hidden" class="id" name="id"
                                        value="@if (isset($data->id)) {{ $data->id }} @endif">
                                    <button type="button" class="btn submitdata">Submit</button>
                                </div>
                                <div class="alert alert-success text-center hide1"><span class="msg_success"></span>
                                </div>
                                <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span>
                                </div>
                            </div>
                        </form><!-- End Horizontal Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('input[type=radio][name=inlineRadioOptions]').change(function() {
        if (this.value == '1') {
            $('.externalpage').css('display', 'block');
            $('.inetrnalpage').css('display', 'none');
        } else if (this.value == '2') {
            $('.externalpage').css('display', 'none');
            $('.inetrnalpage').css('display', 'block');
        }
    });
</script>
<script>
    $('input[type=radio][name=notifytype]').change(function() {
        if (this.value == '1') {
            //$('.time').css('display', 'none');
            $('.umpush,.upush,.utype').css('display', 'block');
            $('.upushtime').css('display', 'flex');

        } else if (this.value == '2') {
            //$('.time').css('display', 'block');
            $('.upushtime,.umpush,.upush,.utype,.inetrnalpage').css('display', 'none');
        }
    });
</script>
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const img = new Image();
        const objectURL = URL.createObjectURL(file);
        img.src = objectURL;

        img.onload = function() {
            const width = img.naturalWidth;
            const height = img.naturalHeight;
            const aspectRatio = width / height;
            const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(file.type)) {
                //messageDiv.textContent = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
                //return;
                const fileInput = document.getElementById('image');
                fileInput.value = '';
                document.getElementById('image_error').textContent =
                    "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
                document.getElementById('previewImg').style.display = 'none';
                // Clean up if the ratio is invalid
                URL.revokeObjectURL(objectURL);
                imgfile = "1";
            } else {
                document.getElementById('previewImg').src = objectURL;
                document.getElementById('previewImg').style.display = 'block';
                document.getElementById('image_error').style.display = 'none';
                imgfile = "0";
            }
            // const width = img.naturalWidth;
            // const height = img.naturalHeight;
            // const aspectRatio = width / height;

            // // Define your desired aspect ratio
            // //const desiredAspectRatio = 16 / 9;
            // const desiredAspectRatio = 4 / 3;
            // const tolerance = 0.03;  // Adjust the tolerance as needed
            // console.log(Math.abs(aspectRatio - desiredAspectRatio));
            // if (Math.abs(aspectRatio - desiredAspectRatio) < tolerance) {
            //     //document.getElementById('message').textContent = 'Image aspect ratio is valid!';
            //     document.getElementById('previewImg').src = objectURL;
            //     document.getElementById('previewImg').style.display = 'block';
            // } else {
            //     document.getElementById('image_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
            //     document.getElementById('previewImg').style.display = 'none';
            //     // Clean up if the ratio is invalid
            //     URL.revokeObjectURL(objectURL);
            // }
        };

        img.onerror = function() {
            document.getElementById('image_error').textContent = 'Failed to load image.';
            document.getElementById('previewImg').style.display = 'none';
        };
    });
</script>
<script>
    $('#usertype').change(function() {
        var type = $(this).val();
        var token = "<?= csrf_token() ?>";
        if (type == 6) {
            $('.brand').css('display', 'block')
        } else {
            $('.brand').css('display', 'none')
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/getuser') }}',
                data: {
                    type: type,
                    _token: token,
                    value: 1
                },
                success: function(data) {
                    console.log(data);
                    $("#user").html(data);
                }
            })
        }

    })
</script>
<script>
    $('#brand').change(function() {
        var brand = $(this).val();
        var token = "<?= csrf_token() ?>";
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/getuser') }}',
            data: {
                type: 6,
                _token: token,
                value: 2,
                brand: brand
            },
            success: function(data) {
                console.log(data);
                $("#user").html(data);
            }
        })


    })
</script>
<script>
    $('.submitdata').click(function() {
        var user = $("#user").val();
        var name = $('#name').val();
        var info = $('#info').val();
        var usertype = $('#usertype').val();
        var brand = $('#brand').val();
        var url = $('#url').val();
        var image = $('#image').val();
        var end_date = $('#end_date').val();
        var end_time = $('#end_time').val();
        // if (user == '') {
        //     $('#user_error').text("The user field is required.");
        //     return false;
        // }
        // if (!name) {
        //     $('#user_error').text("");
        //     $('#name_error').text("The title field is required.");
        //     return false;
        // }
        // if (!info) {
        //     $('#user_error').text("");
        //     $('#name_error').text("");
        //     $('#info_error').text("The info field is required.");
        //     return false;
        // }
        // if (url != '') {
        //     if (!url.match(
        //             /^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)) {
        //         $('#user_error').text("");
        //         $('#name_error').text("");
        //         $('#info_error').text("");
        //         $('#url_error').text("Please enter valid URL");
        //         return false;
        //     }
        // }
        // if (!end_date) {
        //     $('#user_error').text("");
        //     $('#name_error').text("");
        //     $('#info_error').text("");
        //     $('#end_date_error').text("The field is required.");
        //     return false;
        // }
        // if (!end_time) {
        //     $('#user_error').text("");
        //     $('#name_error').text("");
        //     $('#info_error').text("");
        //     $('#end_date_error').text("");
        //     $('#end_time_error').text("The info field is required.");
        //     return false;
        // }
        // $('#user_error').text("");
        // $('#name_error').text("");
        // $('#info_error').text("");
        // $('#url_error').text("");
        // $('#end_date_error').text("");
        // $('#end_time_error').text("");
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/insertNotification') }}',
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
                        window.location.href = "{{ URL::to('/admin/notification') }}";
                    });

                }
                if ($.trim(data) == "2") {
                    $('#end_time_error').text(
                        "Time must me be greater then current time for current date");
                    // $('.hide2').css('display', 'block');
                    // $('.msg_danger').text("Name already exist");
                    // $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

                }
            }

        });

    })
</script>


@include('admin.includes.footer')
