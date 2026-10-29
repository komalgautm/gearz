
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex align-items-center justify-content-between">
            <div class="page_title_left">
                <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$brand_name}} {{$model_name}} {{$vehicle_name}} Car Price</h4>
                <!-- 
                <ol class="breadcrumb page_bradcam mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Car Price</li>
                </ol>
             -->
            </div>
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            <a href="{{ url('admin/addprice?id='.base64_encode($vehicleID)) }}" class="white_btn3">Create</a>
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <table class="table p-0" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">City</th>
                                <th scope="col">Ex-showroom Price</th>
                                <!-- <th scope="col">RTO</th>
                                <th scope="col">Insurance</th>
                                <th scope="col">Other</th> -->
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
                                <td>{{App\Models\City::find($value->CityID)->name}}</td>
                                <td>{{$value->price}}</td>
                                <!-- <td>{{$value->rto}}</td>
                                <td>{{$value->insurance}}</td>
                                <td>{{$value->other}}</td> -->
                                <td><a href="{{url('admin/addprice?id='.base64_encode($vehicleID).'&key='.base64_encode($value->id))}}"><i class="fa fa-edit"></i></a> | <a class="delete" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
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
  $(".delete").click(function(){
    var id = $(this).data('id');
    var token = "<?=csrf_token()?>";
    if(confirm("Are you sure want to delete this?")){
      $.ajax({
          type:'POST',
          url:'{{url("/admin/delete_carprice")}}',
          data  :{id:id,_token:token},          
          success:function(data){
            location.reload();
          }
          
      });
    }    
  })
</script>
<script>
   $(document).ready(function() {
      $('#example').DataTable({
         dom: 'Bfrtip',
         buttons: [{
            extend: 'excel',
            text: 'Export', 
            exportOptions: {
                columns: [ 0,1,2]
            },
            filename: function(){
                var d = new Date();
                var n = d.getTime();
                return 'Car Price' + n;
            },
            title:'Car Price'
         }],
      });
   });
</script>
@include('admin.includes.footer')
