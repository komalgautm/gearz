@include('admin.includes.header')
<style>
    .brand {
        display: none;
    }
    .age {
        display: none;
    }
    .gender {
        display: none;
    }
    .city {
        display: none;
    }
    .statecity {
        display: none;
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
                                <div class="col-sm-6">
                                    <label for="name" class=" col-form-label">Title (English)</label>
                                    <input type="text" name="name" class="form-control name" placeholder="Title"
                                        id="name"
                                        value="@if(isset($data->id)){{$data->name}}@endif" required>
                                    <div class="text-danger" id="name_error"></div>
                                </div>                                
                                <div class="col-sm-6">
                                    <label for="namear" class=" col-form-label">Title (Arabic)</label>
                                    <input type="text" name="namear" class="form-control namear" placeholder="Title"
                                        id="namear"
                                        value="@if(isset($data->id)){{$data->name_ar}}@endif" required>
                                    <div class="text-danger" id="namear_error"></div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="row mb-3">                                    
                                    <div class="col-md-12">
                                        <label for="sponser_icon" class="col-form-label">Image</label>
                                        <input type="file" name="sponser_icon" id="sponserlogo" class="form-control file mb-2" placeholder="Logo" accept=".jpeg, .jpg, .png, .gif"  onchange="sponserFiles(this);">
                                        <span class="text-danger"><b>Note : </b>Recommended image size is 16:9 aspect ratio (e.g., 1280×720 pixels). Maximum file size: 2MB.</span><br>
                                        <img id="previewImgss" class="mt-2" src="<?php if(isset($data->id)){ if($data->image!=''){ echo url("public/images/".$data->image);}else{?> ../public/img/image-preview.png <?php }}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                                        <div class="text-danger" id="sponser_error"></div>
                                    </div>
                                </div>                          

                                <div class="col-sm-12 text-center mt-4">
                                    <input type="hidden" class="id" name="id"
                                        value="@if (isset($data->id)) {{ $data->id }} @endif">
                                    <button type="button" class="btn submitdata">Submit</button>
                                    <div id="submitError" class="text-danger mt-3"></div>
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
    function sponserFiles(input) {
       var files = $("#sponserlogo").get(0).files[0];
 
       if (files) {
          var readers = new FileReader();
 
          readers.onload = function() {
             $("#previewImgss").attr("src", readers.result);
          }
 
          readers.readAsDataURL(files);
       }
    }
 </script>
<script>
  $('.submitdata').click(function() {
    var name = $('#name').val();
    var namear = $('#namear').val();   
    if (!name) {       
        $('#name_error').text("The title field is required.");
        return false;
    }
    if (!namear) {       
        $('#name_error').text("");
        $('#namear_error').text("The title field is required.");
        return false;
    }           
    $('#name_error').text("");
    $('#namear_error').text("");
    $.ajax({
        type: 'POST',
        url: '{{ url('/admin/addknowledgeimagesectionupdate') }}',
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
                    window.location.href = "{{ URL::to('/admin/knowledgeimagesection') }}";
                    //location.reload();
                });

            }
            if ($.trim(data) == "2") {
              $('#end_time_error').text("Time must me be greater then current time for current date");
                // $('.hide2').css('display', 'block');
                // $('.msg_danger').text("Name already exist");
                // $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                $('#submitError').text(xhr.responseJSON.error);
            } else {
                alert('Something went wrong. Please try again.');
            }
        }

    });

  })
</script>


@include('admin.includes.footer')
