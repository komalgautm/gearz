
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex align-items-center justify-content-between">
            <div class="page_title_left">
                <h4 class="f_s_30 f_w_700 text_white mb-0"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>

            </div>
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            <a href="{{ url('admin/addserviceType') }}" class="white_btn3">Create</a>
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
                                <th scope="col">Name (English)</th>
                                <th scope="col">Name (Arabic)</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i=1
                            @endphp
                            @foreach($data as $value)
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                <img class="img-fluid" src="{{ url('public/images/'.$value->image) }}" alt width="40" height="40">
                                </td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->name_ar}}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input flexSwitchCheckChecked" type="checkbox" role="switch" id="flexSwitchCheckChecked" data-id="{{$value->id}}" <?php if($value->status==1){ echo "checked"; } ?>>
                                    </div>
                                </td>
                                <td><a href="{{url('/admin/addserviceType?key='.base64_encode($value->id))}}"><i class="fa fa-edit"></i></a> | <a class="delete" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @php
                            $i++
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
    if(confirm("Are you sure you want to delete this?")){
      $.ajax({
          type:'POST',
          url:'{{url("/admin/delete_serviceType")}}',
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
        var checkbox = $(this);
        var status = checkbox.is(':checked') ? 1 : 0;
        if (!confirm("Are you sure you want to change this status?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkbox.prop('checked', !status);
            return;
        }
        $.ajax({
            type:'POST',
            url:'{{url("/admin/change_servicetype_status")}}',
            data  :{id:id,_token:token,status:status},
            success:function(data){
                location.reload();
            }

        });

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
                columns: [ 0, 2,3]
            },
            filename: function(){
                var d = new Date();
                var n = d.getTime();
                return 'Service Type' + n;
            },
            title:'Service Type'
         }],
      });
   });
</script>

@include('admin.includes.footer')
