
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Add City</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/brands') }}">Brands</a></li>
              <li class="breadcrumb-item active">Add Brand</li>
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
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="twitter" class="col-form-label">Country</label>
                  <select class="form-select" name="country" id="country">
                    <option value="">Choose</option>
                    @foreach($country as $countryvalue)
                    <option value="{{$countryvalue->id}}" <?php if($countryvalue->id == $country_id){ echo"selected"; } ?>>{{$countryvalue->name}}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="country_error"></div>
                </div>
                <div class="col-md-6">
                    <?php if($country_id!=191){ ?>
                  <label for="linkedin" class="col-form-label">State</label>
                  <select class="form-select" name="state" id="state">
                    <option value="">Choose</option>
                    @foreach($state as $value)
                    <option value="{{$value->id}}" <?php if(isset($data->id)){ if($value->id == $data->state_id){ echo"selected"; }}else{ if($state_id==$value->id){ echo"selected"; }} ?>>{{$value->name}}</option>
                    @endforeach

                  </select>
                  <div class="text-danger" id="state_error"></div>
                  <?php } ?>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-md-6">
                  <label for="name" class="col-form-label">City Name (English)</label>
                  <input type="text" name="name" class="form-control name" placeholder="City Name (English)" id="name" value="@if(isset($data->id)) {{$data->name}} @endif" required>
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-md-6">
                    <label for="name" class="col-form-label">City Name (Arabic)</label>
                    <input type="text" name="namear" class="form-control namear" placeholder="City Name (Arabic)" id="namear" value="@if(isset($data->id)) {{$data->name_ar}} @endif" required>
                    <div class="text-danger" id="namear_error"></div>
                  </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="id" name="id" id="id" value="@if(isset($data->id)) {{$data->id}} @endif">
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
    $('.submitdata').click(function(){
        var name  = $('#name').val();
        var namear  = $('#namear').val();
        var country  = $('#country').val();
        var state  = $('#state').val();
        var id  = $('#id').val();
        if(!country){
          $('#name_error').text("");
          $('#country_error').text("The country field is required.");
          return false;
        }
        if(!name){
          $('#name_error').text("The name field is required.");
          return false;
        }
        if(!namear){
          $('#namear_error').text("The name field is required.");
          return false;
        }
        $.ajax({
            type:'POST',
            url:'{{url("/admin/insertCity")}}',
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
                  window.location.href="{{URL::to('/admin/cityList')}}";
              });

              }
              if($.trim(data)=="2"){
              $('.hide2').css('display','block');
              $('.msg_danger').text("Name already exist");
              $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

              }
              if ($.trim(data) == "3") {
                $('#image_error').text("Invalid image dimensions must be 40px X 40px");

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

@include('admin.includes.footer')
