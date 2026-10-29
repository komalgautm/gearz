@include('admin.includes.header')

<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <!-- <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button"
                                title="Back"><i class="fa fa-arrow-left"></i></a> {{ $title }}</h4>

                    </div>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv" style="display: none;">
                    <button type="submit" class="white_btn3" id="">Import Branches</button>
                    <a href="{{ url('admin/addBranch?key=' . $id) }}" class="white_btn3">Create</a>
                </div> -->
                <div class="d-flex bd-highlight mb-3">
                    <div class="me-auto p-2 bd-highlight">
                        <h4 class="f_s_30 f_w_700 text_white"><a href="javascript:history.back()" class="back_button" title="Back"><i class="fa fa-arrow-left"></i></a> {{ $title }}</h4>
                    </div>
                    <div class="p-2 bd-highlight">
                        <a href="{{ url('public/sample/ImportBranchesSampleSheet.csv') }}" title="Download Sample File"><button type="button" class="white_btn3">
                        Sample
                        </button></a>&emsp;
                        <button type="button" class="white_btn3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Import
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <a href="{{ url('admin/addBranch?key=' . $id) }}" class="white_btn3">Create</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_20 ">
            <!-- <h2 class="card-title text-center p-5">Brands</h2> -->
            <div class="white_card_body text-center">
                @if (Session::has('message'))
                <div class="text-end mb-3">
                    <span class="alert alert-success">
                        <span style="font-weight: 600;"> Success !! </span>
                        {{ Session::get('message') }}
                    </span>
                </div>
                @endif
                @error('csv_file')
                    <div class="text-end mb-3">
                        <span class="alert alert-danger">
                            <span style="font-weight: 600;"> Failed !! </span>
                            {{ $message }}
                        </span>
                    </div>
                @enderror
                <div class="QA_table table-responsive pb-3">
                    <table class="table p-0 dataTable no-footer" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name (English)</th>
                                <th scope="col">Name (Arabic)</th>
                                <th scope="col">Service Type</th>
                                <th scope="col">Location</th>
                                <th scope="col" style="display: none">Contact</th>
                                <th scope="col" style="display: none">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data as $value)
                                <?php
                                $cityDetails = App\Models\City::find($value->city);
                                $service = App\Models\ServiceBranch::where('branchId', $value->id)->get();
                                $servicename = '';
                                foreach ($service as $serv) {
                                    $sername = App\Models\ServiceType::where('id', $serv->serviceID)->first()->name;
                                    $servicename = $servicename . ',' . $sername;
                                    # code...
                                }

                                ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->name_ar }}</td>
                                    <td><?= ltrim($servicename, ',') ?></td>
                                    <td>{{ $cityDetails->name ?? '' }}</td>
                                    <td style="display: none">{{ $value->contact }}</td>
                                    <td style="display: none">{{ $value->email }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox"
                                                role="switch" id="flexSwitchCheckChecked" data-id="{{ $value->id }}"
                                                <?php if ($value->status == 1) {
                                                    echo 'checked';
                                                } ?>>
                                        </div>
                                    </td>
                                    <td class="w-25">
                                        <a href="{{ url('admin/addBranch?key=' . $id . '&id=' . base64_encode($value->id)  . '&view=' . base64_encode($value->id)) }}"><i class="fa fa-eye"></i></a> | 
                                        <a href="{{ url('admin/addBranch?key=' . $id . '&id=' . base64_encode($value->id)) }}"><i class="fa fa-edit"></i></a> | 
                                        <a class="delete" data-id="{{ $value->id }}"><i class="fa fa-trash"></i></a>
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
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Import Branches</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="{{ url('/admin/import-branches') }}" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            @csrf
            <label for="csv_file" class="col-form-label">Upload Excel</label>
            <input type="hidden" name="agentid" value="{{$id}}">
            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
            <span style="color: #dc3545;"><b>Note : </b>Please upload only .csv extension excel file.</span><br>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" class="btn btn-primary">Import</button>
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
                url: '{{ url('/admin/delete_branch') }}',
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
            url: '{{ url('/admin/change_branch_status') }}',
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
                    columns: [0,1,2, 3, 4, 5, 6]
                },
                filename: function() {
                    var d = new Date();
                    var n = d.getTime();
                    return 'Branch' + n;
                },
                title: 'Branch'
            }],
        });
    });
</script>


@include('admin.includes.footer')
