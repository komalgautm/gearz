
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Welcome image settings</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/banners') }}">Banners</a></li>
              <li class="breadcrumb-item active"></li>
            </ol> -->

        <div class="card p-4">
          <div class="card-body">
            <!-- @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif -->
            <p class="alert alert-success" style="display:none" id="msg"></p>

            <!-- <a href="#" class="white_btn3">Create Report</a>style="border: 1px solid #d7d7d7;" -->

            <!-- <h2 class="card-title text-center pb-5">Banner</h2> -->
            <div class="customCheck">
              <form id="formData" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="image" class="col-form-label">Background Image<em>*</em></label>
                    <input type="file" id="imageInput" name="image" accept="image/*" class="form-control mb-2">
                    {{-- <span class="text-danger"><b>Note : </b>Recommended size will be 0.46:1</span><br> --}}
                    <p class="text-danger" id="message"></p>
                    <img id="preview" src="<?php if(isset($data)){ echo url("public/img/welcome_images/".$data->backgroundimage);}else{ ?>../public/img/image-preview.png<?php } ?>" style="max-width: 100%;">
                    <?php if(isset($data)){ ?>
                      <input type="hidden" name="banner_id" id="banner_id" value="{{ $data->id }}">
                    <?php }else{ ?>
                      <input type="hidden" name="banner_id" id="banner_id" value="">
                    <?php } ?>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 text-center">
                    <button type="button" onclick="get_form_data()" class="btn btn-primary">Submit</button>
                  </div>
                </div>
                {{-- <div class="row">
                  {{-- <div class="col-sm-2">
                    <div class="form-group">
                      <input type="checkbox" id="skippable" class="skipable" name="skip" <?php if(isset($data)){ if($data->skipable==1){ echo "Checked"; } } ?>>
                      <label for="skippable" class="col-form-label">Skippable</label>
                    </div>
                  </div> --}}
                  {{-- <div class="col-sm-2">
                    <div class="form-group">
                      <input type="checkbox" id="slidable" class="sliding" name="slidable" <?php if(isset($data)){ if($data->sliding==1){ echo "Checked"; } } ?>>
                      <label for="slidable" class="col-form-label">Automatic</label>
                    </div>
                  </div>
                  <div class="col-sm-2 text-center">
                    <button type="button" onclick="get_form_data()" class="btn btn-primary">Submit</button>
                  </div> --
                </div> --}}
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById('imageInput').addEventListener('change', function(event) {
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
          const fileInput = document.getElementById('imageInput');
          fileInput.value = '';
          document.getElementById('message').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
          document.getElementById('preview').style.display = 'none';
          // Clean up if the ratio is invalid
          URL.revokeObjectURL(objectURL);
          imgfile="1";
        }else{
          document.getElementById('preview').src = objectURL;
          document.getElementById('preview').style.display = 'block';
          document.getElementById('message').style.display = 'none';
          imgfile="0";
        }
          // const width = img.naturalWidth;
          // const height = img.naturalHeight;
          // const aspectRatio = width / height;

          // // Define your desired aspect ratio
          // //const desiredAspectRatio = 16 / 9;
          // const desiredAspectRatio = 0.46 / 1;
          // const tolerance = 0.03;  // Adjust the tolerance as needed
          // console.log(Math.abs(aspectRatio - desiredAspectRatio));
          // if (Math.abs(aspectRatio - desiredAspectRatio) < tolerance) {
          //     //document.getElementById('message').textContent = 'Image aspect ratio is valid!';
          //     document.getElementById('preview').src = objectURL;
          //     document.getElementById('preview').style.display = 'block';
          //     document.getElementById('message').style.display = 'none';
          // } else {
          //     const fileInput = document.getElementById('imageInput');
          //     fileInput.value = '';
          //     document.getElementById('message').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
          //     document.getElementById('preview').style.display = 'none';
          //     // Clean up if the ratio is invalid
          //     URL.revokeObjectURL(objectURL);
          // }
      };

      img.onerror = function() {
          document.getElementById('message').textContent = 'Failed to load image.';
          document.getElementById('preview').style.display = 'none';
      };
  });
</script>
<script>
  function get_form_data(){
    var banner_id = $('#banner_id').val();
    var image = $('#imageInput').val();
    if(!image){
      $('#message').text("The image field is required.");
      return false;
    }

    $.ajax({
      type:"POST",
      url:"{{url('admin/image_setting_insert')}}",
      data:new FormData( $("#formData")[0]),
      async : false,
      contentType : false,
      cache : false,
      processData: false,
      success:function(data)
      {
        console.log(data);
        if($.trim(data)=="update")
        {
          $(window).scrollTop(0);
          $('#msg').show();
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Banner updated successfully.');
          $('#image_error').html('');
          $('#name_error').html('');
          $('#start_date_error').html('');
          $('#end_date_error').html('');
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/image_setting'); ?>";
          }, 5000);
        }
        else if($.trim(data)=="add")
        {
          $(window).scrollTop(0);
          $('#msg').show();
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Banner added successfully.');
          $('#formData')[0].reset();
          $('#image_error').html('');
          $('#name_error').html('');
          $('#start_date_error').html('');
          $('#end_date_error').html('');
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/image_setting'); ?>";
          }, 5000);
        }
      }

    });
 // }
  }
</script>
{{-- <script>
  function get_form_data(){
    if ($('input.skipable').is(':checked')) {
      var skipable = 1;
    }else{
      var skipable = 0;
    }
    if ($('input.sliding').is(':checked')) {
      var sliding = 1;
    }else{
      var sliding = 0;
    }
    var token = "<?=csrf_token()?>";
    $.ajax({
      type:"POST",
      url:"{{url('admin/image_setting_insert')}}",
      data:{skipable:skipable,sliding:sliding,_token:token},
      success:function(data)
      {
        if($.trim(data)=="update")
        {
          $(window).scrollTop(0);
          $('#msg').show();
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Updated successfully.');
          $('#image_error').html('');
          $('#name_error').html('');
          $('#start_date_error').html('');
          $('#end_date_error').html('');
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/image_setting'); ?>";
          }, 5000);
        }
        else if($.trim(data)=="add")
        {
          $(window).scrollTop(0);
          $('#msg').show();
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Banner added successfully.');
          $('#formData')[0].reset();
          $('#image_error').html('');
          $('#name_error').html('');
          $('#start_date_error').html('');
          $('#end_date_error').html('');
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/image_setting'); ?>";
          }, 5000);
        }else{
          var obj=JSON.parse(data);
          if(obj.image){
            $('#image_error').html(obj.image);
            $('#name_error').html('');
            $('#start_date_error').html('');
            $('#end_date_error').html('');
          }else if(obj.name){
            $('#image_error').html('');
            $('#name_error').html(obj.name);
            $('#start_date_error').html('');
            $('#end_date_error').html('');
          }else if(obj.start_date){
            $('#image_error').html('');
            $('#name_error').html('');
            $('#start_date_error').html(obj.start_date);
            $('#end_date_error').html('');
          }else if(obj.end_date){
            $('#image_error').html('');
            $('#name_error').html('');
            $('#start_date_error').html('');
            $('#end_date_error').html(obj.end_date);
          }
        }
      }

    });
 // }
  }
</script> --}}

@include('admin.includes.footer')
