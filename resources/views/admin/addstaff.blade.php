
@include('admin.includes.header')
<style>
  .plus, .minus {
            display: inline-block;
    background-repeat: no-repeat;
    background-size: 16px 16px !important;
    width: 16px;
    height: 16px;
            /*vertical-align: middle;*/
        }

        .plus {
            background-image: url(https://img.icons8.com/color/48/000000/plus.png);
        }

        .minus {
            background-image: url(https://img.icons8.com/color/48/000000/minus.png);
        }

        ul {
            list-style: none;
            padding: 0px 0px 0px 20px;
        }

            ul.inner_ul li:before {
                content: "├";
                font-size: 18px;
                margin-left: -11px;
                margin-top: -5px;
                vertical-align: middle;
                float: left;
                width: 8px;
                color: #41424e;
            }

            ul.inner_ul li:last-child:before {
                content: "└";
            }

        .inner_ul {
            padding: 0px 0px 0px 35px;
        }
</style>
<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-12">
        <div class="page_title_box d-flex align-items-center justify-content-between">
          <div class="page_title_left">
            <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> <?php if(isset($data->id)){ echo "Edit"; }else{ echo"Add"; } ?> Staff</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/stafflist') }}">Staff</a></li>
              <li class="breadcrumb-item active">Add Staff</li>
            </ol> -->
          </div>
          @if(Session::has('message'))
          <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
          @endif
          <!-- <a href="#" class="white_btn3">Create Report</a> -->
        </div>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card">
          <div class="card-body p-5">
            <!-- <h2 class="card-title text-center pb-5">Add Brand</h2> -->
            <form method="post" id="saveform" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-sm-2 col-form-label">Name<em>*</em></label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control name" placeholder="Name" id="name" value="@if(isset($data->id)){{$data->name}}@endif">
                <div class="text-danger" id="name_error"></div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="username" class="col-sm-2 col-form-label">Username<em>*</em></label>
              <div class="col-sm-10">
                <input type="text" name="username" class="form-control username" placeholder="Username" id="username" value="@if(isset($data->id)){{$data->username}}@endif">
                <div class="text-danger" id="username_error"></div>
              </div>
            </div>
            <?php if(isset($data->id)){ }else{?>
            <div class="row mb-3">
              <label for="email" class="col-sm-2 col-form-label">Email<em>*</em></label>
              <div class="col-sm-10">
                <input type="text" name="email" class="form-control" placeholder="Email" id="email" value="@if(isset($data->id)){{$data->email}}@endif">
                <div class="text-danger" id="email_error"></div>
              </div>
            </div>
            <?php } ?>
            <div class="row mb-3">
              <label for="phone" class="col-sm-2 col-form-label">Mobile No.<em>*</em></label>
              <div class="col-sm-10">
                <input type="text" name="phone" class="form-control" placeholder="Mobile No." id="phone" value="@if(isset($data->id)){{$data->phone}}@endif">
                <div class="text-danger" id="phone_error"></div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="address" class="col-sm-2 col-form-label">Address</label>
              <div class="col-sm-10">
                <textarea name="address" class="form-control" id="address" cols="5" rows="5" placeholder="Address">@if(isset($data->id)){{$data->address}}@endif</textarea>
                <div class="text-danger" id="address_error"></div>
              </div>
            </div>
            <?php if(isset($data->id)){ }else{?>
            <div class="row mb-3">
              <label for="password" class="col-sm-2 col-form-label">Password<em>*</em></label>
              <div class="col-sm-10">
                <input type="password" name="password" class="form-control" placeholder="Password" id="password" value="@if(isset($data->id)){{$data->password}}@endif">
                <div class="text-danger" id="password_error"></div>
              </div>
            </div>
            <?php } ?>
            <div class="row mb-3">
              <label for="logo" class="col-sm-2 col-form-label">Profile Image<?php if(isset($data->id)){}else {
                echo "<em>*</em>";
              } ?></label>
              <div class="col-sm-10">
                <input type="file" name="image" class="form-control file" placeholder="Logo" id="logo" accept=".jpeg, .jpg, .png"  onchange="previewFile(this);">
                <img id="previewImg" src="<?php if(isset($data->id)){ echo url("public/img/".$data->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                <div class="text-danger" id="logo_error"></div>
              </div>

            </div>

            <!-- permission -->
            <div class="row mb-3">
              <div class="col-sm-2">
                <label for="permission" class=" col-form-label">Permission<em>*</em></label>
                <div class="text-danger" id="permission_error"></div>
              </div>
              <div class="col-sm-10">
                <div class="tree_main">


                  <ul id="bs_main" class="main_ul ps-0">
                    @php
                    $i=1;
                    @endphp
                    @foreach ($ManagementSection as $value)
                      <li id="bs_<?=$i?>">
                          <span class="plus">&nbsp;</span>
                          <input type="checkbox" id="c_bs_<?=$i?>" name="managementsection[]" value="<?=$value->id?>" <?php if(isset($data->id)){ $rights = explode(",",$data->managementsection); foreach($rights as $right_values){ if($right_values == $value->id){ echo"Checked"; } } } ?>/>
                          <span>{{$value->name}}</span>
                          <ul id="bs_l_<?=$i?>" class="sub_ul" style="display: none">
                            <?php
                            $j = 1;
                            $access_right = App\Models\AccessRight::where('management_section_id',$value->id)->get();
                            foreach ($access_right as $accessvalue) {

                            ?>

                            <li id="bf_<?=$j?>">
                                <span class="">&nbsp;</span>
                                <input type="checkbox" id="c_bf_<?=$j?>" class="myCheckboxes" name="accessright[]" value="<?=$accessvalue->id?>" <?php if(isset($data->id)){ $right = explode(",",$data->access_right); foreach($right as $right_value){ if($right_value == $accessvalue->id){ echo"Checked"; } } } ?> />
                                <span><?=$accessvalue->name?></span>
                            </li>
                            <?php $j++; } ?>
                          </ul>
                      </li>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    <?php
                    $accessright = App\Models\AccessRight::where('management_section_id',0)->get();
                    foreach ($accessright as $accessvalues) {
                    ?>
                    <input type="checkbox" name="accessright[]" value="<?=$accessvalues->id?>" Checked style="display:none">
                    <?php } ?>
                  </ul>
              </div>
              </div>
            </div>

            {{-- <div class="tree_main">
              <label for="permission" class="col-sm-2 col-form-label">Permission</label>
              <div class="text-danger" id="permission_error"></div>
              <ul id="bs_main" class="main_ul">
                @php
                $i=1;
                @endphp
                @foreach ($ManagementSection as $value)
                  <li id="bs_<?=$i?>">
                      <span class="plus">&nbsp;</span>
                      <input type="checkbox" id="c_bs_<?=$i?>" name="managementsection[]" value="<?=$value->id?>" <?php if(isset($data->id)){ $rights = explode(",",$data->managementsection); foreach($rights as $right_values){ if($right_values == $value->id){ echo"Checked"; } } } ?>/>
                      <span>{{$value->name}}</span>
                      <ul id="bs_l_<?=$i?>" class="sub_ul" style="display: none">
                        <?php
                        $j = 1;
                        $access_right = App\Models\AccessRight::where('management_section_id',$value->id)->get();
                        foreach ($access_right as $accessvalue) {

                        ?>

                        <li id="bf_<?=$j?>">
                            <span class="">&nbsp;</span>
                            <input type="checkbox" id="c_bf_<?=$j?>" class="myCheckboxes" name="accessright[]" value="<?=$accessvalue->id?>" <?php if(isset($data->id)){ $right = explode(",",$data->access_right); foreach($right as $right_value){ if($right_value == $accessvalue->id){ echo"Checked"; } } } ?> />
                            <span><?=$accessvalue->name?></span>
                        </li>
                        <?php $j++; } ?>
                      </ul>
                  </li>
                @php
                $i++;
                @endphp
                @endforeach
                <?php
                $accessright = App\Models\AccessRight::where('management_section_id',0)->get();
                foreach ($accessright as $accessvalues) {
                ?>
                <input type="checkbox" name="accessright[]" value="<?=$accessvalues->id?>" Checked style="display:none">
                <?php } ?>
              </ul>
          </div>  --}}

            <!-- permission -->

            <div class="row">
              <div class="text-center">
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
  </section>
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
  $(document).ready(function () {
    $(".plus").click(function () {
        $(this).toggleClass("minus").siblings("ul").toggle();
    })

    $("input[type=checkbox]").click(function () {
        //alert($(this).attr("id"));
        //var sp = $(this).attr("id");
        //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
            $(this).siblings("ul").find("input[type=checkbox]").prop('checked', $(this).prop('checked'));
        //}
    })

    $("input[type=checkbox]").change(function () {
        var sp = $(this).attr("id");
        if (sp.substring(0, 4) === "c_io") {
            var ff = $(this).parents("ul[id^=bf_l]").attr("id");
            if ($('#' + ff + ' > li input[type=checkbox]:checked').length == $('#' + ff + ' > li input[type=checkbox]').length) {
                $('#' + ff).siblings("input[type=checkbox]").prop('checked', true);
                check_fst_lvl(ff);
            }
            else {
                $('#' + ff).siblings("input[type=checkbox]").prop('checked', false);
                check_fst_lvl(ff);
            }
        }

        if (sp.substring(0, 4) === "c_bf") {
            var ss = $(this).parents("ul[id^=bs_l]").attr("id");
            if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
                $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
                check_fst_lvl(ss);
            }
            else {
                $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
                check_fst_lvl(ss);
            }
        }
    });

})

function check_fst_lvl(dd) {
    //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
    var ss = $('#' + dd).parent().closest("ul").attr("id");
    if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
        //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
        $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
    }
    else {
        //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
        $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
    }

}

