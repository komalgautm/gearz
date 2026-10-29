
@include('admin.includes.header')

<!-- JS & CSS library of MultiSelect plugin -->
<script src="{{ asset('public/js/jquery.multiselect.js') }}"></script>
<link rel="stylesheet" href="{{ asset('public/css/jquery.multiselect.css') }}">
<style>
  .form-checks{ margin-top:41px }
  /* .pageid{display: none;} */

  .state_select .dropdown {
      position: relative;
      font-size: 14px;
      color: #333;
    }

    .state_select .dropdown-list a {
      color: #9a783b;
      font-weight: 500;

    }

    .state_select .dropdown-list {
      padding: 12px;
      background: #fff;
      position: absolute;
      top: 30px;
      left: 2px;
      right: 2px;
      box-shadow: 0 1px 2px 1px rgba(0, 0, 0, .15);
      transform-origin: 50% 0;
      transform: scale(1, 0);
      transition: transform .15s ease-in-out .15s;
      max-height: 26vh;
      overflow-y: scroll;
    }

    .state_select .dropdown-list::-webkit-scrollbar {
      width: 5px;
    }

    .state_select .dropdown-list::-webkit-scrollbar-thumb {
      background-color: #ddd;
      border-radius: 5px;
    }

    .state_select .dropdown-option {
      display: block;
      padding: 8px 12px;
      opacity: 0;
      transition: opacity .15s ease-in-out;
    }

    .state_select .dropdown-label {
      display: block;
      height: 39px;
      background: #fff;
      border: 1px solid #ced4da;
      padding: 10px 12px;
      line-height: 1;
      cursor: pointer;
      border-radius: 5px;
      position: relative;
    }

    .state_select .dropdown-label:before {
      content: '▼';
      font-size: 10px;
      position: absolute;
      right: 12px;
      top: 13px;
      color: #424244;
    }

    .state_select .dropdown.on .dropdown-list {
      transform: scale(1, 1);
      transition-delay: 0s;
    }

    .state_select .dropdown.on .dropdown-list .dropdown-option {
      opacity: 1;
      transition-delay: .2s;
    }

    .state_select .dropdown.on .dropdown-label:before {
      content: '▲';
    }

    .state_select [type="checkbox"] {
      position: relative;
      top: -1px;
      margin-right: 4px;
    }

    .state_select .nav-tabs .nav-link {
      color: #424244;
      font-weight: 600;
    }
    .form-check-input:checked {
        background-color: #766c2c;
        border-color: #766c2c;
        padding: 0px;
        width: 15px;
        height: 15px;
    }
</style>

