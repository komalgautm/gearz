
@include('admin.includes.header')
<style>
  .img-div {
    position: relative;
    /* width: 46%; */
    float:left;
    margin-right:5px;
    margin-left:5px;
    margin-bottom:10px;
    margin-top:10px;
}

.image {
    opacity: 1;
    display: block;
    width: 100%;
    max-width: auto;
    transition: .5s ease;
    backface-visibility: hidden;
}

.middle {
    transition: .5s ease;
    opacity: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    text-align: center;
}

.img-div:hover .image {
    opacity: 0.3;
}

.img-div:hover .middle {
    opacity: 1;
}
</style>
<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"> <a href="javascript:history.back()" class="back_button" title="Back"> <i class="fa fa-arrow-left"></i> </a> <?php if(isset($data->id)){ echo "Edit"; }else{ echo "Add"; } ?> Agent / Reseller</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/brands') }}">Agent</a></li>
              <li class="breadcrumb-item active">Add Agent</li>
            </ol> -->
        <div class="card p-4">
          <div class="card-body">
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            <!-- <a href="#" class="white_btn3">Create Report</a> -->
            <!-- <h2 class="card-title text-center pb-5">Add Brand</h2> -->
            <form method="post" id="saveform" enctype="multipart/form-data">
              @csrf
              <div class="row mb-3 ui">
                <div class="col-md-6">
                  <label for="name" class="col-form-label">Name (English)<em>*</em></label>
                  <input type="text" name="name" class="form-control name" placeholder="Name (English)" id="name" value="@if(isset($data->id)){{$data->name}}@endif" required>
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="namear" class="col-form-label">Name (Arabic)<em>*</em></label>
                  <input type="text" name="namear" class="form-control namear" placeholder="Name (Arabic)" id="namear" value="@if(isset($data->id)){{$data->name_ar}}@endif" required>
                  <div class="text-danger" id="namear_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="logo" class="col-form-label">Logo<?php if(isset($data->id)){}else { echo "<em>*</em>"; } ?></label>
                  <input type="file" name="image" class="form-control file mb-2" placeholder="Logo" id="logo" accept=".jpeg, .jpg, .png, .gif">
                  <span class="text-danger"><b>Note : </b>Recommended image size is 1:1 aspect ratio (e.g., 500×500 pixels). Maximum file size: 2MB.</span><br>
                  <img id="previewImg" class="mt-2" src="<?php if(isset($data->id)){ echo url("public/images/".$data->logo);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                  <div class="text-danger" id="logo_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="imagess" class="col-form-label">Image<?php if(isset($data->id)){}else { echo "<em>*</em>"; } ?></label>
                  <input type="file" name="imagess" class="form-control files mb-2" placeholder="images" id="imagess" accept=".jpeg, .jpg, .png, .gif">
                  <span class="text-danger"><b>Note : </b>Recommended image size is 16:9 aspect ratio (e.g., 1280×720 pixels). Maximum file size: 2MB.</span><br>
                  <img id="previewImgs" class="mt-2" src="<?php if(isset($data->id)){ echo url("public/images/".$data->images);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                  <div class="text-danger" id="imagess_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="website" class="col-form-label">Brand<em>*</em></label>
                  <select name="brands[]" multiple="" id="brands" class="js-example-basic-single form-select">
                    @foreach($brand as $brandvalue)
                    <option value="{{$brandvalue->id}}" <?php if(isset($data->id)){ foreach($agentbrand as $agval){ if($agval->brandID==$brandvalue->id){ echo"Selected"; }}}?>>{{$brandvalue->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="brands_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="email" class="col-form-label">Email<em>*</em></label>
                  <input type="text" name="email" class="form-control" placeholder="Email" id="email" value="@if(isset($data->id)){{$data->email}}@endif" required>
                  <div class="text-danger" id="email_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="mobile" class="col-form-label">Mobile No.<em>*</em></label>
                  <input type="text" name="mobile" class="form-control" placeholder="Mobile No." id="mobile" value="@if(isset($data->id)){{$data->contact}}@endif" required>
                  <div class="text-danger" id="mobile_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="whatsapp" class="col-form-label">WhatsApp Number</label>
                  <input type="text" name="whatsapp" class="form-control" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="WhatsApp Number" id="whatsapp" value="@if(isset($data->id)){{$data->whatsapp}}@endif" required>
                  <div class="text-danger" id="whatsapp_error"></div>
                </div>
                <div class="col-md-12">
                  <label for="website" class="col-form-label">Website</label>
                  <input type="text" name="website" class="form-control" placeholder="Website" id="website" value="@if(isset($data->id)){{$data->website}}@endif" required>
                  <div class="text-danger" id="website_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="youtube" class="col-form-label">Youtube</label>
                  <input type="text" name="youtube" class="form-control" placeholder="Youtube" id="youtube" value="@if(isset($data->id)){{$data->youtube}}@endif" required>
                  <div class="text-danger" id="youtube_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="twitter" class="col-form-label">Twitter</label>
                  <input type="text" name="twitter" class="form-control" placeholder="Twitter" id="twitter" value="@if(isset($data->id)){{$data->twitter}}@endif" required>
                  <div class="text-danger" id="twitter_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="linkedin" class="col-form-label">Linkedin</label>
                  <input type="text" name="linkedin" class="form-control" placeholder="Linkedin" id="linkedin" value="@if(isset($data->id)){{$data->linkedin}}@endif" required>
                  <div class="text-danger" id="linkedin_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="facebook" class="col-form-label">Facebook</label>
                  <input type="text" name="facebook" class="form-control" placeholder="Facebook" id="facebook" value="@if(isset($data->id)){{$data->facebook}}@endif" required>
                  <div class="text-danger" id="facebook_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="instagram" class="col-form-label">Instagram</label>
                  <input type="text" name="instagram" class="form-control" placeholder="Instagram" id="instagram" value="@if(isset($data->id)){{$data->instagram}}@endif" required>
                  <div class="text-danger" id="instagram_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="snapchat" class="col-form-label">Snapchat</label>
                  <input type="text" name="snapchat" class="form-control" placeholder="Snapchat" id="snapchat" value="@if(isset($data->id)){{$data->snap_chat}}@endif" required>
                  <div class="text-danger" id="snapchat_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="info" class="col-form-label">Description (English)</label>
                  <textarea name="info" id="info" cols="30" rows="5" class="form-control" placeholder="Description (English)">@if(isset($data->id)){{$data->description}}@endif</textarea>
                </div>
                <div class="col-md-6">
                  <label for="infoar" class="col-form-label">Description (Arabic)</label>
                  <textarea name="infoar" id="infoar" cols="30" rows="5" class="form-control" placeholder="Description (Arabic)">@if(isset($data->id)){{$data->discription_ar}}@endif</textarea>
                </div>
                <div class="col-md-6">
                  <label for="tag_name" class="col-form-label">Tag Name (English)</label>
                  <input type="text" name="tag_name" class="form-control" placeholder="Tag Name (English)" id="tag_name" value="@if(isset($data->id)){{$data->tag_name}}@endif" required>
                  <div class="text-danger" id="tag_name_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="tag_namear" class="col-form-label">Tag Name (Arabic)</label>
                  <input type="text" name="tag_namear" class="form-control" placeholder="Tag Name (Arabic)" id="tag_namear" value="@if(isset($data->id)){{$data->tag_name_ar}}@endif" required>
                  <div class="text-danger" id="tag_namear_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="tag_icon" class="col-form-label">Tag Icon</label>
                  <input type="file" name="tag_icon" id="tag_icon" class="form-control file" placeholder="Logo" accept=".jpeg,.jpg,.png,.gif"  onchange="previewFiletag(this,20,20);">
                  <span class="text-danger"><b>Note : </b>Recommended size will be 1:1</span><br>
                  <img id="previewImgtag" src="<?php if(isset($data->id)){ if($data->tag_icon!=''){ echo url("public/images/".$data->tag_icon);}else{?> ../public/img/image-preview.png <?php }}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                  <div class="text-danger" id="tag_icon_error"></div>
                </div>
                <div class="col-md-12">
                  <label for="images" class="col-form-label">Banner Images</label>
                  <input type="file" name="images[]" id="images" multiple class="form-control" accept=".jpeg, .jpg, .png,.gif">
                  <span class="text-danger"><b>Note : </b>Recommended image size is 16:9 aspect ratio (e.g., 1280×720 pixels). Maximum file size: 2MB.</span><br>
                </div>
                <div class="form-group">
                  <?php if(isset($data->id)){
                    foreach($brandbanner as $bannervalue){
                  ?>

                    <div class='img-div' id='img-div"+i+"'>
                      <img src='{{url('public/images/'.$bannervalue->image)}}' class='img-responsive image img-thumbnail' title=''>
                      <div class='middle'>
                        <button id='action-icon' class='btn' type="button" onclick="bannerimagedelete({{$bannervalue->id}})"><i class='fa fa-trash'></i></button>
                      </div>
                    </div>

                  <?php } } ?>
                  <div id="image_preview" style="width:100%;">
                  </div>
                </div>

              </div>
              <div class="row align-items-center mb-3">
                <div class="col-md-12">
                  <label for="instagram" class="col-form-label">Type<em>*</em></label>
                  <div>
                    <input type="radio" id="agent" name="type" value="1" <?php if(isset($data->id)){ if($data->type==1){echo "checked"; }}else{ ?>checked<?php } ?> >
                    <label for="agent">Agent</label>
                    <input type="radio" id="reseller" name="type" class="ms-3" value="2" <?php if(isset($data->id)){ if($data->type==2){echo "checked"; }} ?>>
                    <label for="reseller">Reseller</label>
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="id" name="id" id="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                  <button type="button" class="btn btn-primary submitdata">Submit</button>
                  <div id="submitError" class="text-danger mt-3"></div>
                </div>
                <div class="alert alert-success text-center hide1 mt-3"><span class="msg_success"></span></div>
                <div class="alert alert-danger text-center hide2 mt-3"><span class="msg_danger"></span></div>
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
          document.getElementById('logo_error').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
          document.getElementById('previewImg').style.display = 'none';
          // Clean up if the ratio is invalid
          URL.revokeObjectURL(objectURL);
          imgfile="1";
        }else{
          document.getElementById('previewImg').src = objectURL;
          document.getElementById('previewImg').style.display = 'block';
          document.getElementById('logo_error').style.display = 'none';
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
          //     document.getElementById('logo_error').style.display = 'none';
          //     imgfile="0";
          // } else {
          //   const fileInput = document.getElementById('logo');
          //     fileInput.value = '';
          //     document.getElementById('logo_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
          //     document.getElementById('previewImg').style.display = 'none';
          //     // Clean up if the ratio is invalid
          //     URL.revokeObjectURL(objectURL);
          //     imgfile="1";
          // }
      };

      img.onerror = function() {
          document.getElementById('logo_error').textContent = 'Failed to load image.';
          document.getElementById('previewImg').style.display = 'none';
      };
  });
