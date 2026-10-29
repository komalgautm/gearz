@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-10">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button"
                    title="Back"><i class="fa fa-arrow-left"></i></a> User</h4>
                <div class="card p-4">
                    <div class="card-body">
                        @if (Session::has('message'))
                            <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                                </span>{{ Session::get('message') }}</p>
                        @endif
                        <!-- <a href="#" class="white_btn3">Create Report</a> -->
                        <!-- <h2 class="card-title text-center pb-5">Add Brand</h2> -->
                        <form method="post" id="saveform" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="col-form-label">Name</label>
                                    <input type="text" name="name" class="form-control name" placeholder="Name"
                                        id="name"
                                        value="@if (isset($data->id)){{$data->name}}@endif" required>
                                    <div class="text-danger" id="name_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="col-form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">Choose</option>
                                        <option value="1" <?php if(isset($data->id)){if($data->gender==1){echo"Selected";}} ?>>Male</option>
                                        <option value="2" <?php if(isset($data->id)){if($data->gender==2){echo"Selected";}} ?>>Female</option>
                                        <option value="3" <?php if(isset($data->id)){if($data->gender==3){echo"Selected";}} ?>>Other</option>
                                    </select>
                                    <div class="text-danger" id="gender_error"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="dob" class="col-form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" placeholder="Date of Birth"
                                        id="dob"
                                        value="@if (isset($data->id)){{$data->dob}}@endif" required>
                                    <div class="text-danger" id="dob_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email"
                                        id="email"
                                        value="@if (isset($data->id)){{$data->email}}@endif" required>
                                    <div class="text-danger" id="email_error"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="mobile_number" class="col-form-label">Mobile Number</label>
                                    <input type="text" name="mobile_number" class="form-control"
                                        placeholder="Mobile Number" id="mobile_number"
                                        value="@if (isset($data->id)){{$data->mobile_number}}@endif" required>
                                    <div class="text-danger" id="mobile_number_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="license_expire_date" class="col-form-label">License Expire Date</label>
                                    <input type="date" name="license_expire_date" class="form-control"
                                        placeholder="License Expire Date" id="license_expire_date"
                                        value="@if (isset($data->id)){{$data->license_expire_date}}@endif"
                                        required>
                                    <div class="text-danger" id="license_expire_date_error"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="profileImage" class="col-form-label">Profile Image</label>
                                    <input type="file" name="profileImage" class="form-control file" placeholder="Profile Image"
                                        id="logo" accept=".jpeg, .jpg, .png,.gif"
                                        >
                                    {{-- <span class="text-danger"><b>Note : </b>Dimension 500px * 500px</span><br> --}}
                                    <div class="text-center">
                                        <img id="previewImg"
                                        src="<?php if(isset($data->id)){ if($data->profileImage!=''){ echo url("public/images/".$data->profileImage);}else{?> ../public/img/image-preview.png <?php }}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                                    </div>
                                    <div class="text-danger" id="image_error"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="DriverLicense" class="col-form-label">Driver License</label>
                                    <input type="file" name="DriverLicense" id="tag_icon" class="form-control file"
                                        placeholder="DriverLicense" accept=".jpeg,.jpg,.png,.gif"
                                        >
                                    {{-- <span class="text-danger"><b>Note : </b>Dimension 500px * 500px</span><br> --}}
                                    <div class="text-center">
                                        <img id="previewImgtag"
                                        src="<?php if(isset($data->id)){ if($data->DriverLicense!=''){ echo url("public/images/".$data->DriverLicense);}else{?> ../public/img/image-preview.png <?php }}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                                    </div>
                                    <div class="text-danger" id="tag_icon_error"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="DriverLicenseback" class="col-form-label">Driver License Back</label>
                                    <input type="file" name="DriverLicenseback" id="sponserlogo"
                                        class="form-control file" placeholder="DriverLicenseback" accept=".jpeg, .jpg, .png, .gif">
                                    {{-- <span class="text-danger"><b>Note : </b>Dimension 500px * 500px</span><br> --}}
                                    <div class="text-center">
                                        <img id="previewImgsponser"
                                        src="<?php if(isset($data->id)){ if($data->DriverLicenseback!=''){ echo url("public/images/".$data->DriverLicenseback);}else{?> ../public/img/image-preview.png <?php }}else{ ?>../public/img/image-preview.png<?php } ?>"
                                        alt="Placeholder" width="100px">
                                    </div>
                                    <div class="text-danger" id="sponser_error"></div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" class="id" name="id" id="id"
                                        value="@if (isset($data->id)){{$data->id}}@endif">
                                    <button type="button" class="btn btn-primary submitdata">Submit</button>
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
    var imgfile = "0";
    document.getElementById('logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;
  
        const img = new Image();
        const objectURL = URL.createObjectURL(file);
        img.src = objectURL;
        
        img.onload = function() {
          const width = img.naturalWidth;
          const height = img.naturalHeight;
          const aspectRatio = width / height;
          const validImageTypes = ['image/jpeg','image/jpg', 'image/png', 'image/gif'];
          if (!validImageTypes.includes(file.type)) {
            //messageDiv.textContent = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
            //return;
            const fileInput = document.getElementById('logo');
            fileInput.value = '';
            document.getElementById('image_error').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
            document.getElementById('previewImg').style.display = 'none';
            // Clean up if the ratio is invalid
            URL.revokeObjectURL(objectURL);
            imgfile="1";
          }else{
            document.getElementById('previewImg').src = objectURL;
            document.getElementById('previewImg').style.display = 'block';
            document.getElementById('image_error').style.display = 'none';
            imgfile="0";
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
            //     document.getElementById('image_error').style.display = 'none';
            //     imgfile="0";
            // } else {
            //     const fileInput = document.getElementById('logo');
            //     fileInput.value = '';
            //     document.getElementById('image_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
            //     document.getElementById('previewImg').style.display = 'none';
            //     // Clean up if the ratio is invalid
            //     URL.revokeObjectURL(objectURL);
            //     imgfile="1";
            // }
        };
  
        img.onerror = function() {
            document.getElementById('image_error').textContent = 'Failed to load image.';
            document.getElementById('previewImg').style.display = 'none';
        };
    });
  </script>
  <script>
    document.getElementById('sponserlogo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;
  
        const img = new Image();
        const objectURL = URL.createObjectURL(file);
        img.src = objectURL;
        
        img.onload = function() {
          const width = img.naturalWidth;
          const height = img.naturalHeight;
          const aspectRatio = width / height;
          const validImageTypes = ['image/jpeg','image/jpg', 'image/png', 'image/gif'];
          if (!validImageTypes.includes(file.type)) {
            //messageDiv.textContent = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
            //return;
            const fileInput = document.getElementById('sponserlogo');
            fileInput.value = '';
            document.getElementById('sponser_error').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
            document.getElementById('previewImgsponser').style.display = 'none';
            // Clean up if the ratio is invalid
            URL.revokeObjectURL(objectURL);
            imgfile="1";
          }else{
            document.getElementById('previewImgsponser').src = objectURL;
            document.getElementById('previewImgsponser').style.display = 'block';
            document.getElementById('sponser_error').style.display = 'none';
            imgfile="0";
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
            //     document.getElementById('previewImgsponser').src = objectURL;
            //     document.getElementById('previewImgsponser').style.display = 'block';
            // } else {
            //     const fileInput = document.getElementById('sponserlogo');
            //     fileInput.value = '';
            //     document.getElementById('sponser_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
            //     document.getElementById('previewImgsponser').style.display = 'none';
            //     // Clean up if the ratio is invalid
            //     URL.revokeObjectURL(objectURL);
            // }
        };
  
        img.onerror = function() {
            document.getElementById('sponser_error').textContent = 'Failed to load image.';
            document.getElementById('previewImgsponser').style.display = 'none';
        };
    });
  </script>
  <script>
    document.getElementById('tag_icon').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;
  
        const img = new Image();
        const objectURL = URL.createObjectURL(file);
        img.src = objectURL;
        
        img.onload = function() {
          const width = img.naturalWidth;
          const height = img.naturalHeight;
          const aspectRatio = width / height;
          const validImageTypes = ['image/jpeg','image/jpg', 'image/png', 'image/gif'];
          if (!validImageTypes.includes(file.type)) {
            //messageDiv.textContent = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
            //return;
            const fileInput = document.getElementById('tag_icon');
            fileInput.value = '';
            document.getElementById('tag_icon_error').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
            document.getElementById('previewImgtag').style.display = 'none';
            // Clean up if the ratio is invalid
            URL.revokeObjectURL(objectURL);
            imgfile="1";
          }else{
            document.getElementById('previewImgtag').src = objectURL;
            document.getElementById('previewImgtag').style.display = 'block';
            document.getElementById('tag_icon_error').style.display = 'none';
            imgfile="0";
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
            //     document.getElementById('previewImgtag').src = objectURL;
            //     document.getElementById('previewImgtag').style.display = 'block';
            // } else {
            //     const fileInput = document.getElementById('tag_icon');
            //     fileInput.value = '';
            //     document.getElementById('tag_icon_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
            //     document.getElementById('previewImgtag').style.display = 'none';
            //     // Clean up if the ratio is invalid
            //     URL.revokeObjectURL(objectURL);
            // }
        };
  
        img.onerror = function() {
            document.getElementById('tag_icon_error').textContent = 'Failed to load image.';
            document.getElementById('previewImgtag').style.display = 'none';
        };
    });
  </script>