<?php
if(isset($data->id)){
  if($data->internalpage!=''){
    if($data->internalpage==1){
?>
<style>
  .pageid{display: none;}
</style>

    <?php }else{?>

<style>
  .pageid{display: block;}
</style>
    <?php }
  }else{
    ?>

<style>
  .pageid{display: none;}
</style>
  <?php }
}else{?>
<style>
  .pageid{display: none;}
</style>
<?php }
?>
<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
        <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> <?php if(isset($data->id)){ echo "Edit"; }else{ echo "Add"; } ?> {{$title}}</h4>

            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/dealList') }}">{{$title}}</a></li>
              <li class="breadcrumb-item active"><?php if(isset($data->id)){ echo "Update"; }else{ echo "Add"; } ?> {{$title}}</li>
            </ol> -->

        <div class="card px-3">
          <div class="card-body">

            <!-- @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif -->
            <!-- <p class="alert alert-success" style="display:none" id="msg"></p> -->

            <!-- <a href="#" class="white_btn3">Create Report</a>style="border: 1px solid #d7d7d7;" -->
            <!-- <h2 class="card-title text-center pb-5"><?php if(isset($data->id)){ echo "Update"; }else{ echo "Add"; } ?> Banner</h2> -->

            <form id="formData" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <label for="name" class="col-form-label">Where to Show<em>*</em></label>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="whereshow"
                            id="exampleRadios1" value="1" <?php if (isset($data->id)) {
                                if ($data->whereshow == 1) {
                                    echo 'checked';
                                }
                            } else {
                                echo 'checked';
                            } ?>>
                        <label class="form-check-label" for="exampleRadios1">
                            All Deals
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="whereshow"
                            id="exampleRadios2" value="2" <?php if (isset($data->id)) {
                                if ($data->whereshow == 2) {
                                    echo 'checked';
                                }
                            } ?>>
                        <label class="form-check-label" for="exampleRadios2">
                            Agent/Reseller
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="whereshow"
                            id="exampleRadios3" value="3" <?php if (isset($data->id)) {
                                if ($data->whereshow == 3) {
                                    echo 'checked';
                                }
                            } ?>>
                        <label class="form-check-label" for="exampleRadios3">
                            Both
                        </label>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="name" class="col-form-label">Name (English)<em>*</em></label>
                  <input type="text" name="name" class="form-control" placeholder="Name" id="name" value="<?php if(isset($data->id)){ echo $data->name; } ?>">
                  <div class="text-danger" id="name_error"></div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="namear" class="col-form-label">Name (Arabic)<em>*</em></label>
                  <input type="text" name="namear" class="form-control" placeholder="Name (Arabic)" id="namear" value="<?php if(isset($data->id)){ echo $data->name_ar; } ?>">
                  <div class="text-danger" id="namear_error"></div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="page" class="col-form-label">App Page Name</label>
                  <Select class="form-select" name="page" id="page">
                    <option value="">Choose</option>
                    @foreach($InternalAppPage as $page)
                    <option <?php if(isset($data->id)){ if($page->id==$data->internalpage){ echo"selected"; } } ?> value="{{$page->id}}">{{$page->name}}</option>
                    @endforeach
                  </Select>
                </div>

                <div class="col-md-6 mb-3 pageid">
                  <label for="pageid" class="col-form-label">Inner Page</label>
                  <Select class="form-select" name="pageid" id="pageid">
                    <?php
                      if(isset($data->id)){
                        if($data->internalpage!=""){
                          if($data->internalpage == 2){
                              //brand
                              $datap = App\Models\Brand::where('status',1)->get();
                              foreach($datap as $value){
                                if($value->id==$data->pageid){
                                  $selected = "selected";
                                }else{
                                  $selected = "";
                                }
                                  echo "<option value='".$value->id."' ".$selected.">".$value->name."</option>";
                              }
                          }
                          if($data->internalpage == 3){
                              //car
                              $datap = App\Models\Vehicle::where('status',1)->get();
                              foreach($datap as $value){
                                if($value->id==$data->pageid){
                                  $selected = "selected";
                                }else{
                                  $selected = "";
                                }
                                  echo "<option value='".$value->id."' ".$selected.">".$value->name."</option>";
                              }
                          }
                          if($data->internalpage == 4){
                              //agent
                              $datap = App\Models\Agent::where('status',1)->get();
                              foreach($datap as $value){
                                if($value->id==$data->pageid){
                                  $selected = "selected";
                                }else{
                                  $selected = "";
                                }
                                  echo "<option value='".$value->id."' ".$selected.">".$value->name."</option>";
                              }
                          }
                          if($data->internalpage == 5){
                              //event
                              $datap = App\Models\Magazine::where('status',1)->where('type',4)->get();
                              foreach($datap as $value){
                                if($value->id==$data->pageid){
                                  $selected = "selected";
                                }else{
                                  $selected = "";
                                }
                                  echo "<option value='".$value->id."' ".$selected.">".$value->heading."</option>";
                              }
                          }
                          if($data->internalpage == 6){
                              //news
                              $datap = App\Models\Magazine::where('status',1)->where('type','!=',4)->get();
                              foreach($datap as $value){
                                if($value->id==$data->pageid){
                                  $selected = "selected";
                                }else{
                                  $selected = "";
                                }
                                  echo "<option value='".$value->id."' ".$selected.">".$value->heading."</option>";
                              }
                          }
                          if($data->internalpage == 7){
                              //deal
                              $datap = App\Models\Deal::where('status',1)->get();
                              foreach($datap as $value){
                                if($value->id == $data->pageid){
                                  $selected = "selected";
                                }else{
                                  $selected = "";
                                }
                                  echo "<option value='".$value->id."' ".$selected.">".$value->name."</option>";
                              }
                          }
                        }

                      }else{?>
                    <option value="">Choose</option>
                    <?php } ?>
                  </Select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="startDate" class="col-form-label">Start Date<em>*</em></label>
                  <input type="date" name="startDate" class="form-control" id="startDate" min="<?=date('Y-m-d')?>" value="<?php if(isset($data->id)){ echo $data->startDate; }else{ echo date('Y-m-d'); } ?>">
                  <div class="text-danger" id="startDate_error"></div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="endDate" class="col-form-label">Expiry Date<em>*</em></label>
                  <input type="date" name="endDate" class="form-control" id="endDate" min="<?=date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'))?>" value="<?php if(isset($data->id)){ echo $data->endDate; }else{ echo date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day')); } ?>">
                  <div class="text-danger" id="endDate_error"></div>
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

                  <div class="col-md-6 stateoption">
                    <div class="state_select">
                      <label for="state" class="col-form-label">State<em>*</em></label>
                      <div class="dropdown" data-control="checkbox-dropdown">
                          <label class="dropdown-label">Select</label>
                          <div class="dropdown-list" id="stateDatas">
                              {{-- <a href="#" data-toggle="check-all" class="dropdown-option">
                                  Check All
                              </a> --}}
                              @if(isset($data->id))
                              {{-- <a href="#" data-toggle="check-all" class="dropdown-option">
                                Check All
                            </a> --}}
                                <a class="dropdown-option stateallcheck" onclick="checkallstate()" style="cursor:pointer">
                                  Check All
                                </a>
                                @foreach($state as $value)
                                  <label class="dropdown-option">
                                      <input type="checkbox" name="state[]" class="checkstatename" onchange="checkedstatevalue()" value="{{ $value->id }}"
                                      <?php foreach ($statedata as $values) { if($values == $value->id) { echo "checked"; }} ?>>
                                      {{ $value->name }}
                                  </label>
                                  @endforeach
                              @endif


                          </div>
                      </div>
                      <div class="text-danger" id="state_error"></div>
                    </div>
                  </div>
                <div class="col-md-6 mb-3">
                  <div class="state_select cityselect">
                  <label for="end_date" class="col-form-label">City<em>*</em></label>
                  <div class="dropdown" data-control="checkbox-dropdown">
                    <label class="dropdown-label">Select</label>
                    <div class="dropdown-list" id="cityDatas">
                      @if(isset($data->id))
                      <a class="dropdown-option cityallcheck" onclick="checkallcity()" style="cursor:pointer">
                        Check All
                        </a>
                      @foreach($city as $cityvalue)
                        <label class="dropdown-option">
                          <input type="checkbox" name="city[]" class="checkcityname" value="{{$cityvalue->id}}" <?php foreach ($dealdetail as $dvalue) { if($dvalue->cityID == $cityvalue->id){ echo "checked"; }} ?>>{{$cityvalue->name}}
                        </label>


                      @endforeach
                      @endif

                    </div>
                  </div>
                  {{-- <select class="js-example-basic-single form-control form-select" name="city[]" id="city" multiple>
                    @if(isset($data->id))
                    <option value="All">All</option>
                      @foreach($city as $cityvalue)
                      <option value="{{$cityvalue->id}}" <?php foreach ($dealdetail as $dvalue) { if($dvalue->cityID == $cityvalue->id){ echo "selected"; }} ?>>{{$cityvalue->name}}</option>
                      @endforeach
                    @endif
                  </select> --}}
                  <div class="text-danger" id="city_error"></div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="end_date" class="col-form-label">Deal Type<em>*</em></label>
                  <select class="form-select" id="dealtype" name="dealtype">
                    <option <?php if(isset($data->id)){if($data->dealType==1){ echo"Selected"; }} ?> value="1">Brand</option>
                    <option <?php if(isset($data->id)){if($data->dealType==2){ echo"Selected"; }} ?> value="2">Agent/Reseller</option>
                  </select>
                  <div class="text-danger" id="dealtype_error"></div>
                </div>

                <div class="col-md-6">
                  <label for="end_date" class="col-form-label dealtypename"><?php if(isset($data->id)){ if($data->dealType==1){ echo"Brand"; }else{ echo"Agent"; } }else{ echo "Brand"; }  ?><em>*</em></label>
                  <?php if(isset($data->id)){
                    if($data->dealType==1){
                  ?>
                  <select class="form-select" name="dealtypeid" id="dealtypeid">
                    @foreach($brand as $bvalue)
                    <option <?php if(isset($data->id)){if(count($dealdetail)!=0){ if($dealdetail[0]->dealTypeID==$bvalue->id){ echo"Selected"; }}} ?> value="{{$bvalue->id}}">{{$bvalue->name}}</option>
                    @endforeach

                  </select>
                  <?php }else{ ?>
                    <select class="form-select" name="dealtypeid" id="dealtypeid">
                    @foreach($agent as $avalue)
                    <option <?php if(isset($data->id)){if(count($dealdetail)!=0){if($dealdetail[0]->dealTypeID==$avalue->id){ echo"Selected"; }}} ?> value="{{$avalue->id}}">{{$avalue->name}}</option>
                    @endforeach

                  </select>

                  <?php } }else{ ?>

                  <select class="form-select" name="dealtypeid" id="dealtypeid">
                    @foreach($brand as $bvalue)
                    <option value="{{$bvalue->id}}">{{$bvalue->name}}</option>
                    @endforeach

                  </select>
                  <?php } ?>
                  <div class="text-danger" id="dealtypeid_error"></div>
                </div>
                {{--  --}}
                <div class="col-md-6">
                  <label for="end_date" class="col-form-label">Types of Deal<em>*</em></label>
                  <select class="form-select" name="dealtypesid" id="dealtypesid">
                    @foreach($dealtype as $dealtypevalue)
                    <option value="{{$dealtypevalue->id}}">{{$dealtypevalue->name}}</option>

                    @endforeach
                  </select>
                  <div class="text-danger" id="dealtypesid_error"></div>
                </div>
                {{--  --}}

                <div class="col-md-12">
                  <label for="discription" class="col-form-label">Discription (English)<em>*</em></label>
                  <textarea class="form-control" name="discription" id="discription"><?php if(isset($data->id)){ echo $data->discription; } ?></textarea>
                  <div class="text-danger" id="discription_error"></div>
                </div>
                <div class="col-md-12">
                  <label for="discriptionar" class="col-form-label">Discription (Arabic)<em>*</em></label>
                  <textarea class="form-control" name="discriptionar" id="discriptionar"><?php if(isset($data->id)){ echo $data->discription_ar; } ?></textarea>
                  <div class="text-danger" id="discriptionar_error"></div>
                </div>


                <div class="col-md-12 mb-3">
                  <label for="images" class="col-form-label">Image<?php if(isset($data->id)){}else { echo "<em>*</em>"; } ?></label>
                  <input type="file" name="images"class="form-control files mb-2" placeholder="images" id="images" accept=".jpeg, .jpg, .png, .gif"  >
                  <span class="text-danger"><b>Note : </b>Recommended image size is 16:9 aspect ratio (e.g., 1280×720 pixels). Maximum file size: 2MB.</span><br>
                  <div class="text-center">
                  <img id="previewImgs" class="mt-2" src="<?php if(isset($data->id)){ echo url("public/images/".$data->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                  </div>
                  <div class="text-danger" id="image_error"></div>
                </div>
                {{-- tag --}}
                <div class="row mb-3">
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
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="tag_icon" class="col-form-label">Tag Icon</label>
                    <input type="file" name="tag_icon" id="tag_icon" class="form-control file" placeholder="Logo" accept=".jpeg,.jpg,.png,.gif">
                    <span class="text-danger"><b>Note : </b>Recommended size will be 1:1</span><br>
                    <img id="previewImgtag" src="<?php if(isset($data->id)){ if($data->tag_icon!=''){ echo url("public/images/".$data->tag_icon);}else{?> ../public/img/image-preview.png <?php }}else{ ?>../public/img/image-preview.png<?php } ?>" alt="Placeholder" width="100px">
                    <div class="text-danger" id="tag_icon_error"></div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-checks">
                      <input class="form-check-inputs" type="checkbox" name="addasanews" value="1" id="addasanews">
                      <label class="col-form-label" for="addasanews"> Add as a News</label>
                    </div>
                  </div>
                </div>


              </div>

              <div class="row mb-4">
                <div class="col-sm-12 text-center">
                  <input type="hidden" class="id" name="id" id="id" value="@if(isset($data->id)) {{$data->id}} @endif">
                  <button type="button" class="btn submitdata">Submit</button>
                  <div id="submitError" class="text-danger mt-3"></div>
                </div>
                <div class="alert alert-success text-center hide1"><span class="msg_success"></span></div>
                <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
  $('#page').change(function(){
    var id= $(this).val();
    var token = "<?=csrf_token()?>";
    if(id==1){
      $('.pageid').css('display','none');
    }else{
      $('.pageid').css('display','block');
      $.ajax({
        type:'POST',
        url:"{{url('/getmultiplepage')}}",
        data:{id:id,_token:token},
        success:function(res){
          $('#pageid').html(res)

        }
      })
    }

  })
