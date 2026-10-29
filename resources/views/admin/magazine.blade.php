@include('admin.includes.header')
<style>
    .table img {
        object-fit: cover;
    }
</style>
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
                    <a href="{{ url('admin/addMagazine') }}" class="white_btn3">Create</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->

            <div class="white_card_body text-center">
                <div class="row justify-content-end">
                    <div class="form-group col-md-3">
                        <div class="row mt-3 me-3 align-items-center">
                            <div class="col-md-4 d-flex justify-content-end ">
                                <label for="maincategory">Category:</label>
                            </div>
                            <div class="col-md-8 ps-0">
                                <select class="form-select" name="maincategory" id="maincategory">
                                    <option value="">Choose</option>
                                    @foreach ($category as $category_value )
                                        <option value="{{$category_value->id}}">{{$category_value->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="row mt-3 me-3 align-items-center">
                            <div class="col-md-4 d-flex justify-content-end ">
                                <label for="Subcategory">Sub Category:</label>
                            </div>
                            <div class="col-md-8 ps-0">
                                <select class="form-select" name="Subcategory" id="Subcategory">
                                    <option value="">Choose</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="QA_table table-responsive pb-3 pt-5">
                    <table class="table p-0" id="example">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">#</th>
                                <!-- <th scope="col" style="display: none">Category</th>
                                <th scope="col" style="display: none">Sub Category</th> -->
                                <th scope="col">Heading (English)</th>
                                <th scope="col">Heading (Arabic)</th>
                                <!-- <th scope="col" style="display: none">Description (English)</th>
                                <th scope="col" style="display: none">Description (Arabic)</th>
                                <th scope="col" style="display: none">videourl</th>
                                <th scope="col" style="display: none">Created By</th>
                                <th scope="col" style="display: none">Start Date</th>
                                <th scope="col" style="display: none">End Date</th>
                                <th scope="col" style="display: none">Tag name (English)</th>
                                <th scope="col" style="display: none">Tag name (Arabic)</th> -->
                                <th scope="col">Total View</th>
                                <th scope="col">No. of Click</th>
                                <th scope="col">Hot News</th>
                                <th scope="col">Add Banner</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                            <?php
                            if($value->categoryID!=""){
                                $checkcategory = App\Models\MagazineCategory::where('id',$value->categoryID)->count();
                                if($checkcategory!=0){
                                    $categoryname = App\Models\MagazineCategory::where('id',$value->categoryID)->first()->name;
                                }else{
                                    $categoryname = '';
                                }
                            }
                            if($value->subcategoryID!=""){
                                $checksubcategory = App\Models\MagazineSubcategory::where('id',$value->subcategoryID)->count();
                                if($checksubcategory!=0){
                                    $subcategoryname = App\Models\MagazineSubcategory::where('id',$value->subcategoryID)->first()->name;
                                }else{
                                    $subcategoryname = '';
                                }
                            }
                            ?>
                                <tr id="<?php echo $value->id; ?>">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>{{ $i }}</td>
                                    <td><a
                                            href="{{ url('admin/magazinedata?key=' . base64_encode($value->id) . '&lang=' . base64_encode(1)) }}">{{ $value->heading }}</a>
                                    </td>
                                    <td><a
                                            href="{{ url('admin/magazinedata?key=' . base64_encode($value->id) . '&lang=' . base64_encode(2)) }}">{{ $value->heading_ar }}</a>
                                    </td>
                                    <!-- <td style="display: none">{{ $value->description }}</td>
                                    <td style="display: none">{{ $value->description_ar }}</td>
                                    <td style="display: none">{{ $value->videourl }}</td>
                                    <td style="display: none">{{ $value->postedBy }}</td>
                                    <td style="display: none">{{ $value->postedDate }}</td>
                                    <td style="display: none">{{ $value->end_date }}</td>
                                    <td style="display: none">{{ $value->tag_name }}</td>
                                    <td style="display: none">{{ $value->tag_name_ar }}</td> -->
                                    <td><?php
                                    $view = App\Models\Viewed::where('menu_type', 2)
                                        ->where('menuID', $value->id)
                                        ->count();
                                    if ($view == 0) {
                                        echo $value->totalview . "<a href='#' onclick='changetotal_view($value->id,$value->totalview)'><i class='fa fa-edit' style='font-size:12px'></i></a>";
                                    } else {
                                        echo $view + $value->totalview . "<a href='#' onclick='changetotal_view($value->id,$value->totalview)'><i class='fa fa-edit' style='font-size:12px'></i></a>";
                                    }
                                    //echo $view = App\Models\Viewed::where('menu_type', 2)->where('menuID', $value->id)->count();
                                    ?>
                                    </td>
                                    <td>{{ $value->log }}</td>
                                    <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input flexSwitchCheckCheckedhome" type="checkbox" role="switch" id="flexSwitchCheckCheckedhome" data-id="{{$value->id}}" <?php if ($value->home == 1) {
                                            echo 'checked';
                                        } ?>>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input flexSwitchCheckCheckedbanner" type="checkbox" role="switch" id="flexSwitchCheckCheckedbanner" data-id="{{$value->id}}" <?php if ($value->banner == 1) {
                                            echo 'checked';
                                        } ?>>
                                    </div>
                                </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox"
                                                role="switch" id="flexSwitchCheckChecked" data-id="{{ $value->id }}"
                                                <?php if ($value->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td><a href="{{ url('admin/addMagazine?key=' . base64_encode($value->id)) }}"><i
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
{{-- modal --}}
<div id="changetotal_view" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Total View</h4>
                <button type="button" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <input type="number" class="form-control" name="totalview" id="totalview">
            </div>
            <div class="modal-footer">
                <input type="hidden" name="totalviewid" id="totalviewid">
                <button type="button" class="btn btn-danger">Close</button>
                <button type="button" class="btn btn-primary changetotalview">Submit</button>
            </div>
        </div>

    </div>
</div>
{{-- modal --}}
<script>
    $(".changetotalview").click(function() {
        var totalview = $('#totalview').val();
        var totalviewid = $('#totalviewid').val();
        var token = "<?= csrf_token() ?>";
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/changetotalview') }}',
            data: {
                totalview: totalview,
                totalviewid: totalviewid,
                _token: token
            },
            success: function(data) {
                location.reload();
            }

        });

    })
