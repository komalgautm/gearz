@include('admin.includes.header')


<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> {{ $title }}</h4>

                    </div>
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                    <a href="{{ url('admin/addDeal') }}" class="white_btn3">Create</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <div class="row justify-content-end">
                <div class="form-group col-md-4">
                    <div class="row mt-3 me-3 align-items-center">
                        <div class="col-md-4 d-flex justify-content-end ">
                            <label for="dealtype">Deal Type :</label>
                        </div>
                        <div class="col-md-8 ps-0">
                            <select class="form-select" name="dealtype" id="dealtype">
                                <option value="">Choose</option>
                                @foreach ($dealtype as $dealvalue)
                                    <option value="{{ $dealvalue->id }}">{{ $dealvalue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <table class="table table-striped" id="example">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name (English)</th>
                                <th scope="col">Name (Arabic)</th>
                                <th scope="col" style="display: none">Discription (English)</th>
                                <th scope="col" style="display: none">Discription (Arabic)</th>
                                <th scope="col">Deal Type</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col" style="display: none">Tag name (English)</th>
                                <th scope="col" style="display: none">Tag name (Arabic)</th>
                                <th scope="col">Visited</th>
                                <th scope="col">No. of Click</th>
                                <th scope="col">Status</th>
                                <th scope="col">Hot Deal</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                                <tr id="<?php echo $value->id; ?>">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <img class="img-fluid" src="{{ url('public/images/' . $value->image) }}" alt
                                            width="100" height="100">
                                    </td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->name_ar }}</td>
                                    <td style="display: none">{{ $value->discription }}</td>
                                    <td style="display: none">{{ $value->discription_ar }}</td>
                                    <td><?php if ($value->typesofdeal != '') {
                                        echo App\Models\DealType::find($value->typesofdeal)->name;
                                    } ?></td>
                                    <td>{{ $value->startDate }}</td>
                                    <td>{{ $value->endDate }}</td>
                                    <td style="display: none">{{ $value->tag_name }}</td>
                                    <td style="display: none">{{ $value->tag_name_ar }}</td>
                                    <td><?php echo $view = App\Models\Viewed::where('menu_type', 4)
                                        ->where('menuID', $value->id)
                                        ->count(); ?></td>
                                    <td>{{ $value->log }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox"
                                                role="switch" id="flexSwitchCheckChecked"
                                                data-id="{{ $value->id }}" <?php if ($value->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedpopular"
                                                type="checkbox" role="switch" id="flexSwitchCheckCheckedpopular"
                                                data-id="{{ $value->id }}" <?php if ($value->popular == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td><a href="{{ url('admin/addDeal?key=' . base64_encode($value->id)) }}"><i
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
            url: "{{ url('updateDealOrder') }}",
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
    $(".delete").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/delete_deal') }}',
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
            url: '{{ url('/admin/change_deal_status') }}',
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
    $('.flexSwitchCheckCheckedpopular').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        var checkboxp = $(this);
        var status = checkboxp.is(':checked') ? 1 : 0;
        if (!confirm("Are you sure you want to add this to the home list?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkboxp.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/add_popular_deal') }}',
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
                    columns: [1, 3, 4, 5, 6,7,8,9,10,11,12,13]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Deal' + n;
                },
                title: 'Deal'
            }],
        });
    });
</script>
<script>
    $('#dealtype').change(function() {
        var id = $('#dealtype').val();
        var token = "<?= csrf_token() ?>";
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/filter_dealtypedata') }}',
            data: {
                id: id,
                _token: token
            },
            success: function(data) {
                // if ($.fn.dataTable.isDataTable('#example')) {
                //     $('#example').DataTable().clear().destroy();
                // }
                $('.row_position').html(data);
                // Reinitialize the DataTable after the new data is loaded
                // $('#example').DataTable({
                //     // Add your DataTable options here
                //     "paging": true,
                //     "ordering": true,
                //     "info": true,
                //     "autoWidth": false,
                //     "responsive": true,
                //     "lengthMenu": [10, 25, 50, 100],  // Example page length options
                //     dom: 'Bfrtip',
                //     buttons: [{
                //         extend: 'excel',
                //         text: 'Export',
                //         exportOptions: {
                //             columns: [1, 3, 4, 5, 6,7,8,9,10,11,12,13 ]
                //         },
                //         filename: function() {
                //             var d = new Date();
                //             var n = d.getTime();
                //             return 'Deal' + n;
                //         },
                //         title: 'Deal'
                //     }],

                // });
            }

        });


    })
</script>

@include('admin.includes.footer')