</script>
<script>
  var imgfile = "0";
  document.getElementById('images').addEventListener('change', function(event) {
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
          const fileInput = document.getElementById('images');
          fileInput.value = '';
          document.getElementById('image_error').textContent = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
          document.getElementById('previewImgs').style.display = 'none';
          // Clean up if the ratio is invalid
          URL.revokeObjectURL(objectURL);
          imgfile="1";
        }else{
          document.getElementById('previewImgs').src = objectURL;
          document.getElementById('previewImgs').style.display = 'block';
          document.getElementById('image_error').style.display = 'none';
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
          //     document.getElementById('image_error').style.display = 'none';
          //     imgfile="0";
          // } else {
          //   const fileInput = document.getElementById('images');
          //     fileInput.value = '';
          //     document.getElementById('image_error').textContent = `Invalid aspect ratio: ${aspectRatio.toFixed(2)}. Expected: ${desiredAspectRatio.toFixed(2)}`;
          //     document.getElementById('previewImgs').style.display = 'none';
          //     // Clean up if the ratio is invalid
          //     URL.revokeObjectURL(objectURL);
          //     imgfile="1";
          // }
      };

      img.onerror = function() {
          document.getElementById('image_error').textContent = 'Failed to load image.';
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
  $('#dealtype').change(function(){
    var id = $('#dealtype').val();
    var token = "<?=csrf_token()?>";
    if(id==1){
      $('.dealtypename').text("Brand");
    }else{
      $('.dealtypename').text("Agent / Reseller");
    }
    //alert(id);
    $.ajax({
      type:'POST',
      url:"{{url('/getdealtype')}}",
      data:{id:id,_token:token},
      success:function(res){
          $('#dealtypeid').html(res)
      }
    })
  })
</script>
<script>
    $('.submitdata').click(function(){
      var name  = $('#name').val();
      var discription  = $('#discription').val();
      var startDate  = $('#startDate').val();
      var endDate  = $('#endDate').val();
      var city  = $('#city').val();
      var dealtype  = $('#dealtype').val();
      var dealtypeid  = $('#dealtypeid').val();
      var dealtypesid  = $('#dealtypesid').val();
      var images  = $('#images').val();
      var id  = $('#id').val();
      if(!name){
        $('#name_error').text("The name field is required.");
        return false;
      }
      if(!discription){
        $('#name_error').text("");
        $('#discription_error').text("The discription field is required.");
        return false;
      }
      if(!startDate){
        $('#name_error').text("");
        $('#discription_error').text("");
        $('#startDate_error').text("The start Date field is required.");
        return false;
      }
      if(!endDate){
        $('#name_error').text("");
        $('#discription_error').text("");
        $('#startDate_error').text("");
        $('#endDate_error').text("The end Date field is required.");
        return false;
      }
      if(new Date(startDate) > new Date(endDate)){
        $('#startDate_error').text("Start Date must be less then Expiry Date");
        return false;
      }

      if(!dealtype){
        $('#name_error').text("");
        $('#discription_error').text("");
        $('#startDate_error').text("");
        $('#endDate_error').text("");
        $('#dealtype_error').text("The deal type field is required.");
        return false;
      }
      if(!dealtypeid){
        $('#name_error').text("");
        $('#discription_error').text("");
        $('#startDate_error').text("");
        $('#endDate_error').text("");
        $('#dealtype_error').text("");
        $('#dealtypeid_error').text("This field is required.");
        return false;
      }
      if(id==''){
        if(!images){
          $('#name_error').text("");
          $('#discription_error').text("");
          $('#startDate_error').text("");
          $('#endDate_error').text("");
          $('#dealtype_error').text("");
          $('#dealtypeid_error').text("");
          $('#image_error').text("The image field is required.");
          return false;
        }
      }else if(imgfile =="1"){
        $('#name_error').text("");
        $('#discription_error').text("");
        $('#startDate_error').text("");
        $('#endDate_error').text("");
        $('#dealtype_error').text("");
        $('#dealtypeid_error').text("");
        $('#image_error').text("The image field is required.");
        return false;
      }
      $.ajax({
          type:'POST',
          url:'{{url("/admin/insertdealtype")}}',
          data  :new FormData( $("#formData")[0] ),
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
                window.location.href="{{URL::to('/admin/dealList')}}";
            });

            }
            if($.trim(data)=="2"){
            $('.hide2').css('display','block');
            $('.msg_danger').text("Name already exist");
            $(".alert-danger").show('slow' , 'linear').delay(4000).fadeOut();

            }
          },
          error: function(xhr) {
            console.log(xhr);
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
  $('#country').change(function(){
    //$("#state").multiselect('destroy');
    //$("#state").multiselect();
      var country_id = $(this).val();
      var token = "<?=csrf_token()?>";
      if(country_id==191){
        $('.stateoption').css('display','none');
      }else{
        $('.stateoption').css('display','block');
      }
      if(!country_id){
          $('#country').css("border","1px solid red");
      }else{
        if(country_id!=191){

            $('#country').css("border","");
            $.ajax({
                type:'POST',
                url:"{{url('/getmultipleState')}}",
                data:{country_id:country_id,_token:token},
                success:function(res){
                    console.log(res);

                    $('#stateDatas').html(res)
                    //$("#state").multiselect();
                    //$('.js-example-basic-single').select2();

                }
            })
        }else{
            $.ajax({
                type:'POST',
                url:"{{url('/getmultipleCity')}}",
                data:{state_id:0,country_id:country_id,_token:token},
                success:function(res){
                console.log(res);
                    $('#cityDatas').html(res)

                }
            })
        }


      }
  });

</script>
<script>
    (function ($) {
      var CheckboxDropdown = function (el) {
        var _this = this;
        this.isOpen = false;
        this.areAllChecked = false;
        this.$el = $(el);
        this.$label = this.$el.find('.dropdown-label');
        this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
        this.$inputs = this.$el.find('[type="checkbox"]');

        this.onCheckBox();

        this.$label.on('click', function (e) {
          e.preventDefault();
          _this.toggleOpen();
        });

        this.$checkAll.on('click', function (e) {
          e.preventDefault();
          _this.onCheckAll();
        });

        this.$inputs.on('change', function (e) {
          _this.onCheckBox();
        });
      };

      CheckboxDropdown.prototype.onCheckBox = function () {
        this.updateStatus();
      };

      CheckboxDropdown.prototype.updateStatus = function () {
        var checked = this.$el.find(':checked');

        this.areAllChecked = false;
        this.$checkAll.html('Check All');

        if (checked.length <= 0) {
          this.$label.html('Select Options');

        }
        else if (checked.length === 1) {
          this.$label.html(checked.parent('label').text());
        }
        else if (checked.length === this.$inputs.length) {
          this.$label.html('All Selected');
          this.areAllChecked = true;
          this.$checkAll.html('Uncheck All');
        }
        else {
          this.$label.html(checked.length + ' Selected');
        }
      };

      CheckboxDropdown.prototype.onCheckAll = function (checkAll) {
        if (!this.areAllChecked || checkAll) {
          this.areAllChecked = true;
          this.$checkAll.html('Uncheck All');
          this.$inputs.prop('checked', true);
        }
        else {
          this.areAllChecked = false;
          this.$checkAll.html('Check All');
          this.$inputs.prop('checked', false);
        }

        this.updateStatus();
      };

      CheckboxDropdown.prototype.toggleOpen = function (forceOpen) {
        var _this = this;

        if (!this.isOpen || forceOpen) {
          this.isOpen = true;
          this.$el.addClass('on');
          $(document).on('click', function (e) {
            if (!$(e.target).closest('[data-control]').length) {
              _this.toggleOpen();
            }
          });
        }
        else {
          this.isOpen = false;
          this.$el.removeClass('on');
          $(document).off('click');
        }
      };

      var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
      for (var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
        new CheckboxDropdown(checkboxesDropdowns[i]);
      }
    })(jQuery);

  </script>
<script>
  function checkedstatevalue(){
    var checkedValues = [];
    if (this.checked) {

      checkedValues.push($(this).val());
    }
    getCheckedValues();

  }

</script>
<script>
  var state = false;
  function checkallstate(){

    $('.checkstatename').each(function() {
    	if(!state) {
        	this.checked = true;
        } else {
         	this.checked = false;
        }

    });

    //switch
    if (state) {
      //console.log(1);
      $('.stateallcheck').text("Check All");

     	state = false;
    } else {
      $('.stateallcheck').text("Uncheck All");
      //console.log(2);
        state = true;
    }
    getCheckedValues();

  }

  function getCheckedValues() {
    var token = "<?=csrf_token()?>";
    var checkedValues = [];
    $('.checkstatename:checked').each(function() {
        checkedValues.push($(this).val()); // Assuming the value of the checkbox is what you want
    });
    var country_id = $('#country').val();
    $.ajax({
        type:'POST',
        url:"{{url('/getmultipleCity')}}",
        data:{state_id:checkedValues,country_id:country_id,_token:token},
        success:function(res){
          console.log(res);
            $('#cityDatas').html(res)

        }
    })
    console.log(checkedValues); // Log the checked values to the console
    return checkedValues; // You can return this if needed elsewhere
}
</script>
<script>
  var city = false;
  function checkallcity(){
    //alert();
    $('.checkcityname').each(function() {
    	if(!city) {
        	this.checked = true;
        } else {
         	this.checked = false;
        }

    });

    //switch
    if (city) {
      //console.log(1);
      $('.cityallcheck').text("Check All");

      city = false;
    } else {
      $('.cityallcheck').text("Uncheck All");
      //console.log(2);
      city = true;
    }



  }

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectElement = document.getElementById("country");
        const saudiOption = Array.from(selectElement.options).find(option => option.value === "191");

        if (saudiOption) {
            // Remove Saudi Arabia from its current position
            selectElement.remove(saudiOption.index);
            // Add it to the top (after the "Choose" option)
            const chooseOption = selectElement.options[0];
            chooseOption.insertAdjacentHTML(
                "afterend",
                `<option value="191">Saudi Arabia</option>`
            );

        }
    });
</script>



@include('admin.includes.footer')
