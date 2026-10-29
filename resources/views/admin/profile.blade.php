@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
  <div class="container-fluid p-0 ">
    <div class="row">
      <div class="col-md-10">
            <h4 class="f_s_30 f_w_700 text_white mb-4">Profile</h4>
            <!-- <ol class="breadcrumb page_bradcam mb-0">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol> -->
        <div class="card p-4">
          <div class="card-body">
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            <!-- <a href="#" class="white_btn3">Create Report</a> -->
            <!-- <h2 class="card-title text-center pb-5">Profile</h2> -->
            <form action="{{ url('/admin/update_profile') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="row mb-3">
                <!-- <div class="col-md-2">
                  <label for="inputEmail3" class="col-form-label">Profile Image</label>
                </div> -->
                <div class="col-sm-12">
                  <div class="prof_img">
                    <input type="hidden" name="id" value="{{ $admin->id }}">
                    <input type="hidden" name="pre_profile_img" value="{{ $admin->image }}">
                    <input type="file" name="profile_img" id="profile_img" class="form-control" placeholder="Profile Image" id="inputText" accept=".jpeg, .jpg, .png">
                    <label for="profile_img"><img src="{{ url('public/img') }}/{{ $admin->image }}" alt="Profile Image" width="100" height="100" style="object-fit: cover;"></label>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="name" class="col-form-label">Name</label>
                  <input type="text" name="name" value="{{ $admin->name }}" class="form-control" placeholder="Name" id="name" required>
                  <!-- <span class="text-danger" id="headingCount"></span> -->
                </div>
                <div class="col-md-6">
                  <label for="email" class="col-form-label">Email</label>
                  <input type="email" name="email" value="{{ $admin->email }}" class="form-control" placeholder="Email" id="email" required>
                  <!-- <span class="text-danger" id="headingCount"></span> -->
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="phone" class="col-form-label">Phone</label>
                  <input type="text" name="phone" value="{{ $admin->phone }}" class="form-control" placeholder="Phone" id="phone" required>
                  <!-- <span class="text-danger" id="headingCount"></span> -->
                </div>
                <div class="col-md-6">
                  <label for="address" class="col-form-label">Address</label>
                  <input type="text" name="address" value="{{ $admin->address }}" class="form-control" placeholder="Address" id="address" required>
                  <!-- <span class="text-danger" id="DesignationCount"></span> -->
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn submitdata">Update</button>
                </div>
              </div>
            </form><!-- End Horizontal Form -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('admin.includes.footer')