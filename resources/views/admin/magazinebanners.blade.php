@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> Magazine Banners</h4>

                    </div>
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                    <p class="alert alert-success" style="display:none;" id="orderUpdateMsg">Order updated successfully
                        !!</p>
                    <a href="{{ url('admin/addmagazineBanner') }}" class="white_btn3">Create</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">

            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <table class="table table-striped" id="example">
                        <thead class="">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name (English)</th>
                                <th scope="col">Name (Arabic)</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">Expiry Date</th>
                                <th scope="col">No. of Click</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            @foreach ($banners as $key => $banner)
                                <tr id="<?php echo $banner->id; ?>">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        <a href="{{ $banner->url }}" target="_blank"><img
                                                src="{{ url('public/img/banners') }}/{{ $banner->image }}"
                                                alt="Banner Image" height="100" width="100"></a>
                                    </td>
                                    <td>{{ $banner->name }}</td>
                                    <td>{{ $banner->name_ar }}</td>
                                    <td>{{ $banner->start_date }}</td>
                                    <td>{{ $banner->end_date }}</td>
                                    <td>{{ $banner->log }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox"
                                                role="switch" id="flexSwitchCheckChecked"
                                                data-id="{{ $banner->id }}" <?php if ($banner->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td><a href="{{ url('admin/addmagazineBanner?id=') }}{{ $banner->id }}"><i
                                                class="fa fa-edit"></i></a> | <a
                                            href="{{ url('admin/deletemagazinebanner') }}/{{ $banner->id }}/MagazineBanner"
                                            onclick="return confirm('Are you sure you want to delete this? ?');"><i
                                                class="fa fa-trash text-danger"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(".row_position").sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $(".row_position>tr").each(function() {
                selectedData.push($(this).attr("id"))
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(aData) {
        var token = "<?php echo csrf_token(); ?>";
        $.ajax({
            url: "{{ url('updatemagazineBannerOrder') }}",
            type: "POST",
            data: {
                allData: aData,
                _token: token
            },
            success: function(data) {
                $(window).scrollTop(0);
                $("#orderUpdateMsg").show();
                $("#orderUpdateMsg").fadeOut(3000);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        });
    }
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
            url: '{{ url('/admin/change_agentbanner_status') }}',
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
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Export',
                exportOptions: {
                    columns: [1, 3, 4, 5, 6,7]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Magazine Banner' + n;
                },
                title: 'Magazine Banner'
            }],
        });
    });
</script>


@include('admin.includes.footer')
