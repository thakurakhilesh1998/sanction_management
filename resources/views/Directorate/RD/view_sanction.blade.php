@extends('layouts/dir')
@section('main')
<div class="card m-4">
    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <div class="card-header">
        <h3 class="h3 mb-3 text-gray-800">View Sanction
            <a href="{{ url('dir/rd-add') }}" class="btn btn-primary btn-sm float-right">Add Sanction</a>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="font-size:0.9rem">
            <table class="table table-bordered text-center" id="datatable">
                <thead>
                    <th>Sr. No.</th>
                    <th>District Name</th>
                    <th>Block Name</th>
                    <th>Financial Year</th>
                    <th>Sanction Amount</th>
                    <th>Sanction Date</th>
                    <th>Work Name</th>
                    <th>Upload Sanction PDF</th>
                    <th>Edit</th>
                    
                </thead>
                <tbody>
                    @foreach($rdsanctions as $index=>$s)
                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{ $s->district }}</td>
                        <td>{{ $s->block }}</td>
                        <td>{{ $s->financial_year}}</td>
                        <td>{{ addCommas($s->san_amount) }}</td>
                        <td>{{ $s->sanction_date }}</td>
                        <td>{{$s->work}}</td>
                        <td>
                            @if($s->san_pdf!=null)
                                <a href="{{route('viewSignedSanctionFileRD',['filename'=>$s->san_pdf])}}" target="_blank">View Sanction</a>
                            @else
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadsan" data-id="{{$s->id}}">Upload Sanction</button>
                            @endif
                        </td>
                        <td><a href="#">Edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="m-3">
        <a href="{{ url()->previous() }}" class="ml-3 btn btn-primary btn-sm float-right">Back</a>
    </div>
</div>

<!-- Start of Modal -->
<div class="modal fade" id="uploadsan" tabindex="-1" aria-labelledby="uploadsanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadForm" action="{{ route('uploadSanctionRd') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Signed Sanction</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
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
   $(document).ready(function() {
    // Use event delegation to attach the click event to buttons within the datatable
    $('#datatable').on('click', 'button[data-bs-target="#uploadsan"]', function() {
        var id = $(this).data('id');
        $('#id').val(id);
    });
});
</script>
@endsection