</script>

<script>
    $(".delete").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/delete_magazine') }}',
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
            url: '{{ url('/admin/change_magazine_status') }}',
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
    $('.flexSwitchCheckCheckedhome').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        var checkboxp = $(this);
        var status = checkboxp.is(':checked') ? 1 : 0;
        if (!confirm("Are you sure you want to add this to the home screen?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkboxp.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/addhomemagazine') }}',
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
    $('.flexSwitchCheckCheckedbanner').click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        var checkboxp = $(this);
        var status = checkboxp.is(':checked') ? 1 : 0;
        if (!confirm("Are you sure you want to add this to the banner list?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkboxp.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/addbannermagazine') }}',
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
        $('#example,#exampler,#examplee,#examplev').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Export',
                exportOptions: {
                    columns: [1, 2, 3,4, 5, 6,7,8,9,10,11,12,13,14,15 ]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Magazine' + n;
                },
                title: 'Magazine'
            }],
        });
    });
</script>
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
            console.log(selectedData);
            updateOrder(selectedData);
        }
    });

    function updateOrder(aData) {
        var token = "<?php echo csrf_token(); ?>";
        $.ajax({
            url: "{{ url('updatemagzineOrder') }}",
            type: "POST",
            data: {
                allData: aData,
                _token: token
            },
            success: function(data) {
                console.log(data);
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
    function changetotal_view(th, value) {
        // alert(th);
        $('#totalviewid').val(th);
        $('#totalview').val(value);
        $('#changetotal_view').modal('show');
    }
</script>
<script>
    $('#maincategory').change(function() {
        var id = $('#maincategory').val();
        var token = "<?= csrf_token() ?>";
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/filter_maincategorydata') }}',
            data: {
                id: id,
                _token: token
            },
            success: function(data) {
                console.log(data)
                // if ($.fn.dataTable.isDataTable('#example')) {
                //     $('#example').DataTable().clear().destroy();
                // }
                // $('.row_position').html(data.magazine);
                $('#Subcategory').html(data.subcategory);
                // Destroy the existing DataTable


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
                //             columns: [1, 2, 3,4, 5, 6,7,8,9,10,11,12,13,14,15 ]
                //         },
                //         filename: function() {
                //             var d = new Date();
                //             var n = d.getTime();
                //             return 'Magazine' + n;
                //         },
                //         title: 'Magazine'
                //     }],

                // });
            }

        });


    })
</script>
<script>
    $('#Subcategory').change(function() {
        var id = $('#Subcategory').val();
        var token = "<?= csrf_token() ?>";
        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/filter_subcategorydata') }}',
            data: {
                id: id,
                _token: token
            },
            success: function(data) {
                console.log(data)
                if ($.fn.dataTable.isDataTable('#example')) {
                    $('#example').DataTable().clear().destroy();
                }
                $('.row_position').html(data.magazine);

                // Destroy the existing DataTable


                // Reinitialize the DataTable after the new data is loaded
                $('#example').DataTable({
                    // Add your DataTable options here
                    "paging": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "lengthMenu": [10, 25, 50, 100],  // Example page length options
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        text: 'Export',
                        exportOptions: {
                            columns: [1, 3, 4, 5, 6, ]
                        },
                        filename: function() {
                            var d = new Date();
                            var n = d.getTime();
                            return 'Magazine' + n;
                        },
                        title: 'Magazine'
                    }],

                });
            }

        });


    })
</script>


@include('admin.includes.footer')
