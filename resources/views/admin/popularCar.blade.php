@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> Popular Cars</h4>

                    </div>
                    @if (Session::has('message'))
                        <p class="alert alert-success"><span style="font-weight: 600;"> Success !!
                            </span>{{ Session::get('message') }}</p>
                    @endif
                    <a href="{{ url('admin/brands') }}" class="white_btn3">Add Popular Car</a>
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
                                <th scope="col"></th>
                                <th scope="col">#</th>
                                <th scope="col">Make</th>
                                <th scope="col">Model</th>
                                <th scope="col">Trim</th>
                                <th scope="col">Year</th>
                                <th scope="col">Popular</th>
                            </tr>
                        </thead>
                        <tbody class="row_position">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                                @php
                                $data = App\Models\PopularCar::where(['brand_id' => $value['brand_id'], 'make' => $value['make'], 'model' => $value['model'], 'trim' => $value['trim'], 'year' => $value['year']])->first();
                                if($data){ $popular = 1; }else{ $popular = 0; }
                                @endphp
                                
                                <tr id="<?php echo $value->id; ?>">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>{{ $i }}</td> 
                                    <td>{{ $value['make'] }}</td>
                                    <td>{{ $value['model'] }}</td>
                                    <td>{{ $value['trim'] }}</td>
                                    <td>{{ $value['year'] }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedpopular"
                                                type="checkbox" role="switch" id="flexSwitchCheckCheckedpopular"
                                                data-brandid="{{ $value['brand_id'] }}" data-make="{{ $value['make'] }}" data-model="{{ $value['model'] }}" data-trim="{{ $value['trim'] }}" data-year="{{ $value['year'] }}" <?php if ($popular == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
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
    $('.flexSwitchCheckCheckedpopular').click(function() {
        var brand_id = $(this).data('brandid');
        var make = $(this).data('make');
        var model = $(this).data('model');
        var trim = $(this).data('trim');
        var year = $(this).data('year');
        var token = "<?= csrf_token() ?>";
        var checkboxp = $(this);
        var status = checkboxp.is(':checked') ? 1 : 0;
        var action = status == 1 ? 'add this to' : 'remove this from';
        if (!confirm("Are you sure you want to "+ action +" the popular list?")) {
            // Revert the toggle state if the user cancels the confirmation
            checkboxp.prop('checked', !status);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ url('/admin/add_popular_car') }}',
            data: {
                _token: token,
                brand_id: brand_id,
                // status: status,
                make: make,
                model: model,
                trim: trim,
                year: year,
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
            console.log(selectedData);
            updateOrder(selectedData);
        }
    });

    function updateOrder(aData) {
        var token = "<?php echo csrf_token(); ?>";
        $.ajax({
            url: "{{ url('updateCarOrder') }}",
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
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Export',
                exportOptions: {
                    columns: [1, 2]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Popular car' + n;
                },
                title: 'Popular car'
            }],
        });
    });
</script>

@include('admin.includes.footer')
