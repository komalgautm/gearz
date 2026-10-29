
@include('admin.includes.header')
<?php
  if(isset($welcome_image)){
    if($welcome_image->internal_external==1){
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
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> <?php if(isset($welcome_image)){ echo "Edit"; }else{ echo "Add"; } ?> Welcome Image</h4>

        <div class="card p-4">
          <div class="card-body">
            <!-- @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif -->
            <p class="alert alert-success" style="display:none" id="msg"></p>

            <!-- <a href="#" class="white_btn3">Create Report</a> -->
              <!-- <h2 class="card-title text-center pb-5"><?php if(isset($welcome_image)){ echo "Update"; }else{ echo "Add"; } ?> Welcome Image</h2> -->
            <form id="formData" enctype="multipart/form-data">
              @csrf

              <div class="row mb-3">
                <div class="col-sm-6">
                  <label for="name" class="col-form-label">Name (English)<em>*</em></label>
                  <input type="text" name="name" class="form-control" placeholder="Name (English)" id="name" value="<?php if(isset($welcome_image)){ echo $welcome_image->name; } ?>">
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-sm-6">
                  <label for="namear" class="col-form-label">Name (Arabic)<em>*</em></label>
                  <input type="text" name="namear" class="form-control" placeholder="Name (Arabic)" id="namear" value="<?php if(isset($welcome_image)){ echo $welcome_image->name_ar; } ?>">
                  <div class="text-danger" id="namear_error"></div>
                </div>

                {{--  --}}
              </div>
              <div class="row mb-3">
                <div class="col-sm-12">
                  {{-- radio button --}}
                  <div class="form-check form-check-inline">
                    <input class="form-check-inputs" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" <?php if(isset($welcome_image)){ if($welcome_image->internal_external==1){ echo"checked"; } }else{ echo"checked"; } ?> >
                    <label class="form-check-label" for="inlineRadio1">External URL</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-inputs" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" <?php if(isset($welcome_image)){ if($welcome_image->internal_external==2){ echo"checked"; } } ?>>
                    <label class="form-check-label" for="inlineRadio2">Internal Page</label>
                  </div>
                </div>
              </div>
              <div class="row mb-3 externalpage">
                <div class="col-sm-12">
                  <label for="url" class="col-form-label">URL</label>
                  <input type="url" name="url" class="form-control" placeholder="URL" id="url" value="<?php if(isset($welcome_image)){ if($welcome_image->internal_external==2){echo "";}else{echo $welcome_image->url;} } ?>">
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
                    <option <?php if(isset($welcome_image)){ if($welcome_image->internal_external==2){ if($welcome_image->url==$page->id){ echo"Selected"; } } } ?> value="{{$page->id}}">{{$page->name}}</option>
                    @endforeach

                  </Select>
                </div>
              </div>
              {{-- page --}}
              <div class="row mb-3">
                <div class="col-sm-6">
                  <label for="start_date" class="col-form-label">Start Date<em>*</em></label>
                  <input type="date" name="start_date" class="form-control" id="start_date" min="<?=date('Y-m-d')?>" value="<?php if(isset($welcome_image)){ echo $welcome_image->start_date; }else{ echo date('Y-m-d'); } ?>">
                  <div class="text-danger" id="start_date_error"></div>
                </div>

                <div class="col-sm-6">
                  <label for="end_date" class="col-form-label">Expiry Date<em>*</em></label>
                  <input type="date" name="end_date" class="form-control" id="end_date" min="<?=date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'))?>" value="<?php if(isset($welcome_image)){ echo $welcome_image->end_date; }else{ echo date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day')); } ?>">
                  <div class="text-danger" id="end_date_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="info" class="col-form-label">Description (English)<em>*</em></label>
                  <textarea name="info" id="info" cols="30" rows="5" class="form-control" placeholder="Description (English)"><?php if(isset($welcome_image)){ echo $welcome_image->discription; } ?></textarea>
                  <div class="text-danger" id="info_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="infoar" class="col-form-label">Description (Arabic)<em>*</em></label>
                  <textarea name="infoar" id="infoar" cols="30" rows="5" class="form-control" placeholder="Description (Arabic)"><?php if(isset($welcome_image)){ echo $welcome_image->discription_ar; } ?></textarea>
                  <div class="text-danger" id="infoar_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="image_time" class="col-form-label">Image Time (Second)<em>*</em></label>
                  <select name="image_time" id="image_time" class="form-select">
                    <option value="">Select time</option>
                    <?php for ($i=1; $i <=10; $i++) { ?>
                     <option value="{{$i}}" <?php if(isset($welcome_image)){ if($welcome_image->image_time==$i){ echo "Selected"; }  } ?>>{{$i}}</option>
                   <?php } ?>
                  </select>

                  <div class="text-danger" id="image_time_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-12">
                  <label for="image" class="col-form-label">Upload Image<?php if(isset($welcome_image)){}else {
                    echo "<em>*</em>";
                  } ?></label>
                  <input type="file" name="image" class="form-control mb-2" id="image" accept=".jpeg, .jpg, .png,.gif" <?php if(!isset($welcome_image)){ echo "required"; } ?>>
                  <span class="text-danger"><b>Note : </b>Recommended image size is 9:18 aspect ratio (e.g., 720×1440 pixels). Maximum file size: 2MB.</span><br>
                  <div class="text-center">
                    <img id="preview" class="my-2" src="<?php if(isset($welcome_image)){ echo url("public/img/welcome_images/".$welcome_image->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                  </div>
                  <?php if(isset($welcome_image)){ ?>
                  <input type="hidden" name="welcome_image_id" id="welcome_image_id" value="{{ $welcome_image->id }}">
                  <input type="hidden" name="pre_image" value="{{ $welcome_image->image }}">
                  <!-- <img src="{{ url('public/img/welcome_images') }}/{{ $welcome_image->image }}" alt="Welcome Image" width="150" height="100"> -->
                  <?php }else{ ?>
                    <input type="hidden" name="welcome_image_id" id="welcome_image_id" value="">
                    <?php } ?>
                  <div class="text-danger" id="image_error"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2">
                  <div class="form-group">
                    <input type="radio" id="skippable" class="skipable" name="skip" value="0" <?php if(isset($welcome_image)){ if($welcome_image->skipable==0){ echo "Checked"; }}else{ echo"Checked"; } ?>>
                    <label for="skippable" class="col-form-label">Skippable</label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <input type="radio" id="slidable" class="sliding" name="skip" value="1" <?php if(isset($welcome_image)){ if($welcome_image->skipable==1){ echo "Checked"; }} ?>>
                    <label for="slidable" class="col-form-label">Automatic</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <button type="button" onclick="get_form_data()" class="btn btn-primary">Submit</button>
                  <div id="submitError" class="text-danger mt-3"></div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
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
          document.getElementById('preview').style.display = 'none';
          // Clean up if the ratio is invalid
          URL.revokeObjectURL(objectURL);
          imgfile="1";
        }else{
          document.getElementById('preview').src = objectURL;
          document.getElementById('preview').style.display = 'block';
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
          //     document.getElementById('preview').src = objectURL;
          //     document.getElementById('preview').style.display = 'block';
          //     document.getElementById('image_error').style.display = 'none';
          //     imgfile="0";
          // } else {
          //     const fileInput = document.getElementById('image');
          //     fileInput.value = '';
          //     document.getElementById('image_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
          //     document.getElementById('preview').style.display = 'none';
          //     // Clean up if the ratio is invalid
          //     URL.revokeObjectURL(objectURL);
          //     imgfile="1";
          // }
      };

      img.onerror = function() {
          document.getElementById('image_error').textContent = 'Failed to load image.';
          document.getElementById('preview').style.display = 'none';
      };
  });