<script>
    $('.submitdata').click(function() {
        var name = $('#name').val();
        var logo = $('#logo').val();
        var gender = $('#gender').val();
        var dob = $('#dob').val();
        var email = $('#email').val();
        var mobile_number = $('#mobile_number').val();
        var DriverLicense = $('#tag_icon').val();
        var DriverLicenseback = $('#sponserlogo').val();
        var license_expire_date = $('#license_expire_date').val();
        var id = $('#id').val();
        if (!name) {
            $('#name_error').text("The name field is required.");
            return false;
        }
        if (id == '') {
            if (!logo) {
                $('#name_error').text("");
                $('#image_error').text("The image field is required.");
                return false;
            }
        }
        if (!gender) {
            $('#name_error').text("");
            $('#image_error').text("");
            $('#gender_error').text("The gender field is required.");
            return false;
        }        
        if (!dob) {
            $('#name_error').text("");
            $('#gender_error').text("");
            $('#image_error').text("");
            $('#dob_error').text("The field is required.");
            return false;
        }        
        if (email != '') {
            if (!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                $('#name_error').text("");
                $('#gender_error').text("");
                $('#image_error').text("");
                $('#dob_error').text("");
                $('#email_error').text("Please enter valid Email");
                return false;
            }
        }
        if (mobile_number != '') {
            if (!mobile_number.match(/^[0-9]{9,12}$/)) {
                $('#name_error').text("");
                $('#gender_error').text("");
                $('#image_error').text("");
                $('#dob_error').text("");
                $('#email_error').text("");
                $('#mobile_number').text("Please enter valid Mobile number");
                return false;
            }
        }
        if (id == '') {
            if (!DriverLicense) {
                $('#name_error').text("");
                $('#gender_error').text("");
                $('#image_error').text("");
                $('#dob_error').text("");
                $('#email_error').text("");
                $('#mobile_number').text("");
                $('#tag_icon_error').text("The image field is required.");
                return false;
            }
        }
        if (id == '') {
            if (!DriverLicenseback) {
                $('#name_error').text("");
                $('#gender_error').text("");
                $('#image_error').text("");
                $('#dob_error').text("");
                $('#email_error').text("");
                $('#mobile_number').text("");
                $('#tag_icon_error').text("");
                $('#sponser_error').text("The image field is required.");
                return false;
            }
        }
        if (!license_expire_date) {
            $('#name_error').text("");
            $('#gender_error').text("");
            $('#image_error').text("");
            $('#dob_error').text("");
            $('#email_error').text("");
            $('#mobile_number').text("");
            $('#tag_icon_error').text("");
            $('#sponser_error').text("");
            $('#license_expire_date_error').text("The field is required.");
            return false;
        }      

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/insert_user') }}',
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
                        window.location.href = "{{ URL::to('/admin/userList') }}";
                    });

                }
                if ($.trim(data) == "2") {
                    $('.hide2').css('display', 'block');
                    $('.msg_danger').text("Name already exist");
                    $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

                }
                if ($.trim(data) == "3") {
                    $('#image_error').text("Invalid image dimensions must be 40px X 40px");

                }
            }

        });

    })
</script>

@include('admin.includes.footer')