</script>
<script>
  var imgfiles = "0";
  document.getElementById('imagess').addEventListener('change', function(event) {
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
          const fileInput = document.getElementById('imagess');
          fileInput.value = '';
          document.getElementById('imagess_error').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
          document.getElementById('previewImgs').style.display = 'none';
          // Clean up if the ratio is invalid
          URL.revokeObjectURL(objectURL);
          imgfile="1";
        }else{
          document.getElementById('previewImgs').src = objectURL;
          document.getElementById('previewImgs').style.display = 'block';
          document.getElementById('imagess_error').style.display = 'none';
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
          //     document.getElementById('previewImgs').src = objectURL;
          //     document.getElementById('previewImgs').style.display = 'block';
          //     document.getElementById('imagess_error').style.display = 'none';
          //     imgfiles="0";
          // } else {
          //   const fileInput = document.getElementById('imagess');
          //     fileInput.value = '';
          //     document.getElementById('imagess_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
          //     document.getElementById('previewImgs').style.display = 'none';
          //     // Clean up if the ratio is invalid
          //     URL.revokeObjectURL(objectURL);
          //     imgfiles="1";
          // }
      };

      img.onerror = function() {
          document.getElementById('imagess_error').textContent = 'Failed to load image.';
          document.getElementById('previewImgs').style.display = 'none';
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
          //     document.getElementById('tag_icon_error').style.display = 'none';
          // } else {
          //   const fileInput = document.getElementById('tag_icon');
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
  $(document).ready(function() {
  var fileArr = [];
   $("#images").change(function(){
      // check if fileArr length is greater than 0
       if (fileArr.length > 0) fileArr = [];

        $('#image_preview').html("");
        var total_file = document.getElementById("images").files;
        if (!total_file.length) return;
        for (var i = 0; i < total_file.length; i++) {
          if (total_file[i].size > 1048576) {
            return false;
          } else {
            fileArr.push(total_file[i]);
            $('#image_preview').append("<div class='img-div' id='img-div"+i+"'><img src='"+URL.createObjectURL(event.target.files[i])+"' class='img-responsive image img-thumbnail' title='"+total_file[i].name+"'><div class='middle'><button id='action-icon' value='img-div"+i+"' class='btn' role='"+total_file[i].name+"'><i class='fa fa-trash'></i></button></div></div>");
          }
        }
   });

  $('body').on('click', '#action-icon', function(evt){
      var divName = this.value;
      var fileName = $(this).attr('role');
      $(`#${divName}`).remove();

      for (var i = 0; i < fileArr.length; i++) {
        if (fileArr[i].name === fileName) {
          fileArr.splice(i, 1);
        }
      }
    document.getElementById('images').files = FileListItem(fileArr);
      evt.preventDefault();
  });

   function FileListItem(file) {
            file = [].slice.call(Array.isArray(file) ? file : arguments)
            for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
            return b.files
        }
});
</script>
<script>
    $('.submitdata').click(function(){
        var name  = $('#name').val();
        var namear  = $('#namear').val();
        var logo  = $('#logo').val();
        var imagess  = $('#imagess').val();
        var brands  = $('#brands').val();
        var email  = $('#email').val();
        var mobile  = $('#mobile').val();
        var website  = $('#website').val();
        var youtube  = $('#youtube').val();
        var twitter  = $('#twitter').val();
        var linkedin  = $('#linkedin').val();
        var facebook  = $('#facebook').val();
        var instagram  = $('#instagram').val();
        var snapchat  = $('#snapchat').val();
        var id  = $('#id').val();
        //alert(brands);
        if(!name){
          $('#name_error').text("The name field is required.");
          return false;
        }
        if(!namear){
          $('#name_error').text("");
          $('#namear_error').text("The name field is required.");
          return false;
        }
        if(id==''){
          if(!logo){
            $('#name_error').text("");
            $('#logo_error').text("The logo field is required.");
            return false;
          }

        }else if(imgfile =="1"){
          $('#name_error').text("");
          $('#logo_error').text("The logo field is required.");
          return false;
        }
        if(id==''){
          if(!imagess){
            $('#name_error').text("");
            $('#logo_error').text("");
            $('#imagess_error').text("The image field is required.");
            return false;
          }
        }else if(imgfiles =="1"){
          $('#name_error').text("");
            $('#logo_error').text("");
            $('#imagess_error').text("The image field is required.");
          return false;
        }
        if(brands==''){
          $('#name_error').text("");
          $('#logo_error').text("");
          $('#imagess_error').text("");
          $('#brands_error').text("The brand field is required.");
          return false;
        }
        if(!email){
          $('#name_error').text("");
          $('#logo_error').text("");
          $('#imagess_error').text("");
          $('#brands_error').text("");
          $('#email_error').text("The email field is required.");
          return false;
        }
        if(!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){
          $('#name_error').text("");
          $('#logo_error').text("");
          $('#imagess_error').text("");
          $('#brands_error').text("");
          $('#email_error').text("Please enter valid email");
          return false;
        }
        if(!mobile){
          $('#name_error').text("");
          $('#logo_error').text("");
          $('#imagess_error').text("");
          $('#brands_error').text("");
          $('#email_error').text("");
          $('#mobile_error').text("The mobile number field is required.");
          return false;
        }
        if(!mobile.match(/^[0-9]{9,12}$/)){
          $('#name_error').text("");
          $('#logo_error').text("");
          $('#imagess_error').text("");
          $('#brands_error').text("");
          $('#email_error').text("");
          $('#mobile_error').text("Please enter valid number");
          return false;
        }
        if(website!=''){
          if(!website.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#website_error').text("Please enter valid URL");
            return false;
          }
        }
        if(youtube!=''){
          if(!youtube.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#youtube_error').text("Please enter valid URL");
            return false;
          }
        }
        if(twitter!=''){
          if(!twitter.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#twitter_error').text("Please enter valid URL");
            return false;
          }
        }
        if(linkedin!=''){
          if(!linkedin.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#linkedin_error').text("Please enter valid URL");
            return false;
          }
        }
        if(facebook!=''){
          if(!facebook.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#facebook_error').text("Please enter valid URL");
            return false;
          }
        }
        if(instagram!=''){
          if(!instagram.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#instagram_error').text("Please enter valid URL");
            return false;
          }
        }
        if(snapchat!=''){
          if(!snapchat.match(/^(?:(?:http|https|ftp):\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-._~:/?#[\]@!$&'()*+,;=%]+$/)){
            $('#snapchat_error').text("Please enter valid URL");
            return false;
          }
        }
        $.ajax({
            type:'POST',
            url:'{{url("/admin/insert_agent")}}',
            data  :new FormData( $("#saveform")[0] ),
            async   : false,
            cache   : false,
            contentType : false,
            processData : false,
            success:function(data){
              console.log(data);
              if($.trim(data)=="1"){
              $('.hide1').css('display','block');
              $('.msg_success').text("Sucessfully submitted");
              $(".alert-success").show('slow' , 'linear').delay(4000).fadeOut(function(){
                  window.location.href="{{URL::to('/admin/agent')}}";
              });

              }
              if($.trim(data)=="2"){
              $('.hide2').css('display','block');
              $('.msg_danger').text("Name already exist");
              $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

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
<script>
  function bannerimagedelete(th){
    var id = th;
    var token = "<?=csrf_token()?>";
    $.ajax({
        type:'POST',
        url:'{{url("/admin/deleteimage")}}',
        data  :{id:id,_token:token},
        success:function(data){
          console.log(data);
          if($.trim(data)=="1"){
            location.reload();
          }
        }

    });
  }
</script>


@include('admin.includes.footer')
