
@include('admin.includes.header')
<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/brands') }}">Agent</a></li>
              <li class="breadcrumb-item active">{{$title}} Form</li>
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
              </div>
              <div class="row mb-3 ui">
                <div class="col-md-6">
                  <label for="website" class="col-form-label">Service<em>*</em></label>
                  <select name="brands[]" multiple="" id="brands" class="js-example-basic-single form-select">
                    @foreach($ServiceProviderserviceslist as $servicevalue)
                    <?php
                    $service = App\Models\ServiceProvideService::where('id',$servicevalue->serviceID)->first();
                    ?>
                    <option value="{{$service->id}}" <?php if(isset($data->id)){ foreach($servicebranch as $sbval){ if($sbval->serviceproviderID==$service->id){ echo"Selected"; }}}?>>{{$service->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="brand_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="email" class="col-form-label">Email<em>*</em></label>
                  <input type="text" name="email" class="form-control email" id="email" value="@if(isset($data->id)){{$data->email}}@endif"/>
                  <div class="text-danger" id="email_error"></div>
                </div>
              </div>
              <div class="row mb-3 ui">
                <div class="col-md-6">
                  <label for="mobile" class="col-form-label">Mobile No.<em>*</em></label>
                  <input type="text" name="mobile" class="form-control" placeholder="Mobile No." id="mobile" value="@if(isset($data->id)){{$data->contact}}@endif" required>
                  <div class="text-danger" id="mobile_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="youtube" class="col-form-label">Working Days<em>*</em></label>
                  <div class="row">
                    <div class="col-sm-6">
                      <select name="start_day" id="start_day" class="form-select">
                        <option <?php if(isset($data->id)){ if($data->start_day==1){ echo"selected"; }} ?> value="1">Monday</option>
                        <option <?php if(isset($data->id)){ if($data->start_day==2){ echo"selected"; }} ?> value="2">Tuesday</option>
                        <option <?php if(isset($data->id)){ if($data->start_day==3){ echo"selected"; }} ?> value="3">Wednesday</option>
                        <option <?php if(isset($data->id)){ if($data->start_day==4){ echo"selected"; }} ?> value="4">Thursday</option>
                        <option <?php if(isset($data->id)){ if($data->start_day==5){ echo"selected"; }} ?> value="5">Friday</option>
                        <option <?php if(isset($data->id)){ if($data->start_day==6){ echo"selected"; }} ?> value="6">Saturday</option>
                        <option <?php if(isset($data->id)){ if($data->start_day==7){ echo"selected"; }} ?> value="7">Sunday</option>
                      </select>
                      <div class="text-danger" id="start_error"></div>
                    </div>
                    <div class="col-sm-6">
                      <select name="end_day" id="end_day" class="form-select">
                      <option <?php if(isset($data->id)){ if($data->end_day==1){ echo"selected"; }} ?> value="1">Monday</option>
                        <option <?php if(isset($data->id)){ if($data->end_day==2){ echo"selected"; }} ?> value="2">Tuesday</option>
                        <option <?php if(isset($data->id)){ if($data->end_day==3){ echo"selected"; }} ?> value="3">Wednesday</option>
                        <option <?php if(isset($data->id)){ if($data->end_day==4){ echo"selected"; }} ?> value="4">Thursday</option>
                        <option <?php if(isset($data->id)){ if($data->end_day==5){ echo"selected"; }} ?> value="5">Friday</option>
                        <option <?php if(isset($data->id)){ if($data->end_day==6){ echo"selected"; }} ?> value="6">Saturday</option>
                        <option <?php if(isset($data->id)){ if($data->end_day==7){ echo"selected"; }}else{ echo"selected"; } ?> value="7">Sunday</option>
                      </select>
                      <div class="text-danger" id="end_error"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="youtube" class="col-form-label">Working Hours<em>*</em></label>
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="time" class="form-control" name="start_time" id="start_time" value="<?php if(isset($data->id)){ echo $data->start_time; } ?>">
                      <div class="text-danger" id="start_time_error"></div>
                    </div>
                    <div class="col-sm-6">
                      <input type="time" class="form-control" name="end_time" id="end_time" value="<?php if(isset($data->id)){ echo $data->end_time; } ?>">
                      <div class="text-danger" id="end_time_error"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="twitter" class="col-form-label">Country<em>*</em></label>
                  <select class="form-select" name="country" id="country">
                    <option value="">Choose</option>
                    @foreach($country as $countryvalue)
                    <option value="{{$countryvalue->id}}" <?php if(isset($data->id)){ if($countryvalue->id == $data->country){ echo"selected"; }} ?>>{{$countryvalue->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="country_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="linkedin" class="col-form-label">State<em>*</em></label>
                  <select class="form-select" name="state" id="state">
                    <option value="">Choose</option>
                    @if(isset($data->id))
                    @foreach($state as $value)
                    <option value="{{$value->id}}" <?php if(isset($data->id)){ if($value->id == $data->state){ echo"selected"; }} ?>>{{$value->name}}</option>
                    @endforeach
                    @endif
                  </select>
                  <div class="text-danger" id="state_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="facebook" class="col-form-label">City<em>*</em></label>
                  <select class="form-select" name="city" id="city">
                    <option value="">Choose</option>
                    @if(isset($data->id))
                    @foreach($city as $value)
                    <option value="{{$value->id}}" <?php if(isset($data->id)){ if($value->id == $data->city){ echo"selected"; }} ?>>{{$value->name}}</option>
                    @endforeach
                    @endif
                  </select>
                  <div class="text-danger" id="city_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="instagram" class="col-form-label">Address<em>*</em></label>
                  <textarea class="form-control" name="address" id="address">@if(isset($data->id)){{$data->address}}@endif</textarea>
                  <div class="text-danger" id="address_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-12">
                  <label for="image" class="col-form-label">Branch Image<?php if(isset($data->id)){}else { echo "<em>*</em>"; } ?></label>
                  <input type="file" id="imageInput" name="image" accept="image/*" class="form-control mb-2">
                  <span class="text-danger"><b>Note : </b>Recommended size will be 3:2</span><br>
                  <p class="text-danger" id="message"></p>
                  <img id="preview" src="<?php if(isset($data->id)){ echo url("public/images/".$data->image);}else{ ?>../public/img/image-preview.png<?php } ?>" style="max-width: 100%;">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="agentid" name="agentid" value="{{$agentid}}">
                  <input type="hidden" class="id" name="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                  <button type="button" class="btn btn-primary submitdata">Submit</button>
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

      };

      img.onerror = function() {
          document.getElementById('message').textContent = 'Failed to load image.';
          document.getElementById('preview').style.display = 'none';
      };
  });
</script>
<script>
    $('.submitdata').click(function(){
        var name  = $('.name').val();
        var brands  = $('#brands').val();
        var mobile  = $('#mobile').val();
        var email  = $('#email').val();
        var start_day  = $('#start_day').val();
        var end_day  = $('#end_day').val();
        var start_time  = $('#start_time').val();
        var end_time  = $('#end_time').val();
        var country  = $('#country').val();
        var state  = $('#state').val();
        var city  = $('#city').val();
        var address  = $('#address').val();
        var agentid = $('.agentid').val();
        var image = $('#imageInput').val();
        if(!name){
          $('#name_error').text("This field is required.");
          return false;
        }
        if(!brands){
          $('#name_error').text("");
          $('#brand_error').text("This field is required.");
          return false;
        }
        if(!email){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#email_error').text("This field is required.");
          return false;
        }
        if(!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#email_error').text("Please enter valid email");
          return false;
        }
        if(!mobile){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("This field is required.");
          return false;
        }
        if(!mobile.match(/^[0-9]{9,12}$/)){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("Please enter valid number");
          return false;
        }
        if(!start_day){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("This field is required.");
          return false;
        }
        if(!end_day){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("This field is required.");
          return false;
        }
        if(start_day > end_day){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("Start date always less then End date");
          $('#end_error').text("");
          return false;
        }
        if(!start_time){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("This field is required.");
          return false;
        }
        if(!end_time){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("");
          $('#end_time_error').text("This field is required.");
          return false;
        }
        if(start_time > end_time){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("Start time always less then End time");
          $('#end_time_error').text("");
          return false;
        }
        if(!country){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("");
          $('#end_time_error').text("");
          $('#country_error').text("This field is required.");
          return false;
        }
        if(!state){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("");
          $('#end_time_error').text("");
          $('#country_error').text("");
          $('#state_error').text("This field is required.");
          return false;
        }
        if(!city){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("");
          $('#end_time_error').text("");
          $('#country_error').text("");
          $('#state_error').text("");
          $('#city_error').text("This field is required.");
          return false;
        }
        if(!address){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("");
          $('#start_error').text("");
          $('#end_error').text("");
          $('#start_time_error').text("");
          $('#end_time_error').text("");
          $('#country_error').text("");
          $('#state_error').text("");
          $('#city_error').text("");
          $('#address_error').text("This field is required.");
          return false;
        }
        if(agentid==''){
          if(!image){
            $('#name_error').text("");
            $('#brand_error').text("");
            $('#service_error').text("");
            $('#mobile_error').text("");
            $('#start_error').text("");
            $('#end_error').text("");
            $('#start_time_error').text("");
            $('#end_time_error').text("");
            $('#country_error').text("");
            $('#state_error').text("");
            $('#city_error').text("");
            $('#address_error').text("");
            $('#message').text("The image field is required.");
            return false;
          }
        }else if(imgfile=="1"){
            $('#name_error').text("");
            $('#brand_error').text("");
            $('#service_error').text("");
            $('#mobile_error').text("");
            $('#start_error').text("");
            $('#end_error').text("");
            $('#start_time_error').text("");
            $('#end_time_error').text("");
            $('#country_error').text("");
            $('#state_error').text("");
            $('#city_error').text("");
            $('#address_error').text("");
            $('#message').text("The image field is required.");
            return false;
        }
        $.ajax({
            type:'POST',
            url:'{{url("/insert_service_provider_branch")}}',
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
                  window.location.href="{{URL::to('/admin/service_provider_branch?key=')}}"+btoa(agentid);
              });

              }
              if($.trim(data)=="2"){
              $('.hide2').css('display','block');
              $('.msg_danger').text("Name already exist");
              $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

              }
            }

        });

    })
</script>
<script>
    $('#country').change(function(){
        var country_id = $(this).val();
        var token = "<?=csrf_token()?>";
        if(!country_id){
            $('#country').css("border","1px solid red");
        }else{
            $('#country').css("border","");
            $.ajax({
                type:'POST',
                url:"{{url('/getState')}}",
                data:{country_id:country_id,_token:token},
                success:function(res){
                    $('#state').html(res)

                }
            })
        }
    });

</script>
<script>
    $('#state').change(function(){
        var state_id = $(this).val();
        var token = "<?=csrf_token()?>";
        if(!state_id){
            $('#state').css("border","1px solid red");
        }else{
            $('#state').css("border","");
            $.ajax({
                type:'POST',
                url:"{{url('/getCity')}}",
                data:{state_id:state_id,_token:token},
                success:function(res){
                    $('#city').html(res)

                }
            })
        }
    });

</script>

@include('admin.includes.footer')