function pageLoad() {
    $(".plus").click(function () {
        $(this).toggleClass("minus").siblings("ul").toggle();
    })
}
</script>
<script>
    $('.submitdata').click(function(){
      var name = $('#name').val();
      var username = $('#username').val();
      var email  = $('#email').val();
      var phone  = $('#phone').val();
      var address  = $('#address').val();
      var password  = $('#password').val();
      var logo  = $('#logo').val();
      var id  = $('#id').val();
      const checkboxes = document.querySelectorAll(".myCheckboxes");
      const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
      var imgfile;
      if(!name){
        $('#name_error').text("The name field is required.");
        return false;
      }
      if(!username){
        $('#name_error').text("");
        $('#username_error').text("The name field is required.");
        return false;
      }
      if(id==''){
        if(!email){
          $('#name_error').text("");
          $('#username_error').text("");
          $('#email_error').text("The email field is required.");
          return false;
        }
        if(!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){
          $('#name_error').text("");
          $('#username_error').text("");
          $('#email_error').text("Please enter valid email");
          return false;
        }
      }
      if(!phone){
        $('#name_error').text("");
        $('#username_error').text("");
        $('#email_error').text("");
        $('#phone_error').text("The mobile number field is required.");
        return false;
      }
      if(!phone.match(/^[0-9]{9,12}$/)){
        $('#name_error').text("");
        $('#username_error').text("");
        $('#email_error').text("");
        $('#phone_error').text("Please enter valid number");
        return false;
      }

      if(id==''){
        if(!password){
          $('#name_error').text("");
          $('#username_error').text("");
          $('#email_error').text("");
          $('#phone_error').text("");
          $('#password_error').text("The password field is required.");
          return false;
        }
      }
      // if(id==''){
      //   if(!logo){
      //     $('#name_error').text("");
      //     $('#username_error').text("");
      //     $('#email_error').text("");
      //     $('#phone_error').text("");
      //     $('#password_error').text("");
      //     $('#logo_error').text("The Profile Image is required.");
      //     return false;
      //   }

      // }
      if (anyChecked) {

        //console.log("At least one checkbox is checked");
      } else {
        $("#permission_error").text("Please give some permission");
        return false;
        //console.log("No checkbox is checked");
      }

      $.ajax({
          type:'POST',
          url:'{{url("/admin/insert_staff")}}',
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
                window.location.href="{{URL::to('/admin/stafflist')}}";
            });

            }
            if($.trim(data)=="2"){
            $('.hide2').css('display','block');
            $('.msg_danger').text("Username already exist");
            $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

            }
            if($.trim(data)=="3"){
            $('.hide2').css('display','block');
            $('.msg_danger').text("Email already exist");
            $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

            }
          }

      });

    })



</script>


@include('admin.includes.footer')
