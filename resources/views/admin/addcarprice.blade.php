
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> Add {{$brand_name}} {{$model_name}} {{$vehicle_name}} Car Price</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/pricelist') }}">Car Price</a></li>
              <li class="breadcrumb-item active">Add Car Price</li>
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
              <div class="row mb-4">
                <div class="col-md-6">
                  <label for="category" class="col-form-label">City</label>
                  <select name="category" id="category" class="form-select">
                    <option value="">Choose</option>
                    @foreach($city as $cvalue)
                    <option <?php if(isset($data->id)){ if($data->CityID == $cvalue->id){ echo"Selected"; }} ?> value="{{$cvalue->id}}">{{$cvalue->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="name" class="col-form-label">Ex-showroom Price</label>
                  <input type="text" name="name" class="form-control name" placeholder="Ex-showroom Price" id="name" value="@if(isset($data->id)) {{$data->price}} @endif" required>
                </div>
              </div>

              <!-- <div class="row mb-3">
                <label for="rto" class="col-sm-2 col-form-label">RTO</label>
                <div class="col-sm-10">
                  <input type="text" name="rto" class="form-control rto" placeholder="RTO" id="rto" value="@if(isset($data->id)) {{$data->rto}} @endif" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="insurance" class="col-sm-2 col-form-label">Insurance</label>
                <div class="col-sm-10">
                  <input type="text" name="insurance" class="form-control insurance" placeholder="Insurance" id="insurance" value="@if(isset($data->id)) {{$data->insurance}} @endif" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="other" class="col-sm-2 col-form-label">Other</label>
                <div class="col-sm-10">
                  <input type="text" name="other" class="form-control other" placeholder="Other" id="other" value="@if(isset($data->id)) {{$data->other}} @endif" required>
                </div>
              </div> -->
              
              <div class="row">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="id" name="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                  <input type="hidden" class="vehicleID" name="vehicleID" value="{{$vehicleID}}">
                  <input type="hidden" class="modelID" name="modelID" value="{{$modelID}}">
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
        var name  = $('.name').val();
        var vehicleID  = $('.vehicleID').val();
        if(name==''){
            $('.name').css('border','1px solid red');
        }else{
            $('.name').css('border','');
            $.ajax({
                type:'POST',
                url:'{{url("/admin/insert_carprice")}}',
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
                      window.location.href="{{URL::to('/admin/pricelist?key=')}}"+btoa(vehicleID);
                  });

                  }
                  if($.trim(data)=="2"){
                  $('.hide2').css('display','block');
                  $('.msg_danger').text("Name already exist");
                  $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

                  }
                }
                
            });
        }
    })
</script>

@include('admin.includes.footer')