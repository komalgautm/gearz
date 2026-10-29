
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
                  <input type="text" name="name" class="form-control name" placeholder="Name (English)" id="name" value="@if(isset($data->id)){{$data->name}}@endif" @if($eye) readonly @endif required>
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="namear" class="col-form-label">Name (Arabic)<em>*</em></label>
                  <input type="text" name="namear" class="form-control namear" placeholder="Name (Arabic)" id="namear" value="@if(isset($data->id)){{$data->name_ar}}@endif" @if($eye) readonly @endif required>
                  <div class="text-danger" id="namear_error"></div>
                </div>
              </div>
              <div class="row mb-3 ui">
                <div class="col-md-6">
                  <label for="website" class="col-form-label">Brand<em>*</em></label>
                  <select name="brands[]" multiple="" id="brands" class="js-example-basic-single form-select" @if($eye) disabled @endif>
                    @foreach($brand as $brandvalue)
                    <?php
                    $brand = App\Models\Brand::where('id',$brandvalue->brandID)->first();
                    ?>
                    <option value="{{$brand->id}}" <?php if(isset($data->id)){ foreach($agentbrand as $agval){ if($agval->brandId==$brand->id){ echo"Selected"; }}}?>>{{$brand->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="brand_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="email" class="col-form-label">Email<em>*</em></label>
                  <input type="text" name="email" class="form-control email" id="email" value="@if(isset($data->id)){{$data->email}}@endif" @if($eye) readonly @endif/>
                  <div class="text-danger" id="email_error"></div>
                </div>
              </div>
              <div class="row mb-3 ui">
                <div class="col-md-6">
                  <label for="servicetypeID" class="col-form-label">Service Type<em>*</em></label>
                  <select name="servicetypeID[]" multiple="" id="servicetypeID" class="js-example-basic-single form-select" @if($eye) disabled @endif>
                    @foreach($servicetype as $value)
                    <option value="{{$value->id}}" <?php if(isset($data->id)){ foreach($service as $sver){if($value->id == $sver->serviceID){ echo"selected"; }} }?>>{{$value->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="service_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="mobile" class="col-form-label">Mobile No.<em>*</em></label>
                  <input type="text" name="mobile" class="form-control" placeholder="Mobile No." id="mobile" value="@if(isset($data->id)){{$data->contact}}@endif" @if($eye) readonly @endif required>
                  <div class="text-danger" id="mobile_error"></div>
                </div>
              </div>
              <div class="row mb-3">
                  <label for="youtube" class="col-form-label">Working Days</label>
                  <div class="row text-center">
                    <div class="col-md-2">
                      <label for="youtube" class="col-form-label">Day</label>
                    </div>
                    <div class="col-md-5">
                      <label for="youtube" class="col-form-label">First Shift</label>
                    </div>
                    <div class="col-md-5">
                      <label for="youtube" class="col-form-label">Second Shift</label>
                    </div>
                  </div>
                  @php 
                  $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
                  @endphp
                  @foreach($days as $i => $day)
                  <div class="row">
                    <div class="col-md-2">
                      <input type="text" value="{{$day}}" class="form-control" readonly>
                    </div>
                    <div class="col-md-5">
                      <div class="row">
                        <div class="col-sm-6">
                          <input type="time" class="form-control" name="first_shift[start_time][]" id="start_time" value="<?php if(isset($branchWorkingDays[$i]->first_shift_start_time)){ echo $branchWorkingDays[$i]->first_shift_start_time; } ?>" @if($eye) readonly @endif>
                        </div>
                        <div class="col-sm-6">
                          <input type="time" class="form-control" name="first_shift[end_time][]" id="end_time" value="<?php if(isset($branchWorkingDays[$i]->first_shift_end_time)){ echo $branchWorkingDays[$i]->first_shift_end_time; } ?>" @if($eye) readonly @endif>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="row">
                        <div class="col-sm-6">
                          <input type="time" class="form-control" name="second_shift[start_time][]" id="start_time" value="<?php if(isset($branchWorkingDays[$i]->second_shift_start_time)){ echo $branchWorkingDays[$i]->second_shift_start_time; } ?>" @if($eye) readonly @endif>
                        </div>
                        <div class="col-sm-6">
                          <input type="time" class="form-control" name="second_shift[end_time][]" id="end_time" value="<?php if(isset($branchWorkingDays[$i]->second_shift_end_time)){ echo $branchWorkingDays[$i]->second_shift_end_time; } ?>" @if($eye) readonly @endif>   
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="twitter" class="col-form-label">Country<em>*</em></label>
                  <select class="form-select" name="country" id="country" @if($eye) disabled @endif>
                    <option value="">Choose</option>
                    @foreach($country as $countryvalue)
                    <option value="{{$countryvalue->id}}" <?php if(isset($data->id)){ if($countryvalue->id == $data->country){ echo"selected"; }} ?>>{{$countryvalue->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="country_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="linkedin" class="col-form-label">State<em>*</em></label>
                  <select class="form-select" name="state" id="state" @if($eye) disabled @endif>
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
                  <select class="form-select" name="city" id="city" @if($eye) disabled @endif>
                    <option value="">Choose</option>
                    @if(isset($data->id))
                    @foreach($city as $value)
                    <option value="{{$value->id}}" <?php if(isset($data->id)){ if($value->id == $data->city){ echo"selected"; }} ?>>{{$value->name}}</option>
                    @endforeach
                    @endif
                  </select>
                  <div class="text-danger" id="city_error"></div>
                </div>
                <!-- <div class="col-md-6">
                  <label for="instagram" class="col-form-label">Address<em>*</em></label>
                  <textarea class="form-control" name="address" id="address">@if(isset($data->id)){{$data->address}}@endif</textarea>
                  <div class="text-danger" id="address_error"></div>
                </div> -->
              </div> 
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="latitude" class="col-form-label">Latitude<em>*</em></label>
                  <input type="text" name="latitude" class="form-control" placeholder="Latitude" id="latitude" value="@if(isset($data->id)){{$data->latitude}}@endif" @if($eye) readonly @endif required>
                  <div class="text-danger" id="latitude_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="longitude" class="col-form-label">Longitude<em>*</em></label>
                  <input type="text" name="longitude" class="form-control" placeholder="Longitude" id="longitude" value="@if(isset($data->id)){{$data->longitude}}@endif" @if($eye) readonly @endif required>
                  <div class="text-danger" id="longitude_error"></div>
                </div>
              </div>
              @if(!$eye)
              <div class="row mb-3">
                <div class="col-sm-12">
                  <label for="image" class="col-form-label">Branch Image</label>
                  <input type="file" id="imageInput" name="image" accept="image/*" class="form-control mb-2">
                  <span class="text-danger"><b>Note : </b>Recommended size will be 3:2</span><br>
                  <p class="text-danger" id="message"></p>

                  <img id="preview" src="<?php if(isset($data->id) && $data->image != ""){ echo url("public/images/".$data->image);}else{ ?>../public/img/image-preview.png<?php } ?>" style="max-width: 100%;">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="agentid" name="agentid" value="{{$agentid}}">
                  <input type="hidden" class="id" name="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                  <button type="button" class="btn btn-primary submitdata">Submit</button>
                </div>
                <div class="alert alert-success text-center hide1 mt-3"><span class="msg_success"></span></div>
                <div class="alert alert-danger text-center hide2 mt-3"><span class="msg_danger"></span></div>
              </div>
              @endif
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
          //     document.getElementById('message').style.display = 'none';
          //     imgfile="0";
          // } else {
          //     const fileInput = document.getElementById('imageInput');
          //     fileInput.value = '';
          //     document.getElementById('message').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
          //     document.getElementById('preview').style.display = 'none';
          //     // Clean up if the ratio is invalid
          //     URL.revokeObjectURL(objectURL);
          //     imgfile="1";
          // }
      };

      img.onerror = function() {
          document.getElementById('message').textContent = 'Failed to load image.';
          document.getElementById('preview').style.display = 'none';
      };
  });
</script>
<!-- <script>
    $('.submitdata').click(function(){
        var name  = $('.name').val();
        var brands  = $('#brands').val();
        var servicetypeID  = $('#servicetypeID').val();
        var mobile  = $('#mobile').val();
        var email  = $('#email').val();
        // var start_day  = $('#start_day').val();
        // var end_day  = $('#end_day').val();
        // var start_time  = $('#start_time').val();
        // var end_time  = $('#end_time').val();
        var country  = $('#country').val();
        var state  = $('#state').val();
        var city  = $('#city').val();
        // var address  = $('#address').val();
        var latitude  = $('#latitude').val();
        var longitude  = $('#longitude').val();
        var agentid = $('.agentid').val();
        var image = $('#imageInput').val();
        if(!name){
          $('#name_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#name_error').offset().top - 300
          }, 500);
          return false;
        }
        if(brands == ""){
          $('#name_error').text("");
          $('#brand_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#brand_error').offset().top - 300
          }, 500);
          return false;
        }
        if(!email){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#email_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#email_error').offset().top - 300
          }, 500);
          return false;
        }
        if(!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#email_error').text("Please enter valid email");
          $('html, body').animate({
              scrollTop: $('#email_error').offset().top - 300
          }, 500);
          return false;
        }
        if(servicetypeID == ""){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#email_error').text("");
          $('#service_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#service_error').offset().top - 300
          }, 500);
          return false;
        }
        if(!mobile){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#mobile_error').offset().top - 300
          }, 500);
          return false;
        }
        if(!mobile.match(/^[0-9]{9,12}$/)){
          $('#name_error').text("");
          $('#brand_error').text("");
          $('#service_error').text("");
          $('#mobile_error').text("Please enter valid number");
          $('html, body').animate({
              scrollTop: $('#mobile_error').offset().top - 300
          }, 500);
          return false;
        }
        // if(!start_day){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#start_error').text("This field is required.");
        //   $('html, body').animate({
        //       scrollTop: $('#start_error').offset().top - 300
        //   }, 500);
        //   return false;
        // }
        // if(!end_day){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#start_error').text("");
        //   $('#end_error').text("This field is required.");
        //   $('html, body').animate({
        //       scrollTop: $('#end_error').offset().top - 300
        //   }, 500);
        //   return false;
        // }
        // if(start_day > end_day){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#end_error').text("");
        //   $('#start_error').text("Start day always less then End date");
        //   $('html, body').animate({
        //       scrollTop: $('#start_error').offset().top - 300
        //   }, 500);
        //   return false;
        // }
        // if(!start_time){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#start_error').text("");
        //   $('#end_error').text("");
        //   $('#start_time_error').text("This field is required.");
        //   $('html, body').animate({
        //       scrollTop: $('#start_time_error').offset().top - 300
        //   }, 500);
        //   return false;
        // }
        // if(!end_time){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#start_error').text("");
        //   $('#end_error').text("");
        //   $('#start_time_error').text("");
        //   $('#end_time_error').text("This field is required.");
        //   $('html, body').animate({
        //       scrollTop: $('#end_time_error').offset().top - 300
        //   }, 500);
        //   return false;
        // }
        // if(start_time > end_time){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#start_error').text("");
        //   $('#end_error').text("");
        //   $('#end_time_error').text("");
        //   $('#start_time_error').text("Start time always less then End time");
        //   $('html, body').animate({
        //       scrollTop: $('#start_time_error').offset().top - 300
        //   }, 500);
        //   return false;
        // }
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
          $('html, body').animate({
              scrollTop: $('#country_error').offset().top - 300
          }, 500);
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
          $('html, body').animate({
              scrollTop: $('#state_error').offset().top - 300
          }, 500);
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
          $('html, body').animate({
              scrollTop: $('#city_error').offset().top - 300
          }, 500);
          return false;
        }
        // if(!address){
        //   $('#name_error').text("");
        //   $('#brand_error').text("");
        //   $('#service_error').text("");
        //   $('#mobile_error').text("");
        //   $('#start_error').text("");
        //   $('#end_error').text("");
        //   $('#start_time_error').text("");
        //   $('#end_time_error').text("");
        //   $('#country_error').text("");
        //   $('#state_error').text("");
        //   $('#city_error').text("");
        //   $('#address_error').text("This field is required.");
        //   return false;
        // }
        if(!latitude){
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
          // $('#address_error').text("");
          $('#latitude_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#latitude_error').offset().top - 300
          }, 500);
          return false;
        }
        if(!longitude){
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
          // $('#address_error').text("");
          $('#latitude_error').text("");
          $('#longitude_error').text("This field is required.");
          $('html, body').animate({
              scrollTop: $('#longitude_error').offset().top - 300
          }, 500);
          return false;
        }
        // if(agentid==''){
        //   if(!image){
        //     $('#name_error').text("");
        //     $('#brand_error').text("");
        //     $('#service_error').text("");
        //     $('#mobile_error').text("");
        //     $('#start_error').text("");
        //     $('#end_error').text("");
        //     $('#start_time_error').text("");
        //     $('#end_time_error').text("");
        //     $('#country_error').text("");
        //     $('#state_error').text("");
        //     $('#city_error').text("");
        //     // $('#address_error').text("");
        //     $('#latitude_error').text("");
        //     $('#longitude_error').text("");
        //     $('#message').text("The image field is required.");
        //     $('html, body').animate({
        //         scrollTop: $('#message').offset().top - 300
        //     }, 500);
        //     return false;
        //   }
        // }else if(imgfile=="1"){
        //     $('#name_error').text("");
        //     $('#brand_error').text("");
        //     $('#service_error').text("");
        //     $('#mobile_error').text("");
        //     $('#start_error').text("");
        //     $('#end_error').text("");
        //     $('#start_time_error').text("");
        //     $('#end_time_error').text("");
        //     $('#country_error').text("");
        //     $('#state_error').text("");
        //     $('#city_error').text("");
        //     // $('#address_error').text("");
        //     $('#latitude_error').text("");
        //     $('#longitude_error').text("");
        //     $('#message').text("The image field is required.");
        //     $('html, body').animate({
        //         scrollTop: $('#message').offset().top - 300
        //     }, 500);
        //     return false;
        // }
        $('#longitude_error').text("");
        $.ajax({
            type:'POST',
            url:'{{url("/insert_branch")}}',
            data  :new FormData( $("#saveform")[0] ),
            async   : false,
            cache   : false,
            contentType : false,
            processData : false,
            success:function(data){
              console.log(data);
              if($.trim(data)=="0"){
                $('.hide2').css('display','block');
                $('.msg_danger').text("Something went wrong");
                $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();
              }
              if($.trim(data)=="1"){
                $('.hide1').css('display','block');
                $('.msg_success').text("Sucessfully submitted");
                $(".alert-success").show('slow' , 'linear').delay(4000).fadeOut(function(){
                  window.location.href="{{URL::to('/admin/branch?key=')}}"+btoa(agentid);
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
</script> -->
<script>
$(document).ready(function () {
    $('.submitdata').click(function () {
        const name = $('.name').val();
        const brands = $('#brands').val();
        const servicetypeID = $('#servicetypeID').val();
        const mobile = $('#mobile').val();
        const email = $('#email').val();
        const country = $('#country').val();
        const state = $('#state').val();
        const city = $('#city').val();
        const latitude = $('#latitude').val();
        const longitude = $('#longitude').val();
        const agentid = $('.agentid').val();
        const image = $('#imageInput').val();

        function scrollToError(id, message) {
            $(`#${id}_error`).text(message);
            $('html, body').animate({ scrollTop: $(`#${id}_error`).offset().top - 300 }, 500);
        }

        if (!name) return scrollToError('name', "This field is required.");
        if (brands == "") return scrollToError('brand', "This field is required.");
        if (!email) return scrollToError('email', "This field is required.");
        if (!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) return scrollToError('email', "Please enter valid email");
        if (servicetypeID == "") return scrollToError('service', "This field is required.");
        if (!mobile) return scrollToError('mobile', "This field is required.");
        if (!mobile.match(/^[0-9]{9,12}$/)) return scrollToError('mobile', "Please enter valid number");
        if (!country) return scrollToError('country', "This field is required.");
        if (!state) return scrollToError('state', "This field is required.");
        if (!city) return scrollToError('city', "This field is required.");
        if (!latitude) return scrollToError('latitude', "This field is required.");
        if (!longitude) return scrollToError('longitude', "This field is required.");

        $('#longitude_error').text("");

        $.ajax({
            type: 'POST',
            url: '{{url("/insert_branch")}}',
            data: new FormData($("#saveform")[0]),
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if ($.trim(data) === "0") {
                    $('.hide2').show();
                    $('.msg_danger').text("Something went wrong");
                    $(".alert-danger").fadeIn().delay(4000).fadeOut();
                }
                if ($.trim(data) === "1") {
                    $('.hide1').show();
                    $('.msg_success').text("Successfully submitted");
                    $(".alert-success").fadeIn().delay(4000).fadeOut(function () {
                        window.location.href = "{{ URL::to('/admin/branch?key=') }}" + btoa(agentid);
                    });
                }
                if ($.trim(data) === "2") {
                    $('.hide2').show();
                    $('.msg_danger').text("Name already exists");
                    $(".alert-danger").fadeIn().delay(4000).fadeOut();
                }
            }
        });
    });
});
</script>
<script>
    $(document).ready(function() {
        // Initialize select2 for each individually
        $('#country').select2({
            placeholder: 'Search Country...'
        });

        $('#state').select2({
            placeholder: 'Search State...'
        });

        $('#city').select2({
            placeholder: 'Search City...'
        });

        // Country change event
        $('#country').on('change', function() {
            var country_id = $(this).val();
            var token = "{{ csrf_token() }}";

            if (!country_id) {
                $('#country').css("border", "1px solid red");
                return;
            }

            $('#country').css("border", "");

            $.ajax({
                type: 'POST',
                url: "{{ url('/getState') }}",
                data: {
                    country_id: country_id,
                    _token: token
                },
                success: function(res) {
                    console.log(res);
                    $('#state').html('<option selected disabled value="">Please Select State</option>' + res);
                    $('#state').val(null).trigger('change'); // reset and refresh select2
                }
            });
        });
        
        // State change event
        $('#state').change(function(){
          var state_id = $(this).val();
          var token = "{{ csrf_token() }}";

          if(!state_id){
              $('#state').css("border","1px solid red");
              return;
          }

          $('#state').css("border","");
          $.ajax({
              type:'POST',
              url:"{{url('/getCity')}}",
              data:{state_id:state_id,_token:token},
              success:function(res){
                console.log(res);
                $('#city').html('<option selected disabled value="">Please Select City</option>' + res);
                $('#city').val(null).trigger('change'); // reset and refresh select2

              }
          });
          
        });
    });
</script>

@include('admin.includes.footer')
