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
        <h4>Update Sanction Progress</h4>
        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif
    </div>
    <div class="card-body">
        <div class="row my-2">
            <div class="col-md-4"><strong>District Name: {{$sanction[0]->district}}</strong></div>
            <div class="col-md-4"><strong>Block Name: {{$sanction[0]->block}}</strong></div>
            <div class="col-md-4"><strong>Gram Panchayat Name: {{$sanction[0]->gp}}</strong></div>
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
                            @if($images && $images->count() > 0 && $images->work_started_image)
                                <td> 
                                    <button class="btn btn-success updateImageBtn" 
                                    data-type="work_started_image" 
                                    data-url="{{ url('xen/update-progress-image/'.$progress->id) }}">
                                    Update Image
                                    </button>
                                </td>
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
                            @if($images && $images->count() > 0 && $images->work_partial_image)
                                <td> 
                                    <button class="btn btn-success updateImageBtn" 
                                    data-type="work_partial_image" 
                                    data-url="{{ url('xen/update-progress-image/'.$progress->id) }}">
                                    Update Image
                                    </button>
                                </td>
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
                            @if($images && $images->count() > 0 && $images->work_completed_image)
                                <td> 
                                    <button class="btn btn-success updateImageBtn" 
                                    data-type="work_completed_image" 
                                    data-url="{{ url('xen/update-progress-image/'.$progress->id) }}">
                                    Update Image
                                    </button>
                                </td>
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

{{-- Start of Image Update Modal --}}

<!-- Modal -->
<div class="modal fade" id="updateImageModal" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="updateImageForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateImageModalLabel">Update Progress Image</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="image_type" id="imageType"> <!-- work_started_image / work_partial_image / work_completed_image -->
              <div class="mb-3">
                  <label for="newImage" class="form-label">Choose New Image</label>
                  <input type="file" class="form-control" name="new_image" id="newImage" accept="image/*" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update Image</button>
          </div>
        </div>
    </form>
  </div>
</div>


@endsection

@section('scripts')

<script>
$(document).ready(function () {
    $('.updateImageBtn').click(function () {
        let imageType = $(this).data('type');
        let url = $(this).data('url');

        // Set form action and hidden field
        $('#updateImageForm').attr('action', url);
        $('#imageType').val(imageType);

        // Show modal
        $('#updateImageModal').modal('show');
    });
});
</script>


<script src="{{asset('assets/js/progress_update_validation.js')}}"></script>
@endsection
