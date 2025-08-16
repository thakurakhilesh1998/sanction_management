@extends('layouts/gp')
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
        <h3>Gram Panchayat Home Page</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        @if(session('message'))
                <div class="alert alert-success">{{session('message')}}</div>
            @endif
    </div>
    <div class="card-body">
        <h4 class="p-2">View Sanctions for the Gram Panchayat
           
        </h4>
        @if($sanction->isEmpty())
        
            <div class="alert alert-info">No!New Sanction found.</div>
        
        @else 
            <div class="table-responsive">
                @php
                    $seen=[];   
                @endphp
                <table class="table table-bordered text-center" >
                    <thead>
                        <th>Sr. No.</th>
                        <th>District Name</th>
                        <th>Block Name</th>
                        <th>Gram Panchayat Name</th>
                        <th>Current Status</th>
                        <th>Delay if any</th>
                        <th>View</th>
                    </thead>
                    @php
                        $i=1;    
                    @endphp
                    @foreach ($sanction as $san)
                    @if(!in_array($san->gp,$seen))
                        @php 
                            $seen[]=$san->gp;
                        @endphp 
                        <tr>
                            <td>{{$i}}</td>
                            @php
                                $i++;
                            @endphp
                            <td>{{$san->district}}</td>
                            <td>{{$san->block}}</td>
                            <td>{{$san->gp}}</td>
                            <td>
                                @if(isset($san->progress))
                                    <strong>{{$san->progress->completion_percentage}}</strong>
                                @else
                                    <strong>Progress Not Reported yet.</strong>
                                @endif
                            </td>
                            @php 
                                if(isset($san->progress))
                                {
                                    $lastUpdateDate=\Carbon\Carbon::parse($san->progress->updated_at);
                                    $currentDate=\Carbon\Carbon::now();
                                    $days=$lastUpdateDate->diffInDays($currentDate);
                                }
                            @endphp
                            <td>
                               @if(isset($san->progress)) 
                                    @if($san->progress->completion_percentage==='Work Completed')
                                            <strong>Work Completed</strong>
                                    @else
                                            There are <strong>{{$days}}</strong> since {{$san->progress->completion_percentage}}
                                    @endif
                                @else
                                    <span>No Progress Added</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{url('gp/view-gpsan'.'/'.$san->gp.'/'.$san->block.'/'.$san->district)}}">View Sanction</a>
                            </td>
                        </tr>
                        @endif
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
            <form id="uploadForm" action="{{url('gp/upload-signed-sanction')}}" method="POST" enctype="multipart/form-data">
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
@endsection
