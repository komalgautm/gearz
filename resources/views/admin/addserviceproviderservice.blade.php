@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}} Form</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/serviceType') }}">{{$title}}</a></li>
              <li class="breadcrumb-item active">{{$title}} Form</li>
            </ol> -->

          @if(Session::has('message'))
          <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
          @endif
          <!-- <a href="#" class="white_btn3">Create Report</a> -->
        <div class="card px-3">
          <div class="card-body">
            <!-- <h2 class="card-title text-center pb-5">Add Brand</h2> -->
            <form method="post" id="saveform" enctype="multipart/form-data">
              @csrf
              <div class="row mb-3">
                <div class="col-sm-6">
                  <label for="name" class="col-form-label">Name (English)<em>*</em></label>
                  <input type="text" name="name" class="form-control name" placeholder="Name (English)" id="name" value="@if(isset($data->id)){{$data->name}}@endif" required>
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-sm-6">
                  <label for="name" class="col-form-label">Name (Arabic)<em>*</em></label>
                  <input type="text" name="namear" class="form-control namear" placeholder="Name (Arabic)" id="namear" value="@if(isset($data->id)){{$data->name_ar}}@endif" required>
                  <div class="text-danger" id="namear_error"></div>
                </div>
                <div class="col-sm-12">
                  <label for="image" class="col-form-label">Image<?php if(isset($data->id)){}else {
                    echo "<em>*</em>";
                  } ?></label>
                  <input type="file" name="image" class="form-control mb-2" id="image" <?php if(!isset($data->id)){ echo "required"; } ?> accept=".jpeg, .jpg, .png,.gif">
                  <span class="text-danger"><b>Note : </b>Recommended size will be 1:1</span><br>
                  <img id="previewImg" class="mt-2" src="<?php if(isset($data->id)){ echo url("public/images/".$data->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="40px">
                  <div class="text-danger" id="image_error"></div>
                </div>
                <div class="col-sm-12 mt-4">
                  <div class="text-center">
                    <input type="hidden" class="id" name="id" value="@if(isset($data->id)){{$data->id}}@endif">
                    <button type="button" class="btn btn-primary submitdata">Submit</button>
                  </div>
                </div>
                <div class="alert alert-success text-center hide1"><span class="msg_success"></span></div>
                <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span></div>
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
        const validImageTypes = ['image/jpeg','image/jpg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(file.type)) {
          //messageDiv.textContent = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
          //return;
          const fileInput = document.getElementById('image');
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
          //     const fileInput = document.getElementById('image');
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
  $('.submitdata').click(function() {
    var name = $('.name').val();
    var namear = $('.namear').val();
    var image = $('#image').val();
    var id = $('.id').val();
    if (name == '') {
      //$('.name').css('border', '1px solid red');
      $('#name_error').text("The name field is required.");
      return false;
    }
    if (namear == '') {
      $('#name_error').text("");
      $('#namear_error').text("The name field is required.");
      return false;
    }
    if(id ==''){
      if (!image) {
        $('#name_error').text("");
        $('#namear_error').text("");
        $('#image_error').text("The image field is required.");
        return false;
      }
    }else if(imgfile =="1"){
        $('#name_error').text("");
        $('#namear_error').text("");
        $('#image_error').text("The image field is required.");
        return false;
    }
      //$('.name').css('border', '');
      $.ajax({
        type: 'POST',
        url: '{{url("/admin/insert_serviceproviderservice")}}',
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
              window.location.href = "{{URL::to('/admin/serviceproviderservices')}}";
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
