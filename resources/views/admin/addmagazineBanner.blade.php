
@include('admin.includes.header')
<?php
  if(isset($banner)){
    if($banner->internal_external==1){
?>
<style>
  .inetrnalpage{
    display: none;
  }
  .externalpage{
    display: block;
  }
</style>
<?php
    }else{
?>
<style>
  .inetrnalpage{
    display: block;
  }
  .externalpage{
    display: none;
  }
  .image_container{
    max-width: 800px;
    max-height: 450px;
  }
</style>
<?php
    }
  }else{
?>
<style>
  .inetrnalpage{
    display: none;
  }
</style>
<?php } ?>

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"> <a href="javascript:history.back()" class="back_button" title="Back"> <i class="fa fa-arrow-left"></i> </a><?php if(isset($banner)){ echo "Edit"; }else{ echo "Add"; } ?> Banner</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/banners') }}">Banners</a></li>
              <li class="breadcrumb-item active"><?php if(isset($banner)){ echo "Update"; }else{ echo "Add"; } ?> Banner</li>
            </ol> -->
        <div class="card p-4">
          <div class="card-body">
            <!-- @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif -->
            <p class="alert alert-success" style="display:none" id="msg"></p>

            <!-- <a href="#" class="white_btn3">Create Report</a>style="border: 1px solid #d7d7d7;" -->
            <!-- <h2 class="card-title text-center pb-5"><?php if(isset($banner)){ echo "Update"; }else{ echo "Add"; } ?> Banner</h2> -->
            <form id="formData" enctype="multipart/form-data">
              @csrf

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="name" class="col-form-label">Name (English)<em>*</em></label>
                  <input type="text" name="name" class="form-control" placeholder="Name (English)" id="name" value="<?php if(isset($banner)){ echo $banner->name; } ?>">
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="namear" class="col-form-label">Name (Arabic)<em>*</em></label>
                  <input type="text" name="namear" class="form-control" placeholder="Name (Arabic)" id="namear" value="<?php if(isset($banner)){ echo $banner->name_ar; } ?>">
                  <div class="text-danger" id="namear_error"></div>
                </div>

              </div>
              <div class="row mb-3">
                <div class="col-sm-12">
                  {{-- radio button --}}
                  <div class="form-check form-check-inline">
                    <input class="form-check-inputs" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" <?php if(isset($banner)){ if($banner->internal_external==1){ echo"checked"; } }else{ echo"checked"; } ?> >
                    <label class="form-check-label" for="inlineRadio1">External URL</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-inputs" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" <?php if(isset($banner)){ if($banner->internal_external==2){ echo"checked"; } } ?>>
                    <label class="form-check-label" for="inlineRadio2">Internal Page</label>
                  </div>
                </div>
              </div>
              <div class="row mb-3 externalpage">
                <div class="col-sm-12">
                  <label for="url" class="col-form-label">URL</label>
                    <input type="url" name="url" class="form-control" placeholder="URL" id="url" value="<?php if(isset($banner)){ if($banner->internal_external==2){echo "";}else{echo $banner->url;} } ?>">
                    <div class="text-danger" id="url_error"></div>
                </div>
              </div>
              {{-- page --}}
              <div class="row mb-3 inetrnalpage">
                <div class="col-sm-12">
                  <label for="url" class="col-form-label">Page</label>
                  <Select class="form-select" name="page" id="page">
                    <option value="">Choose</option>
                    @foreach($InternalAppPage as $page)
                    <option <?php if(isset($banner)){ if($banner->internal_external==2){ if($banner->url==$page->id){ echo"Selected"; } } } ?> value="{{$page->id}}">{{$page->name}}</option>
                    @endforeach

                  </Select>
                </div>
              </div>
              {{-- page --}}
              {{-- <div class="row mb-3">
                <div class="col-md-6">
                  <label for="url" class="col-form-label">URL</label>
                  <input type="url" name="url" class="form-control" placeholder="URL" id="url" value="<?php if(isset($banner)){ echo $banner->url; } ?>">
                  <div class="text-danger" id="url_error"></div>
                </div>
              </div> --}}
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="start_date" class="col-form-label">Start Date<em>*</em></label>
                  <input type="date" name="start_date" class="form-control" id="start_date" min="<?=date('Y-m-d')?>" value="<?php if(isset($banner)){ echo $banner->start_date; }else{ echo date('Y-m-d'); } ?>">
                  <div class="text-danger" id="start_date_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="end_date" class="col-form-label">Expiry Date<em>*</em></label>
                  <input type="date" name="end_date" class="form-control" id="end_date" min="<?=date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'))?>" value="<?php if(isset($banner)){ echo $banner->end_date; }else{ echo date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day')); } ?>">
                  <div class="text-danger" id="end_date_error"></div>
                </div>
              </div>
              <!-- <div class="row my-3">
                <div class="col-md-12">
                  <input type="file" name="image" class="form-control"id="image" onchange="readURL(this);"/>

                  <div class="image_container">
                      <img id="blah" src="#" class="form-control" alt="your image" />
                  </div>

                  <div id="cropped_result"></div>  
                  <button id="crop_button">Crop</button>
                  <div class="text-danger" id="image_error"></div>
                </div>
              </div> -->
              
              <div class="row my-3">
                <div class="col-md-12">
                  <label for="image" class="col-form-label">Upload Image<?php if(isset($banner)){}else { echo "<em>*</em>"; } ?></label>

                  <input type="file" name="image" class="form-control mb-2" id="image" accept="image/*" <?php if(!isset($banner)){ echo "required"; } ?>>

                  <span class="text-danger"><b>Note : </b>Recommended image size is 16:9 aspect ratio (e.g., 1280×720 pixels). Maximum file size: 2MB.</span><br>
                                        
                  <div class="text-center">

                    <img id="previewImg" class="mt-2" src="<?php if(isset($banner)){ echo url("public/img/banners/".$banner->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">

                  </div>

                  <?php if(isset($banner)){ ?>

                  <input type="hidden" name="banner_id" id="banner_id" value="{{ $banner->id }}">

                  <input type="hidden" name="pre_image" value="{{ $banner->image }}">

                  <!-- <img src="{{ url('public/img/banners') }}/{{ $banner->image }}" alt="Banner Image" width="150" height="100"> -->
                  <?php }else{ ?>

                    <input type="hidden" name="banner_id" id="banner_id" value="">
                    <?php } ?>

                  <div class="text-danger" id="image_error"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center mt-3">
                  <button type="button" onclick="get_form_data()" class="btn btn-primary">Submit</button>
                  <div id="submitError" class="text-danger mt-3"></div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('input[type=radio][name=inlineRadioOptions]').change(function() {
    if (this.value == '1') {
        $('.externalpage').css('display','block');
        $('.inetrnalpage').css('display','none');
    }
    else if (this.value == '2') {
      $('.externalpage').css('display','none');
      $('.inetrnalpage').css('display','block');
    }
});
</script>
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
          //     document.getElementById('image_error').style.display = 'block';
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
  function get_form_data(){
    var banner_id = $('#banner_id').val();
    var name = $('#name').val();
    var namear = $('#namear').val();
    var image = $('#image').val();
    var start_date = new Date($('#start_date').val());
    var end_date = new Date($('#end_date').val());
    if(!name){
      $('#name_error').text("The name field is required.");
      return false;
    }
    if(!namear){
      $('#name_error').text("");
      $('#namear_error').text("The name field is required.");
      return false;
    }
    if(banner_id==''){
      if(!image){
        $('#name_error').text("");
        $('#image_error').text("The image field is required.");
        return false;
      }
    }else if(imgfile=="1"){
      $('#name_error').text("");
      $('#image_error').text("The image field is required.");
      return false;
    }
    if(new Date(start_date) > new Date(end_date)){
      $('#start_date_error').text("Start Date must be less then Expiry Date");
      return false;
    }
    $('#start_date_error').text("");
    $.ajax({
      type:"POST",
      url:"{{url('admin/insertmagazineBanner')}}",
      data:new FormData( $("#formData")[0]),
      async : false,
      contentType : false,
      cache : false,
      processData: false,
      success:function(data)
      {
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
            window.location.href = "<?php echo url('admin/magazineBanners'); ?>";
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
            window.location.href = "<?php echo url('admin/magazineBanners'); ?>";
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
      },
      error: function(xhr) {
          if (xhr.status === 422) {
              $('#submitError').text(xhr.responseJSON.error);
          } else {
              alert('Something went wrong. Please try again.');
          }
      }

    });
  }
</script>
<script>
  function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#blah').attr('src', e.target.result)
    };
    reader.readAsDataURL(input.files[0]);
    setTimeout(initCropper, 1000);
  }
}
function initCropper(){
  console.log("Came here")
  var image = document.getElementById('blah');
  var cropper = new Cropper(image, {
    aspectRatio: 16 / 9,
    crop: function(e) {
      console.log(e.detail.x);
      console.log(e.detail.y);
    }
  });

  // On crop button clicked
  document.getElementById('crop_button').addEventListener('click', function(){
    var imgurl =  cropper.getCroppedCanvas().toDataURL();
    var img = document.createElement("img");
    img.src = imgurl;
    document.getElementById("cropped_result").appendChild(img);

    /* ---------------- SEND IMAGE TO THE SERVER-------------------------

                cropper.getCroppedCanvas().toBlob(function (blob) {
                      var formData = new FormData();
                      formData.append('croppedImage', blob);
                      // Use `jQuery.ajax` method
                      $.ajax('/path/to/upload', {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function () {
                          console.log('Upload success');
                        },
                        error: function () {
                          console.log('Upload error');
                        }
                      });
                });
            ----------------------------------------------------*/
  })
}
</script>

@include('admin.includes.footer')