</script>
<script>
  function get_form_data(){
    var banner_id = $('#welcome_image_id').val();
    var name = $('#name').val();
    var info = $('#info').val();
    var image = $('#image').val();
    var start_date = new Date($('#start_date').val());
    var end_date = new Date($('#end_date').val());
    //alert(imgfile);
    if(banner_id==''){
      if(!image){
        $('#name_error').text("");
        $('#image_error').text("The image field is required.");
        return false;
      }
    }else if(imgfile =="1"){
      //alert();
      $('#name_error').text("");
      $('#image_error').text("The image field is required.");
        return false;
    }

    if(!name){
      $('#name_error').text("The name field is required.");
      return false;
    }
    if(!info){
      $('#name_error').text("");
      $('#info_error').text("This field is required.");
      return false;
    }
    //alert(12);

    if(new Date(start_date) > new Date(end_date)){
      $('#start_date_error').text("Start Date must be less then Expiry Date");
      return false;
    }
    $.ajax({
      type:"POST",
      url:"{{url('admin/insertWelcomeImage')}}",
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
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Welcome Image updated successfully.');
          $('#image_error').html('');
          $('#name_error').html('');
          $('#start_date_error').html('');
          $('#end_date_error').html('');
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/welcome_images'); ?>";
          }, 5000);
        }
        else if($.trim(data)=="add")
        {
          $(window).scrollTop(0);
          $('#msg').show();
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Welcome Image added successfully.');
          $('#formData')[0].reset();
          $('#image_error').html('');
          $('#name_error').html('');
          $('#start_date_error').html('');
          $('#end_date_error').html('');
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/welcome_images'); ?>";
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

@include('admin.includes.footer')
