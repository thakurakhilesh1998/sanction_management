@extends('layouts/xen')
@section('main')
<div class="card m-4">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    </div>
    @endif
    <div class="card-header">
        <div id="progressData" 
            data-completion-status="{{ $progress->completion_percentage }}" 
            data-update-url="{{ url('xen/change-progress/'.$progress->id) }}">
        </div>
        <h4>Update Sanction Progress- RD Sanctions</h4>
        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif
    </div>
    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-4"><strong>District Name: {{$sanction[0]->district}}</strong></div>
            <div class="col-md-4"><strong>Block Name: {{$sanction[0]->block}}</strong></div>
            <div class="col-md-4"><strong>Work Name: {{$sanction[0]->work}}</strong></div>
        </div> 
        <div class="row my-2">
            @php 
            $sanAmount = 0;      
            @endphp
            @foreach($sanction as $san)
                @php   
                    $sanAmount += $san->san_amount;
                @endphp
            @endforeach
            <div class="col-md-4"><strong>Total Sanction Amount: ₹{{$sanAmount}}</strong></div>
            <div class="col-md-4"><strong>Current Status of Work: {{$progress->completion_percentage}}</strong></div>
        </div>
        <br>
        <div class="row my-3">
            <div class="col-md-3"><strong>Update Current Status of Work:</strong></div>
            <div class="col-md-4">
                <select id="completion_percentage" class="form-control" name="completion_status">
                    <option value="-1">--Select Status--</option>
                    <option value="Tender Floated">Tender Floated</option>
                    <option value="Tender Awarded">Tender Awarded</option>
                    <option value="Work Started">Work Started</option>
                    <option value="Partial Completion">Partial Completion</option>
                    <option value="Work Completed">Work Completed</option>
                    <option value="Tender Cancelled">Tender Cancelled</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="Remarks" class="form-label">Remarks</label>
                <textarea type="text" name="remarks" id="remarks" class="form-control"></textarea>
            </div>
        </div>

        {{-- Hidden Image upload field --}}
        <div class="my-3 row" id="imageUploadRow" style="display: none;">
            <div class="col-md-3"><strong>Upload Image:</strong></div>
            <div class="col-md-4">
                <input type="file" id="statusImage" name="status_image" class="form-control" accept="image/*" required>
            </div>
        </div>
        <div class="col-md-4">
            <a href="javascript:void(0);" id="updateStatusBtn" class="btn btn-primary">Update</a>
        </div>
        <div class="row my-4">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <th>Sr. No.</th>
                        <th>Status of Work</th>
                        <th>Status of Progress Image</th>
                        <th>Update Progress Image</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Work Started Image</td>
                            @if($images && $images->count() > 0 && $images->work_started_image)
                                <td><a href="{{ url('uploads/images/'.$images->work_started_image) }}" target="_blank">View Image</a></td>
                            @else
                                <td>No image uploaded yet!</td>
                            @endif
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Partial Completion Image</td>
                            @if($images && $images->count() > 0 && $images->work_partial_image)
                                <td><a href="{{ url('uploads/images/'.$images->work_partial_image) }}" target="_blank">View Image</a></td>
                            @else
                                <td>No image uploaded yet!</td>
                            @endif
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Work Completed Image</td>
                            @if($images && $images->count() > 0 && $images->work_completed_image)
                                <td><a href="{{ url('uploads/images/'.$images->work_completed_image) }}" target="_blank">View Image</a></td>
                            @else
                                <td>No image uploaded yet!</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/progress_update_validation.js')}}"></script>
@endsection
