@include('admin.includes.header')
<style>
    .nav-link {
        color: black;
    }
    .image-border .image {
        border: 1px solid #00000038;
    }

</style>
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-md-12">
                <h4 class="f_s_30 f_w_700 text_white mb-4"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>
                        <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Car Detail</li>
                </ol> -->
                <div class="card p-4">
                    <div class="card-body">
                        @if(Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
                        @endif
                        <!-- <a href="{{ url('admin/addBrand') }}" class="white_btn3">Create</a> -->
                        <!-- <button type="button" class="white_btn3" data-toggle="modal" data-target="#myModal"></button> -->
                        <!-- Button trigger modal -->
                        <div class="QA_table table-responsive pb-3">
                            <table class="table p-0" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nick Name</th>
                                        <th scope="col">Model</th>
                                        <th scope="col">Sub-Model</th>
                                        <th scope="col">Action</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $i=1;
                                @endphp
                                @foreach($my_carlist as $value)
                                    <tr>
                                        <td>{{$i}}</td>
                                        {{-- <td>
                                            <img class="img-fluid" src="<?php if($value->mycar_image!=''){ echo url("public/images/mycar/".$value->mycar_image);}else{ ?>../public/img/image-preview.png<?php } ?>" alt width="100" height="100">
                                        </td> --}}
                                        <td>{{$value->car_nick_name}}</td>                                        
                                        <td>{{$value->make}}</td>                                        
                                        <td>{{$value->model}}</td>                                        
                                        <td><a href="{{url('admin/mycardetail?key='.base64_encode($value->id))}}"><i class="fa fa-eye"></i></a></td>
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
                 columns: [ 0,1,2,3]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return 'Mycar' + n;
             },
             title:'My car'
          }],
       });
    });
 </script>


@include('admin.includes.footer')