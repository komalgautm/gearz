
@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex align-items-center justify-content-between">
            <div class="page_title_left">
                <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{$title}}</h4>

            </div>
            @if(Session::has('message'))
            <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
            @endif
            <a href="{{ url('admin/sendnotification') }}" class="white_btn3">Send Notification</a>
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
                                <th scope="col">User Type</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Title (English)</th>
                                <th scope="col">Title (Arabic)</th>
                                <th scope="col" style="display: none">Message (English)</th>
                                <th scope="col" style="display: none">Message (Arabic)</th>
                                <th scope="col" style="display: none">URL</th>
                                <th scope="col" style="display: none">End date</th>
                                <th scope="col" style="display: none">End time</th>
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
                                <td>
                                    <?php
                                    switch ($value->type) {
                                        case '1':
                                            $usertype = "All user";
                                            break;
                                        case '2':
                                            $usertype = "IOS user";
                                            break;
                                        case '3':
                                            $usertype = "Android user";
                                            break;
                                        case '4':
                                            $usertype = "Active user";
                                            break;
                                        case '5':
                                            $usertype = "Inactive user";
                                            break;
                                        case '6':
                                            $usertype = "Group user";
                                            break;
                                        default:
                                            $usertype = "";
                                            break;
                                    }
                                    echo $usertype;
                                    ?>
                                </td>
                                <td><?php if($value->UserID==0){echo "All"; }else{echo App\Models\User::find($value->UserID)->name; } ?></td>
                                <td><a
                                    href="{{ url('admin/notificationdetails?key=' . base64_encode($value->id) . '&lang=' . base64_encode(1)) }}">{{$value->title}}</a></td>
                                <td><a
                                    href="{{ url('admin/notificationdetails?key=' . base64_encode($value->id) . '&lang=' . base64_encode(2)) }}">{{$value->title_ar}}</a></td>
                                <td style="display: none">{{$value->message}}</td>
                                <td style="display: none">{{$value->message_ar}}</td>
                                <td style="display: none"><?php if($value->internal_external==1){ ?>{{$value->url}}<?php } ?></td>
                                <td style="display: none">{{$value->end_date}}</td>
                                <td style="display: none">{{$value->end_time}}</td>
                                <td><a class="delete" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
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
    if(confirm("Are you sure you want to delete this?")){
      $.ajax({
          type:'POST',
          url:'{{url("/admin/delete_notification")}}',
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
                columns: [ 0,1,2,3,4,5,6,7,8,9]
            },
            filename: function(){
                var d = new Date();
                var n = d.getTime();
                return 'Notification' + n;
            },
            title:'Notification'
         }],
      });
   });
</script>

@include('admin.includes.footer')
