@extends('layouts/district')
@section('main')
<div class="card m-4">
    @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif
    @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
    @endif
    <div class="card-header">
        <h3 class="h3 mb-3 text-gray-800">View Sanction</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="font-size:0.9rem">
            <table class="table table-bordered text-center" id="datatable">
            <thead>
                    <th>Sanction Id</th>
                    <th>Block Name</th>
                    <th>Gram Panchayat Name</th>
                    <th>Sanction Date</th>
                    <th>Sanction Amount</th>
                    <th>Sanction Date</th>
                    <th>View Sanction File</th>
                    <th>Edit</th>
            </thead>
            <tbody>
                @foreach($sanction as $index=>$s)
                <tr>
                    <td>{{$s->id}}</td>
                    <td>{{$s->block}}</td>
                    <td>{{$s->gp}}</td>
                    <td>{{$s->sanction_date}}</td>
                    <td>{{addCommas($s->san_amount)}}</td>
                    <td>{{$s->sanction_date}}</td>
                    <td><a href="{{ route('viewSignedSanctionFileD', ['filename' => $s->san_sign_pdf]) }}" target="_blank">View Sanction File</a></td>
                    <th><a href="{{url('district/changeform').'/'.$s->id}}">Change Sanction</a></th>         
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    <div class="m-3">
        <a href="{{ url()->previous()}}" class="ml-3 btn btn-primary btn-sm float-right">Back</a>
    </div>
</div>

<!-- Start of Modal -->
<div class="modal fade" id="uploadsan" tabindex="-1" aria-labelledby="uploadsanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadForm" action="{{ route('uploadSanction') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Signed Sanction</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="sanction_id" id="sanction_id">
                    <div class="form-group">
                        <label for="file">Choose File</label>
                        <input type="file" class="form-control" id="file" name="file" required accept="application/pdf">
                        <small>Upload only PDF file and upto 1 MB size.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal -->

@endsection

@section('scripts')
<script>
    $(document).ready(function()
    {
        $('#datatable').on('click', 'button[data-bs-target="#uploadsan"]', function() {
        var sanctionId = $(this).data('sanction-id');
        $('#sanction_id').val(sanctionId);
    });
    });
</script>
@endsection

