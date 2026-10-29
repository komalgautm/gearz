
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
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> <?php if(isset($data->id)){ echo "Edit"; }else{ echo "Add"; } ?> Model</h4>


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
                  <label for="name" class="col-form-label">Model Name (English)</label>
                  <input type="text" name="name" class="form-control name" placeholder="Brand Name (English)" id="name" value="@if(isset($data->id)){{$data->name}}@endif" required>
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="namear" class="col-form-label">Model Name (Arabic)</label>
                  <input type="text" name="namear" class="form-control namear" placeholder="Brand Name (Arabic)" id="namear" value="@if(isset($data->id)){{$data->name_ar}}@endif" required>
                  <div class="text-danger" id="namear_error"></div>
                </div>
              </div>
              <div class="row mt-5">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="id" name="id" id="id" value="@if(isset($data->id)){{$data->id}}@endif">
                  <input type="hidden" class="brandID" name="brandID" id="brandID" value="{{$brand_id}}">
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
      var namear  = $('.namear').val();
      var brandID = $('.brandID').val();
      if(name==''){
          $('.name').css('border','1px solid red');
          return false;
      }
      if(namear==''){
          $('.namear').css('border','1px solid red');
          return false;
      }

          $('.name').css('border','');
          $('.namear').css('border','');
          $.ajax({
              type:'POST',
              url:'{{url("/admin/insert_carmodel")}}',
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
                    window.location.href="{{URL::to('/admin/carmodel?key=')}}"+brandID;
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

@include('admin.includes.footer')
