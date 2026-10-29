
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex align-items-center justify-content-between">
            <div class="page_title_left">
                <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>
                <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{$title}}</li>
                </ol> -->
            </div>
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            {{-- <a href="{{ url('admin/addtranslation') }}" class="white_btn3">Create</a> --}}
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <table class="table p-0" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Title (English)</th>
                                <th scope="col">Title (Arabic)</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                        $i=1;
                        @endphp
                        @foreach($data as $value)
                            <tr>
                                <td>{{$i}}</td>                                
                                <td><img class="img-fluid" src="<?php if($value->image!=''){ echo url("public/images/".$value->image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100"></td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->name_ar}}</td>
                                <td><a href="{{url('admin/addknowledgeimagesection?key='.base64_encode($value->id))}}"><i class="fa fa-edit"></i></a></td>                               
                            </tr>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function() {
      $('#example').DataTable({
         dom: 'Bfrtip',
         buttons: [{
            extend: 'excel',
            text: 'Export', 
            exportOptions: {
                columns: [ 0,2,3]
            },
            filename: function(){
                var d = new Date();
                var n = d.getTime();
                return 'Knowledge_image_section' + n;
            },
            title:'Knowledge Image Section'
         }],
      });
   });
</script>

@include('admin.includes.footer')