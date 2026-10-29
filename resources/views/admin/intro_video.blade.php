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
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Intro Video</h4>

        <div class="card p-4">
          <div class="card-body">
            <p class="alert alert-success" style="display:none" id="msg"></p>
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form id="formData" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row mb-3">
                <div class="col-sm-12">
                  <label for="video" class="col-form-label">Upload Intro Video<?php if(isset($intro_video)){}else {
                    echo "<em>*</em>";
                  } ?></label>
                  <input type="file" name="video" class="form-control mb-2" id="video" accept="video/*">
                  <!-- <span class="text-danger"><b>Note : </b>Recommended size will be 3:2</span><br> -->
                  @isset($intro_video)
                  <video src="{{ asset('public/video/intro_video/' . $intro_video->video) }}" width="400" controls autoplay muted playsinline></video>
                  @else
                  <video id="video_preview" width="400" controls style="display:none;"></video>
                  @endisset
                  <input type="hidden" name="intro_video_id" id="intro_video_id" value="@isset($intro_video){{ $intro_video->id }}@endisset">
                  <input type="hidden" name="pre_video" value="@isset($intro_video){{ $intro_video->video }}@endisset">
                  <div class="text-danger" id="video_error"></div>
                </div>
                <div class="col-sm-12 form-check mt-3">
                  <input class="form-check-input" type="checkbox" style="padding: 0px;width: 14px;" name="is_repeat" id="is_repeat" value="1" <?php if(isset($intro_video) && $intro_video->is_repeat == 1){ echo "checked"; }else { echo "";} ?>>
                  <label class="form-check-label" for="is_repeat">
                    Check, if you want that this intro video should show every time?
                  </label>
                </div>
                <div class="col-sm-12 form-check mt-3">
                  <input class="form-check-input" type="checkbox" style="padding: 0px;width: 14px;" name="is_skipable" id="is_skipable" value="1" <?php if(isset($intro_video) && $intro_video->is_skipable == 1){ echo "checked"; }else { echo "";} ?>>
                  <label class="form-check-label" for="is_skipable">
                    Check, if you want that this intro video should be skipable?
                  </label>
                </div>
                <div class="col-sm-4 form-check mt-3">
                  <label for="skip_time" class="col-form-label">Skip After Time ( in second ) </label>
                  <input class="form-control" type="text" name="skip_time" id="skip_time" placeholder="Enter Skip Time" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="{{ $intro_video->skip_time }}">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <button type="button" onclick="get_form_data()" class="btn btn-primary">Submit</button>
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
  var videofile = "0";
  document.getElementById('video').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const objectURL = URL.createObjectURL(file);
    const validVideoTypes = ['video/mp4', 'video/webm', 'video/ogg']; // Add more types if needed

    if (!validVideoTypes.includes(file.type)) {
      document.getElementById('video').value = '';
      document.getElementById('video_error').textContent = "Invalid file type. Please upload MP4, WebM, or OGG video.";
      document.getElementById('video_preview').style.display = 'none';
      URL.revokeObjectURL(objectURL);
      videofile = "1";
    } else {
      const video = document.getElementById('video_preview');
      video.src = objectURL;
      video.style.display = 'block';
      video.load();
      document.getElementById('video_error').textContent = '';
      videofile = "0";
    }
  });
</script>
<script>
  function get_form_data(){
    var video_id = $('#intro_video_id').val();
    var fileInput = document.getElementById('video'); // or use $('#video')[0]
    var file = fileInput.files[0];

    $('#video_error').text(""); 

    // 2MB = 2 * 1024 * 1024 bytes
    if (file && file.size > 2 * 1024 * 1024) {
        $('#video_error').text("The video must not be greater than 2MB.");
        return false;
    }
    
    $.ajax({
      type:"POST",
      url:"{{url('admin/update_intro_video')}}",
      data:new FormData($("#formData")[0]),
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
          $("#msg").html('<span style="font-weight: 600;"> Success !! </span> Intro Video updated successfully.');
          $('#formData')[0].reset();
          setTimeout(() => {
            window.location.href = "<?php echo url('admin/intro_video'); ?>";
          }, 5000);
        }else{
          var obj=JSON.parse(data);
          if(obj.video){
            $('#video_error').html(obj.video);
          }
        }
      }

    });
  }
</script>

@include('admin.includes.footer')
