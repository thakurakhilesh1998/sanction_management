@extends('layouts/app')
@section('content')
<div class="container">
    {{-- Introduction Section --}}
    @if(empty($sanction))
    <div class="alert-danger">
        Nothing found!
    </div>
    @else 
    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <div class="card" style="font-family: sans-serif; font-size:1rem">
        <div class="card-header">
            <h3 class="d-flex justify-content-between align-items-center">
                View Details
                <a href="{{url()->previous()}}" class="btn btn-primary btn-sm">Back</a>
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="datatable" style="font-size: 0.9rem">
                    <thead>
                        <th>Sr. No.</th>
                        <th>District Name</th>
                        <th>Block Name</th>
                        <th>Assembly Constituency</th>
                        <th>Gram Panchayat Name</th>
                        <th>Is New Gram Panchayat?</th>
                        <th>Financial Year</th>
                        <th>Sanction Amount</th>
                        <th>Sanction Date</th>
                        <th>Status</th>
                        <th>View Progress</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                            $totalUtilized=0;
                            $totalSanctioned=0;
                        @endphp
                        @foreach ($sanction as $san)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$san->district}}</td>
                                <td>{{$san->block}}</td>
                                <td>{{$san->ac}}</td>
                                <td>{{$san->gp}}</td>
                                <td>{{$san->newGP}}</td>
                                <td>{{$san->financial_year}}</td>
                                <td>{{addCommas($san->san_amount)}}</td>
                                <td>{{$san->sanction_date}}</td>

                                @php
                                $i++;
                                    $isProgress=optional($san->progress)->isNotEmpty();    
                                    $totalSanctioned+=$san->san_amount;
                                @endphp
                                <td>
                                    @if($isProgress)
                                        @if($san->progress[0]->isFreeze=='yes')
                                            @php $totalUtilized+=$san->san_amount; @endphp
                                            Completed
                                        @elseif($san->progress[0]->completion_percentage!=NULL)
                                            {{$san->progress[0]->completion_percentage}}
                                        @endif
                                    @else
                                        Progress is not started.
                                    @endif

                                </td>
                                <td><a href="{{url('/showGpDetails'.'/'.$san->gp)}}">View</a></td>
                            </tr>    
                        @endforeach
                    </tbody>
                </table>
                <div class="mb-4">
                    <b><span>Total Sanctioned:{{$totalSanctioned}}</span>&nbsp&nbsp&nbsp&nbsp&nbsp
                    <span>Total Utilized:{{$totalUtilized}}</span></b>
                </div>
            </div>
        </div>
    </div>
    @endif  
</div>
@endsection
