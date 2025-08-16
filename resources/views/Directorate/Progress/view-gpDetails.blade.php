@extends('layouts/dir')
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
        <h3 class="h3 mb-3 text-gray-800">View Progress of Sanctions for Gram Panchayat <strong>{{$sanctionsForGP[0]->gp}}</strong>
        ,Development Block <strong>{{$sanctionsForGP[0]->block}}</strong>   
        </h3>
    </div>
    <div class="card-body">
        <div class="border p-3">
            <div class="">
                <h4 class="red-line p-2">View Sanctions for Gram Panchayat</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Sanction Amount</th>
                            <th>Sanction Date</th>
                            <th>Sanction Head</th>
                            <th>Sanction Purpose</th>
                            <th>View UC(if uploaded)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sanctionsForGP as $index=>$detail)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$detail->san_amount}}</td>
                            <td>{{$detail->sanction_date}}</td>
                            <td>{{$detail->sanction_head}}</td>
                            <td>{{$detail->sanction_purpose}}</td>
                            @if($detail->uc!==null)
                                <td><a href="{{url('dir/viewUCgp',['filename'=>$detail->uc])}}" target="_blank">View UC</a></td>
                            @else
                                <td>UC not uploaded</td>
                            @endif
                        </tr>    
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    {{-- View Progress of Sanction --}}
    <div class="border p-3">
        <h4 class="red-line p-2">Current Status of Work</h4>
        <div class="row mt-4">
            <div class="col-md-3">
                  <div class="card-custom">
                    <img src="{{asset('assets/img/pending.png')}}" alt="" alt="Pending with">
                    <h5>Work is Executing</h5>
                    <p>
                        @if($sanctionsForGP[0]->status=='xen')
                            Executive Engineer
                        @elseif($sanctionsForGP[0]->status=='gp')
                            Gram Panchayat
                        @else   
                            No Progress Added
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom">
                    <img src="{{asset('assets/img/construction.png')}}" alt="status of work">
                    <h5>Status of Work</h5>
                    <p>{{$completion}}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom">
                    <img src="{{asset('assets/img/delay.png')}}" alt="Delay">
                    <h5>Delay</h5>
                    <p>
                        @if($days===0)
                            Not reported
                        @elseif($completion==='Work Completed')
                            Work Completed
                        @else
                            {{$days}}&nbsp;days
                        @endif
                    </p>
                </div>
            </div>
                 <div class="col-md-3">
                <div class="card-custom">
                    <img src="{{asset('assets/img/remarks.png')}}" alt="remarks">
                    <h5>Remarks</h5>
                    <p>
                        @if($sanctionsForGP[0]->progress!=null)
                            @if($sanctionsForGP[0]->progress->remarks!=null)
                                {{$sanctionsForGP[0]->progress->remarks}}
                            @else
                                No Remarks
                            @endif
                        @else
                            No Remarks
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="border p-3 mt-3">
        <h4 class="red-line p-2">Images of the Work</h4>
            @if($completion==='Work Started' || 'Partial Completion' || 'Work Completed')  
                @if($completion=='Work Started')
                    @if(!empty($sanctionsForGP[0]->progress->image->work_started_image))
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <img src="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_started_image)}}" alt="Work Started Image" class="card-img-top img-fluid fixed-size">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Work Started</h5>
                                    <a href="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_started_image)}}" target="_blank">View Work Started Image</a>            
                                </div>
                            </div>
                        </div> 
                    </div>
                    @endif
                @endif
                @if($completion=='Partial Completion')
                    @if(!empty($sanctionsForGP[0]->progress->image->work_started_image) && !empty($sanctionsForGP[0]->progress->image->work_partial_image))
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <img src="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_started_image)}}" alt="Work Started Image" class="card-img-top img-fluid fixed-size">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Work Started</h5>
                                    <a href="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_started_image)}}" target="_blank">View Work Started Image</a>            
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <img src="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_partial_image)}}" alt="Work Started Image" class="card-img-top img-fluid fixed-size">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Work Partial Completed</h5>
                                    <a href="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_partial_image)}}" target="_blank">View Work Started Image</a>            
                                </div>
                            </div>
                        </div>
                
                    </div>    
                    @endif
                @endif
                @if($completion=='Work Completed')
                    @if(!empty($sanctionsForGP[0]->progress->image->work_started_image) && !empty($sanctionsForGP[0]->progress->image->work_partial_image) && !empty($sanctionsForGP[0]->progress->image->work_completed_image))
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                    <img src="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_started_image)}}" alt="Work Started Image" class="card-img-top img-fluid fixed-size">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Work Started</h5>
                                        <a href="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_started_image)}}" target="_blank" class="img-fluid">View Work Started Image</a>            
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                    <img src="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_partial_image)}}" alt="Work Started Image" class="card-img-top img-fluid fixed-size">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Work Partial Completed</h5>
                                        <a href="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_partial_image)}}" target="_blank">View Work Started Image</a>            
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                    <img src="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_completed_image)}}" alt="Work Started Image" class="card-img-top img-fluid fixed-size">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Work Completed</h5>
                                        <a href="{{asset('uploads/images'.'/'.$sanctionsForGP[0]->progress->image->work_completed_image)}}" target="_blank">View Work Started Image</a>            
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
    </div>
    </div>
    <div class="m-3">
        <button  id='back' class="ml-3 btn btn-primary btn-sm float-right">Back</button>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.getElementById('back').addEventListener('click', function() {
        window.history.back();
    });
</script>
@endsection