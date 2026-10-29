
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex align-items-center justify-content-between">
            <div class="page_title_left">
                <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$staff_name}} Log</h4>
                <!-- <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Staff</li>
                </ol> -->
            </div>
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            {{-- <a href="{{ url('admin/addStaff') }}" class="white_btn3">Create</a> --}}
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <div class="QA_table ">
                    <table class="table p-0" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Login Date Time</th>
                                <th scope="col">Logout Date Time</th>
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
                                <td>{{$value->logindatetime}}</td>
                                <td>{{$value->logoutdatetime}}</td>
                                <td><a href="{{url('admin/logDetail?key='.base64_encode($value->logindatetime).'&id='.base64_encode($value->staff_id))}}" title="Log Detail"><i class="fa fa-eye"></i></a></td>
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
          url:'{{url("/admin/delete_staff")}}',
          data  :{id:id,_token:token},
          success:function(data){
            location.reload();
          }

      });
    }
  })
</script>
<script>
    $('.flexSwitchCheckChecked').click(function(){
        var id = $(this).data('id');
        var token = "<?=csrf_token()?>";
        if ($(this).is(':checked')) {
            var status = 1;
        }else{
            var status = 0;
        }
        if(confirm("Are you sure want to change this status?")){
            $.ajax({
                type:'POST',
                url:'{{url("/admin/change_staff_status")}}',
                data  :{id:id,_token:token,status:status},
                success:function(data){
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $('.flexSwitchCheckCheckedpopular').click(function(){
        var id = $(this).data('id');
        var token = "<?=csrf_token()?>";
        if ($(this).is(':checked')) {
            var status = 1;
        }else{
            var status = 0;
        }
        if(confirm("Are you sure want to add this in popular list?")){
            $.ajax({
                type:'POST',
                url:'{{url("/admin/add_popular_brand")}}',
                data  :{id:id,_token:token,status:status},
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
                 columns: [0,1,2]
             },
             filename: function(){
                 var d = new Date();
                 var n = d.getTime();
                 return '<?=$staff_name?> Log' + n;
             },
             title:'<?=$staff_name?> Log'
          }],
       });
    });
 </script>

@include('admin.includes.footer')
