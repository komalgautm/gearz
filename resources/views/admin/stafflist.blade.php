@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> Staff</h4>

                    </div>
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                    <a href="{{ url('admin/addStaff') }}" class="white_btn3">Create</a>
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
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col" style="display: none">Address</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <img class="img-fluid"
                                            src="<?php if($value->image!=''){ echo url("public/img/".$value->image);}else{ ?>../public/img/image-preview.png<?php } ?>"
                                            alt width="100" height="100">
                                    </td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->username }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->phone }}</td>
                                    <td style="display:none">{{ $value->address }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox"
                                                role="switch" id="flexSwitchCheckChecked" data-id="{{ $value->id }}"
                                                <?php if ($value->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td><a href="{{ url('admin/log?key=' . base64_encode($value->id)) }}"
                                            title="Log Detail"><i class="fa fa-eye"></i></a> | <a
                                            onclick="changepassword(<?= $value->id ?>)" title="Change Password"><i
                                                class="fa fa-key"></i></a> | <a
                                            href="{{ url('admin/addStaff?key=' . base64_encode($value->id)) }}"><i
                                                class="fa fa-edit"></i></a> | <a class="delete"
                                            data-id="{{ $value->id }}"><i class="fa fa-trash"></i></a></td>
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
{{-- model --}}
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="saveform" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-form-label">New Password</label>
                        <input type="text" name="newpassword" id="newpassword" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Confirm Password</label>
                        <input type="text" name="cnfpassword" id="cnfpassword" class="form-control" value="">
                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="staffid" id="staffid" value="">
                    <button id="loadButton" type="button" class="btn btn-primary submitdata">Submit</button>
                    <div class="alert alert-success text-center hide1"><span class="msg_success"></span></div>
                    <div class="alert alert-danger text-center hide2"><span class="msg_danger"></span></div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(".delete").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/delete_staff') }}',
                data: {
                    id: id,
                    _token: token
                },
                success: function(data) {
                    location.reload();
                }

            });
        }
    })
</script>
<script>
    $('.flexSwitchCheckChecked').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        var checkbox = $(this);
        var status = checkbox.is(':checked') ? 1 : 0;
        if (!confirm("Are you sure you want to change this status?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkbox.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/change_staff_status') }}',
            data: {
                id: id,
                _token: token,
                status: status
            },
            success: function(data) {
                location.reload();
            }

        });

    })
</script>
<script>
    function changepassword(th) {
        $('#staffid').val(th);
        $('#staticBackdrop').modal('show');
    }
</script>
<script>
    $('.submitdata').click(function() {
        var newpassword = $('#newpassword').val();
        var cnfpassword = $('#cnfpassword').val();
        if (!newpassword) {
            $('#newpassword').css('border', '1px solid red');
            return false;
        }
        if (!cnfpassword) {
            $('#newpassword').css('border', '');
            $('#cnfpassword').css('border', '1px solid red');
            return false;
        }
        if (newpassword != cnfpassword) {
            $('#newpassword').css('border', '');
            $('#cnfpassword').css('border', '1px solid red');
            return false;
        }


        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/changestaffpassword') }}',
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
                        location.reload();
                    });

                }
                if ($.trim(data) == "2") {
                    $('.hide2').css('display', 'block');
                    $('.msg_danger').text("Something went wrong");
                    $(".alert-danger").show('slow', 'linear').delay(4000).fadeOut();

                }
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
                    columns: [0,2, 3, 4, 5,6]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Staff' + n;
                },
                title: 'Staff'
            }],
        });
    });
</script>

@include('admin.includes.footer')
