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

    @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
        @endif
    <div class="card-body">
        @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
        @endif
        <h4 class="p-2">Sanctions for the Gram Panchayat 
            {{-- {{$sanction[0]->progress->count()}}
            {{$sanction[0]->progress->completion_percentage}} --}}
            @if(!isset($sanction[0]->progress) || optional($sanction[0]->progress)->completion_percentage === '-1')
            {{-- @if(!isset($sanction[0]->progress) || $sanction[0]->progress->completion_percentage=='-1') --}}
                <a href="{{url("xen/add-progress".'/'.$sanction[0]->gp.'/'.$sanction[0]->block.'/'.$sanction[0]->district)}}" class="btn btn-primary btn-sm float-right">Add Progress</a>
            @else
                <a href="{{url("xen/update-progress".'/'.$sanction[0]->gp.'/'.$sanction[0]->block.'/'.$sanction[0]->district)}}" class="btn btn-primary btn-sm float-right">Update Progress</a> 
            @endif 
        </h4>
        @if($sanction->isEmpty())
        
            <div class="alert alert-info">No!New Sanction found which is not reported by you yet.</div>
        
        @else 
            <div class="table-responsive">
                <table class="table table-bordered text-center" >
                    <thead>
                        <th>Sr. No.</th>
                        <th>District Name</th>
                        <th>Block Name</th>
                        <th>Gram Panchayat Name</th>
                        <th>Total Amount Recived</th>
                        <th>Financial Year</th>
                        <th>Sanction Purpose</th>
                        <th>Sanction Head</th>
                        <th>Sanction Date</th>
                        <th>Sanction File</th>
                        <th>Upload UC if Sanction amount is fully utilized</th>
                        <th>Revert Sanction Back <b>If sanction wrongly sent</b></b></th>
                    </thead>
                    @php
                        $i=1;    
                    @endphp
                    @foreach ($sanction as $san)
                        <tr>
                            <td>{{$i}}</td>
                            @php
                                $i++;
                            @endphp
                            <td>{{$san->district}}</td>
                            <td>{{$san->block}}</td>
                            <td>{{$san->gp}}</td>
                            <td>{{$san->san_amount}}</td>
                            <td>{{$san->financial_year}}</td>
                            <td>{{$san->sanction_purpose}}</td>
                            <td>{{$san->sanction_head}}</td>
                            <td>{{$san->sanction_date}}</td>
                            <td>
                                <a href="{{ url('xen/view-sanction-file', ['filename' => $san->san_sign_pdf]) }}" target="_blank">View Sanction File</a>
                            </td>
                            <td>
                                @if($san->uc==null)
                                    @if($san->progress!==null)
                                        @if($san->progress->completion_percentage==='Work Started' || $san->progress->completion_percentage==='Partial Completion' || $san->progress->completion_percentage==='Work Completed')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadUc"  data-sanction-id="{{$san->id}}">Upload UC</button>
                                        @else
                                            <span>Work Not started yet.</span>
                                        @endif    
                                    @else
                                        <span>Work Not reported yet.</span>
                                    @endif
                                </td>
                                @else
                                    <a href="{{url('xen/viewUCgp',['filename'=>$san->uc])}}" target="_blank">View UC file</a>
                                 @endif
                            </td>
                            <td>
                                @if($san->uc==null)
                                    <button class="btn btn-danger revertBtn"
                                    data-sanction-id="{{ $san->id }}" data-gp="{{ $san->gp }}">Revert</button>
                                @else
                                    <span>UC is alread uploaded</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
       @endif
    </div>
</div>
{{-- Start of Modal --}}
<div class="modal fade" id="uploadUc" tabindex="-1" aria-labelledby="uploadUcLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadForm" action="{{route('uploadUC')}}" method="POST" enctype="multipart/form-data">
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

{{-- Start of Confirmation Modal --}}
<!-- Revert Confirmation Modal -->
<div class="modal fade" id="revertModal" tabindex="-1" aria-labelledby="revertModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="revertForm" method="POST" action="{{ route('xen.revertSanction') }}">
        @csrf
        <input type="hidden" name="sanction_id" id="revertSanctionId">
        <input type="hidden" name="gp" id="revertGp">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="revertModalLabel">Confirm Revert</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to <strong>revert this sanction</strong>?  
            <br>This action <span class="text-danger">cannot be undone</span>.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Revert</button>
          </div>
        </div>
    </form>
  </div>
</div>
{{-- End of Modal --}}
@endsection
@section('scripts')
<script>
    $(document).ready(function()
    {  
        $('.revertBtn').on('click', function () {
        let sanctionId = $(this).data('sanction-id');
        let gp = $(this).data('gp');
        
        $('#revertSanctionId').val(sanctionId);
        $('#revertGp').val(gp);

        $('#revertModal').modal('show');
        }); 
        $('button[data-bs-target="#uploadUc"]').on('click',function()
        {
            let sanctionId=$(this).data('sanction-id');
            $('#sanction_id').val(sanctionId);
        })
    });
</script>
@endsection