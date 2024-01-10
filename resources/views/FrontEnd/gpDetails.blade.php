@extends('layouts/app')
@section('content')
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
                    <th>Update Detail</th>
                    <th>Fully Utilized?</th>    
                    <th>View Images</th>
                </thead>
                @php
                    $i=1;
                @endphp
                @foreach($gpDetails as $details)
                <tbody>
                    <td class="align-middle">{{$i}}</td>
                    <td class="align-middle">{{$details->financial_year}}</td>
                    <td class="align-middle">{{$details->sanction_date}}</td>
                    <td class="align-middle">{{$details->sanction_head}}</td>
                    <td class="align-middle">{{$details->san_amount}}</td>
                    @php 
                        $i++;
                        $progressExists=optional($details->progress)->isNotEmpty();
                    @endphp
                    <td class="align-middle">
                        @if($progressExists)
                            {{$details->progress[0]->updated_at}}
                        @endif
                    </td>
                    <td class="align-middle">
                        @if($progressExists)
                            @if($details->progress[0]->p_isComplete=='yes')
                                @if($details->progress[0]->isFreeze=='yes')
                                    Yes
                                @else
                                    Progress is not Freezed by the District 
                                @endif
                            @else
                                No
                            @endif
                        @else
                            Progress is not reported
                        @endif
                    </td>
                    <td class="align-middle">
                        @if($progressExists)
                            @php
                                $imageExists=optional($details->progress[0]->image)->isNotEmpty();
                            @endphp
                             @if($imageExists)
                             <div class="image-gallery">
                                 @foreach($details->progress[0]->image as $img)
                                    <div class="image-item">
                                        <a href="{{url('uploads/images/'.$img->image_path)}}" target="_blank">
                                        <img src="{{url('uploads/images/'.$img->image_path)}}" alt="Image">
                                        </a>
                                    </div>
                                 @endforeach
                             </div>
                            @else
                                <span>Images not found!</span>
                             @endif
                        @else
                            <span>Progress is not reported</span> 
                        @endif
                    </td>
                </tbody>
                @endforeach
            </table>
        </div>  
    </div>
</div>
@endsection

