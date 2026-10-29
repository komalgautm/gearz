@include('admin.includes.header')
<style>
    .markendDelete {
        text-align: justify;
        position: relative;
        left: 8%;
        top: 52px;
        z-index: 9;
        width: 170px;
    }

    button {
        width: 150px;
        height: 50px;
        cursor: pointer;
        display: flex;
        align-items: center;
        background: red;
        border: none;
        border-radius: 5px;
        box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.15);
        background: #e62222;
    }

    button,
    button span {
        transition: 200ms;
    }

    button .text {
        transform: translateX(35px);
        color: white;
        font-weight: bold;
    }

    button .icon {
        position: absolute;
        border-left: 1px solid #c41b1b;
        transform: translateX(110px);
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    button svg {
        width: 15px;
        fill: #eee;
    }

    button:hover {
        background: #ff3636;
    }

    button:hover .text {
        color: transparent;
    }

    button:hover .icon {
        width: 150px;
        border-left: none;
        transform: translateX(0);
    }

    button:focus {
        outline: none;
    }

    button:active .icon svg {
        transform: scale(0.8);
    }
    .deletemsg{
        position: absolute;
        left: 163px;
        top: 0px;
        width: 170px;
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
                    <a href="{{ url('admin/sendadvice') }}" class="white_btn3">Send Advice</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">

            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                <div class="QA_table table-responsive pb-3">
                    <div class="markendDelete">
                        <div class="jobsection">
                            {{-- <input type="button" class="profileDrop" id="getCheckedValues" value="Delete"> --}}
                            <button class="noselect" id="getCheckedValues"><span class="text">Delete all</span><span
                                    class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z">
                                        </path>
                                    </svg></span></button>
                            <span class="alert text-danger text-center deletemsg"></span>
                        </div>
                    </div>
                    <table class="table p-0" id="example">
                        <thead>
                            <tr>
                                <th class="no-sort"><input type="checkbox" id="selectAll">
                                    <label for="selectAll"> </label>
                                </th>
                                <th scope="col"></th>
                                <th scope="col">#</th>
                                <th scope="col">Advice Type</th>
                                <th scope="col">Title (English)</th>
                                <th scope="col">Title (Arabic)</th>
                                <th scope="col" style="display: none">Message (English)</th>
                                <th scope="col" style="display: none">Message (Arabic)</th>
                                <th scope="col" style="display: none">Sponser Name (English)</th>
                                <th scope="col" style="display: none">Sponser Name (Arabic)</th>
                                <th scope="col">Expire Date</th>
                                <th scope="col">Visited</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                                <tr id="<?php echo $value->id; ?>">
                                    <td><input type="checkbox" class="checkproductcategory"
                                            name="checkproductcategory{{ $i }}"
                                            id="checkproductcategory{{ $i }}" value="{{ $value->id }}">
                                    </td>
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>{{ $i }}</td>
                                    <td><?php if($value->UserID==1){ echo "General Advice"; }else{ echo"Sponser Advice"; } ?></td>
                                    <td><a
                                            href="{{ url('admin/advicedetail?key=' . base64_encode($value->id) . '&lang=' . base64_encode(1)) }}">{{ $value->title }}</a>
                                    </td>
                                    <td><a
                                            href="{{ url('admin/advicedetail?key=' . base64_encode($value->id) . '&lang=' . base64_encode(2)) }}">{{ $value->title_ar }}</a>
                                    </td>
                                    <td style="display: none">{{ $value->message }}</td>
                                    <td style="display: none">{{ $value->message_ar }}</td>
                                    <td style="display: none">{{ $value->sponser_name }}</td>
                                    <td style="display: none">{{ $value->sponser_name_ar }}</td>
                                    <td>{{ $value->expire_date }}</td>
                                    <td><?php echo $view = App\Models\Viewed::where('menu_type', 5)
                                        ->where('menuID', $value->id)
                                        ->count(); ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox"
                                                role="switch" id="flexSwitchCheckChecked"
                                                data-id="{{ $value->id }}" <?php if ($value->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td><a href="{{url('admin/sendadvice?key='.base64_encode($value->id))}}"><i class="fa fa-edit"></i></a> |<a class="delete" data-id="{{ $value->id }}"><i class="fa fa-trash"></i></a>
                                    </td>
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
    $(".delete").click(function() {
        var id = $(this).data('id');
        var token = "<?= csrf_token() ?>";
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/admin/delete_advice') }}',
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
    $(document).ready(function() {
        $('#example').DataTable({
            columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                } // Disable sorting for the "no-sort" class
            ],
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Export',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6,7,8,9,10,11]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Advice' + n;
                },
                title: 'Advice'
            }],
        });
    });
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
                url: '{{ url('/admin/change_advice_status') }}',
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
            url: "{{ url('updateadviceOrder') }}",
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
    document.getElementById('getCheckedValues').addEventListener('click', function() {
        const checkedValues = Array.from(document.querySelectorAll('.checkproductcategory:checked'))
            .map(checkbox => checkbox.value);
        //alert("Checked values: " + checkedValues.join(", "));
        var id = checkedValues.join(", ");
        var token = "<?= csrf_token() ?>";
        if (id == "") {
            $('.deletemsg').css("display","block");
            $('.deletemsg').text("Please select advice");
            return false;
        } else {
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url('/admin/delete_advice') }}',
                    data: {
                        id: id,
                        _token: token
                    },
                    success: function(data) {
                        console.log(data);

                        location.reload();
                    }

                });
            }
        }
    });
</script>
<script>
    const checkAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.checkproductcategory');

    checkAll.addEventListener('change', function() {
        $('.deletemsg').text("");
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) { // Only set checked state for enabled checkboxes
                checkbox.checked = this.checked;
            }
        });
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            $('.deletemsg').text("");
            if (!this.checked) {
                checkAll.checked = false; // Uncheck "Check All" if any checkbox is unchecked
            } else {
                // If all enabled checkboxes are checked, check the "Check All" checkbox
                const allChecked = Array.from(checkboxes).every(cb => cb.checked || cb.disabled);
                checkAll.checked = allChecked;
            }
        });
    });
</script>


@include('admin.includes.footer')
