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
        <h4 class="p-2">Sanctions for the Development Block
            {{-- {{$sanction[0]->progress_rd->count()}}
            {{$sanction[0]->progress_rd->completion_percentage}} --}}
            @if(!isset($rdSanction[0]->progress_rd) || optional($rdSanction[0]->progress_rd)->completion_percentage === '-1')
                <a href="{{url("xen/add-progress-rd".'/'.$rdSanction[0]->block.'/'.$rdSanction[0]->district.'/'.$rdSanction[0]->work)}}" class="btn btn-primary btn-sm float-right">Add Progress</a>
            @elseif(!isset($rdSanction[0]->progress_rd) || optional($rdSanction[0]->progress_rd)->completion_percentage === 'Work Completed')    
                <a href="{{url("xen/update-progress-rd".'/'.$rdSanction[0]->block.'/'.$rdSanction[0]->district.'/'.$rdSanction[0]->work)}}" class="btn btn-primary btn-sm float-right">View Progress Added</a> 
            @else
                <a href="{{url("xen/update-progress-rd".'/'.$rdSanction[0]->block.'/'.$rdSanction[0]->district.'/'.$rdSanction[0]->work)}}" class="btn btn-primary btn-sm float-right">Update Progress</a> 
            @endif  
        </h4>
        @if($rdSanction->isEmpty())
        
            <div class="alert alert-info">No! Sanction found.</div>
        
        @else 
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="datatable">
                    <thead>
                        <th>Sr. No.</th>
                        <th>District Name</th>
                        <th>Block Name</th>
                        <th>Total Amount Recived</th>
                        <th>Financial Year</th>
                        <th>Sanction Purpose</th>
                        <th>Sanction Head</th>
                        <th>Sanction Date</th>
                        <th>Sanction File</th>
                        <th>Upload UC if Sanction amount is fully utilized</th>
                    </thead>
                    @php
                        $i=1;    
                    @endphp
                    @foreach ($rdSanction as $san)
                        <tr>
                            <td>{{$i}}</td>
                            @php
                                $i++;
                            @endphp
                            <td>{{$san->district}}</td>
                            <td>{{$san->block}}</td>
                            <td>{{$san->san_amount}}</td>
                            <td>{{$san->financial_year}}</td>
                            <td>{{$san->work}}</td>
                            <td>{{$san->sanction_head}}</td>
                            <td>{{$san->sanction_date}}</td>
                            <td>
                                <a href="{{ url('xen/view-sanction-file', ['filename' => $san->san_pdf]) }}" target="_blank">View Sanction File</a>
                            </td>
                            <td>
                                @if($san->uc==null)
                                    @if($san->progress_rd!==null)
                                        @if($san->progress_rd->completion_percentage==='Work Started' || $san->progress_rd->completion_percentage==='Partial Completion' || $san->progress_rd->completion_percentage==='Work Completed')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadUc"  data-sanction-id="{{$san->id}}">Upload UC</button>
                                        @else
                                            <span>Work Not started yet.</span>
                                        @endif    
                                    @else
                                        <span>Work Not reported yet.</span>
                                    @endif
                                </td>
                                @else
                                    <a href="{{url('xen/viewUCRD',['filename'=>$san->uc])}}" target="_blank">View UC file</a>
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
            <form id="uploadForm" action="{{url('xen/upload-signed-sanction-rd')}}" method="POST" enctype="multipart/form-data">
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
<script>
    $(document).ready(function()
    {   
        $('button[data-bs-target="#uploadUc"]').on('click',function()
    {
        let sanctionId=$(this).data('sanction-id');
        $('#sanction_id').val(sanctionId);
    })
    })
</script>
@endsection