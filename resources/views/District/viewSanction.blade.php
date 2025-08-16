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
        <h3 class="h3 mb-3 text-gray-800">View Sanction
            <a href="{{url('/district/add-sanction')}}" class="btn btn-primary btn-sm float-right">Add Sanction</a>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="font-size:0.9rem">
            <table class="table table-bordered text-center" id="datatable">
            <thead>
                    <th>Sr. No.</th>
                    <th>District Name</th>
                    <th>Block Name</th>
                    <th>Gram Panchayat Name</th>
                    <th>Financial Year</th>
                    <th>Sanction Amount</th>
                    <th>Sanction Date</th>
                    <th>Status</th>
                    <th>View Generated Sanction</th>
                    <th>Upload Signed Sanction</th>
                    <th>Edit</th>
            </thead>
            <tbody>
                @foreach($sanction as $index=>$s)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$s->district}}</td>
                    <td>{{$s->block}}</td>
                    <td>{{$s->gp}}</td>
                    <td>{{$s->financial_year}}</td>
                    <td>{{addCommas($s->san_amount)}}</td>
                    <td>{{$s->sanction_date}}</td>
                    <td>
                        @php
                            $progressExists = isset($s->progress);  
                            // optional($s->progress)->isNotEmpty();
                            $isFreeze = false;
                            if ($progressExists) {
                                if ($s->progress->isFreeze == 'yes') {
                                    $isFreeze = true;
                                }
                            }
                        @endphp

                        @if($progressExists)
                            @if($isFreeze)
                                <span>Completed</span>
                            @elseif($s->progress->p_isComplete == 'yes')
                                <span>Completed but not freezed by District</span>
                            @else
                                <span><b>{{ $s->progress->completion_percentage }}</b></span>
                            @endif
                        @else
                            <span>Not Reported</span>
                        @endif
                    </td>
                    <td>
                        @if($s->san_pdf != null)
                            <a href="{{ route('viewDistSanctionFile', ['filename' => $s->san_pdf]) }}" target="_blank">View Sanction File</a>
                        @endif
                    </td>
                    <td>
                       @if($s->san_sign_pdf!=null)
                            <a href="{{ route('viewSignedSanctionPdf', ['filename' => $s->san_sign_pdf]) }}" target="_blank">View Sanction File</a>
                       @else
                       <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadsan" data-sanction-id="{{$s->id}}">
                           Upload Sanction
                       </button>
                       @endif
                    </td>
                    <td>
                        @if($isFreeze)
                            <span>Freezed</span>
                        @elseif($s->san_sign_pdf!=null)
                            <b><span>Freezed & </span></b><br>
                            <b><span>Forwarded to {{$s->status}}</span></b>
                        @else   
                            <a href="{{ url('district/editSanction/' . $s->id) }}" class="btn btn-info text-white text-bold">Edit</a>
                        @endif
                    </td>
                    {{-- <td><a href="{{ url('dir/gpDetails/' . $s->gp.'/'.$s->block.'/'.$s->district) }}">View</a></td> --}}
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

