@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-8">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/category') }}">{{$title}}</a></li>
              <li class="breadcrumb-item active">{{$title}} Form</li>
            </ol> -->

          @if(Session::has('message'))
          <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
          @endif
          <!-- <a href="#" class="white_btn3">Create Report</a> -->
        <div class="card p-4">
          <div class="card-body">
            <div class="section">
              <!-- <h2 class="card-title text-center pb-5">Add Brand</h2> -->
              <form method="post" id="saveform" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-sm-6 mb-4">
                    <label for="name" class="col-form-label">Category (English)<em>*</em></label>
                    <input type="text" name="name" class="form-control name" placeholder="Category Name (English)" id="name" value="@if(isset($data->id)) {{$data->name}} @endif" required>
                  </div>
                  <div class="col-sm-6 mb-4">
                    <label for="namear" class="col-form-label">Category (Arabic)<em>*</em></label>
                    <input type="text" name="namear" class="form-control namear" placeholder="Category Name (Arabic)" id="namear" value="@if(isset($data->id)) {{$data->name_ar}} @endif" required>
                  </div>
                  <div class="col-sm-12 text-center">
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
</div>
<script>
  $('.submitdata').click(function() {
    var name = $('.name').val();
    var namear = $('.namear').val();
    if (name == '') {
      $('.name').css('border', '1px solid red');
    }else if(!namear){
      $('.name').css('border', '');
      $('.namear').css('border', '1px solid red');
    }else{
      $('.name').css('border', '');
      $('.namear').css('border', '');
      $.ajax({
        type: 'POST',
        url: '{{url("/admin/insert_category")}}',
        data: new FormData($("#saveform")[0]),
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          console.log(data);
          if ($.trim(data) == "1") {
            $('.hide1').css('display', 'block');
            $('.msg_success').text("Sucessfully submitted");
            $(".alert-success").show('slow', 'linear').delay(4000).fadeOut(function() {
              window.location.href = "{{URL::to('/admin/category')}}";
            });

          }
          if ($.trim(data) == "2") {
            $('.hide2').css('display', 'block');
            $('.msg_danger').text("Name already exist");
            $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

          }
        }

      });
    }
  })
</script>

@include('admin.includes.footer')
