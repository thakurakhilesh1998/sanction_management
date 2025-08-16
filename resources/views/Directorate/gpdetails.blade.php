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
        <h3 class="h3 mb-3 text-gray-800">View GP Details
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">District Name: <strong>{{$gpDetails[0]->district}}</strong></div>
            <div class="col-md-4">Block Name: <strong>{{$gpDetails[0]->block}}</strong></div>
            <div class="col-md-4">Gram Panchayat: <strong>{{$gpDetails[0]->gp}}</strong></div>
            <div class="col-md-4">New Gram Panchayat?: <strong>{{$gpDetails[0]->newGP}}</strong></div>
        </div>
        <h3>View Progress</h3>
        <div>
            <table class="responsive table table-bordered text-center">
                <thead>
                    <th>Sr. No.</th>
                    <th>Financial Year</th>
                    <th>Sanction Date</th>
                    <th>Sanction Head</th>
                    <th>Sanction Amount</th>
                    <th>View UC</th>
                </thead>
                @php
                    $i=1;
                @endphp
                @foreach($gpDetails as $details)
                <tbody>
                    <td class="align-middle">{{$i++}}</td>
                    <td class="align-middle">{{$details->financial_year}}</td>
                    <td class="align-middle">{{$details->sanction_date}}</td>
                    <td class="align-middle">{{$details->sanction_head}}</td>
                    <td class="align-middle">{{$details->san_amount}}</td>
                    <td>
                        @if($details->uc==null)
                            <span>UC is not uploaded yet</span>
                        @else
                            <a href="{{route('viewUCDir',['filename'=>$details->uc])}}" target="_blank">View UC file</a>
                        @endif
                    </td>
                    {{-- @php 
                        $i++;
                        $progressExists=optional($details->progress)->isNotEmpty();
                    @endphp
                    <td class="align-middle">
                        @if($progressExists)
                            {{$details->progress[0]->updated_at}}
                        @endif
                    </td> --}}
                </tbody>
                @endforeach
            </table>
            <div>
                @if($progress!=null)
                    <h3>View GP Total Sanctions Progress: {{$progress->completion_percentage}}</h3>
                @else
                    <h3>No Progress Reported yet!</h3>
                @endif
            </div>

            
            @if($progress!=null)
            <div>Image(s) of the Work</div>
                @php 
                    $progressExists=optional($progress->image)->isNotEmpty();
                @endphp
                @if($progressExists)
                    @foreach($progress->image as $img)
                    
                    <a href="{{asset('uploads/images').'/'.$img->image_path}}" target="_blank">Image</a><br>
                    {{-- {{$img->image_path}} --}}

                    @endforeach
                    
                @endif
            @endif
        </div>  
    </div>
</div>
@endsection

