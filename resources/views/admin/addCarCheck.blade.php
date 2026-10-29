
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
button.btn.btn-primary.addmorecheck {
    font-size: 14px;
    font-weight: bolder;
    position: relative;
    top: 35px;
}
button.btn.btn-danger.addmorecheck {
    font-size: 14px;
    font-weight: bolder;
    position: relative;
    top: 35px;
}
</style>

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> <?php if(isset($data->id)){ echo "Edit"; }else{ echo"Add"; } ?> Car Check</h4>
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
              <?php if(!isset($data->id)){ ?>
              <div class="row mb-3 customer_records">
                <div class="col-md-6">
                    <label for="info" class="col-form-label">Check Name(English)<em>*</em></label>
                    <input type="text" name="checkname[]" class="form-control checkname" placeholder="Check Name (Arabic)"  value="" required>
                    <div class="text-danger" id="info_error"></div>
                </div>
                <div class="col-md-4">
                    <label for="infoar" class="col-form-label">Check Name(Arabic)<em>*</em></label>
                    <input type="text" name="checknamear[]" class="form-control checknamear" placeholder="Check Name (Arabic)"  value="" required>
                    <div class="text-danger" id="infoar_error"></div>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary addmorecheck extra-fields-customer">+</button>
                </div>
              </div>
              <?php }else{ ?>
                <?php if(count($list)==0){ ?>
                    <input type="hidden" name="checklistid[]" value="">
                    <div class="row mb-3 customer_records">
                        <div class="col-md-6">
                            <label for="info" class="col-form-label">Check Name(English)<em>*</em></label>
                            <input type="text" name="checkname[]" class="form-control checkname" placeholder="Check Name (Arabic)"  value="" required>
                            <div class="text-danger" id="info_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="infoar" class="col-form-label">Check Name(Arabic)<em>*</em></label>
                            <input type="text" name="checknamear[]" class="form-control checknamear" placeholder="Check Name (Arabic)"  value="" required>
                            <div class="text-danger" id="infoar_error"></div>
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary addmorecheck extra-fields-customer">+</button>
                        </div>
                      </div>
                <?php }else{ ?>

                @php
                $i=1;
                @endphp
                @foreach($list as $value)
                <input type="hidden" name="checklistid[]" value="{{$value->id}}">
                <div class="row mb-3 customer_records" id="records{{$value->id}}">
                    <div class="col-md-6">
                        <label for="info" class="col-form-label">Check Name(English)<em>*</em></label>
                        <input type="text" name="checkname[]" class="form-control checkname" placeholder="Check Name (Arabic)"  value="{{$value->name}}" required>
                        <div class="text-danger" id="info_error"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="infoar" class="col-form-label">Check Name(Arabic)<em>*</em></label>
                        <input type="text" name="checknamear[]" class="form-control checknamear" placeholder="Check Name (Arabic)"  value="{{$value->name_ar}}" required>
                        <div class="text-danger" id="infoar_error"></div>
                    </div>

                    <div class="col-md-2">
                    <?php if($i==1){ ?>
                        <button type="button" class="btn btn-primary addmorecheck extra-fields-customer">+</button>
                    <?php }else{ ?>
                        <button type="button" class="btn btn-danger addmorecheck btn_remove removerow" id="{{$value->id}}">X</button>
                    <?php } ?>
                    </div>
                  </div>
                @php
                $i++;
                @endphp
                @endforeach
                <?php } } ?>
              <div class="customer_records_dynamic"></div>


              {{-- <div class="row mb-3">
                <div class="col-md-6">
                  <label for="info" class="col-form-label">Description (English)<em>*</em></label>
                  <textarea name="info" id="info" cols="30" rows="5" class="form-control" placeholder="Description (English)">@if(isset($data->id)){{$data->content}}@endif</textarea>
                  <div class="text-danger" id="info_error"></div>
                </div>
                <div class="col-md-6">
                  <label for="infoar" class="col-form-label">Description (Arabic)<em>*</em></label>
                  <textarea name="infoar" id="infoar" cols="30" rows="5" class="form-control" placeholder="Description (Arabic)">@if(isset($data->id)){{$data->content_ar}}@endif</textarea>
                  <div class="text-danger" id="infoar_error"></div>
                </div>
              </div> --}}
              <div class="row mt-5">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="id" name="id" id="id" value="@if(isset($data->id)){{$data->id}}@endif">
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
$(document).ready(function(){

   var i = 1;
   var length;
   $(".extra-fields-customer").click(function(){
    i++;
       $('.customer_records_dynamic').append('<div class="row mb-3 customer_records" id="records'+i+'"><div class="col-md-6"><label for="info" class="col-form-label">Check Name(English)<em>*</em></label><input type="text" name="checkname[]" class="form-control checkname" placeholder="Check Name (Arabic)"  value="" required><div class="text-danger" id="info_error"></div></div><div class="col-md-4"><label for="infoar" class="col-form-label">Check Name(Arabic)<em>*</em></label><input type="text" name="checknamear[]" class="form-control checknamear" placeholder="Check Name (Arabic)"  value="" required><div class="text-danger" id="infoar_error"></div></div><div class="col-md-2"><button type="button" class="btn btn-danger addmorecheck btn_remove" id="'+i+'">X</button></div></div>');
     });

   $(document).on('click', '.btn_remove', function(){
     var button_id = $(this).attr("id");
       $('#records'+button_id+'').remove();
     });
   });
</script>
<script>
    $(document).on('click', '.removerow', function(){
        var button_id = $(this).attr("id");

         var token = "<?=csrf_token()?>";
        if(confirm("Are you sure want to delete this?")){
            $('#records'+button_id+'').remove();
        $.ajax({
            type:'POST',
            url:'{{url("/admin/delete_carChecklist")}}',
            data  :{id:button_id,_token:token},
            success:function(data){
                //location.reload();
            }

        });
        }
     });
</script>
<script>
    $('.submitdata').click(function(){
        var name  = $('#name').val();
        var namear  = $('#namear').val();
        var info  = $('#info').val();
        var infoar  = $('#infoar').val();
        var id  = $('#id').val();
        if(!name){
          $('#name_error').text("The name field is required.");
          return false;
        }
        if(!namear){
          $('#name_error').text("");
          $('#namear_error').text("The name field is required.");
          return false;
        }
        // if(!info){
        //   $('#name_error').text("");
        //   $('#namear_error').text("");
        //   $('#info_error').text("The content field is required.");
        //   return false;
        // }
        // if(!infoar){
        //   $('#name_error').text("");
        //   $('#namear_error').text("");
        //   $('#info_error').text("");
        //   $('#infoar_error').text("The content field is required.");
        //   return false;
        // }
          $('#name_error').text("");
          $('#namear_error').text("");
          $('#info_error').text("");
          $('#infoar_error').text("");
          $.ajax({
            type:'POST',
            url:'{{url("/admin/insert_carcheck")}}',
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
                  window.location.href="{{URL::to('/admin/CarCheck')}}";
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
