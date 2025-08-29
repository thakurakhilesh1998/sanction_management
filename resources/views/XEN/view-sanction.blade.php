@extends('layouts/xen')

@section('main')
<div class="card m-4">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <div class="card-header">
        <h3>Executive Engineer Home Page</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
    </div>

    <div class="card-body">
        <h4 class="p-2">View Sanctions for the Executive Engineer</h4>

        @if($sanction->isEmpty())
            <div class="alert alert-info">No new sanction found.</div>
        @else 
            <div class="table-responsive">
                @php
                    $seen = [];   
                    $i = 1;
                @endphp
                <table class="table table-bordered text-center" id="datatable">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>District Name</th>
                            <th>Block Name</th>
                            <th>Gram Panchayat Name</th>
                            <th>Current Status</th>
                            <th>Delay if any</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sanction as $san)
                            @php
                                $uniqueKey = $san->gp . '_' . $san->block . '_' . $san->district;
                            @endphp

                            @if(!in_array($uniqueKey, $seen))
                                @php $seen[] = $uniqueKey; @endphp

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $san->district }}</td>
                                    <td>{{ $san->block }}</td>
                                    <td>{{ $san->gp }}</td>
                                    <td>
                                        @if(isset($san->progress))
                                            <strong>{{ $san->progress->completion_percentage }}</strong>
                                        @else
                                            <strong>Progress Not Reported yet.</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($san->progress)) 
                                            @php
                                                $lastUpdateDate = \Carbon\Carbon::parse($san->progress->updated_at);
                                                $currentDate = \Carbon\Carbon::now();
                                                $days = $lastUpdateDate->diffInDays($currentDate);
                                            @endphp

                                            @if($san->progress->completion_percentage === 'Work Completed')
                                                <strong>Work Completed</strong>
                                            @else
                                                There are <strong>{{ $days }}</strong> days since {{ $san->progress->completion_percentage }}
                                            @endif
                                        @else
                                            <span>No Progress Added</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('xen/view-gpsan/' . urlencode($san->gp) . '/' . urlencode($san->block) . '/' . urlencode($san->district)) }}">
                                            View Sanction
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Start of Modal --}}
<div class="modal fade" id="uploadUc" tabindex="-1" aria-labelledby="uploadUcLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadForm" action="{{ route('uploadUC') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload UC</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="sanction_id" id="sanction_id">
                    <div class="form-group">
                        <label for="file">Choose File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
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
{{-- End of Modal --}}
@endsection

@section('scripts')
<!-- Add any page-specific scripts here if needed -->
@endsection
