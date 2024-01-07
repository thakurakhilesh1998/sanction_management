@extends('layouts/dir')
@section('main')
<div class="card m-4">

    @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif
    <div class="card-header">
        <h3 class="h3 mb-3 text-gray-800">View Sanction
            <a href="{{url('dir/')}}" class="btn btn-primary btn-sm float-right">Add Sanction</a>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="datatable">
            <thead>
                    <th>District Name</th>
                    <th>Block Name</th>
                    <th>Gram Panchayat Name</th>
                    <th>Assembly Constituency</th>
                    <th>Is New Gram Panchayat?</th>
                    <th>Financial Year</th>
                    <th>Sanction Amount</th>
                    <th>Sanction Date</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>View Progress</th>
            </thead>
            <tbody>
                @foreach($sanction as $s)
                <tr>
                    <td>{{$s->district}}</td>
                    <td>{{$s->block}}</td>
                    <td>{{$s->gp}}</td>
                    <td>{{$s->ac}}</td>
                    <td>{{$s->newGP}}</td>
                    <td>{{$s->financial_year}}</td>
                    <td>{{$s->san_amount}}</td>
                    <td>{{$s->sanction_date}}</td>

                    <td>
                        @php
                        $progressExists = optional($s->progress)->isNotEmpty();
                        $isFreeze=false;
                        if($progressExists)
                            {
                                if($s->progress[0]->isFreeze=='yes')
                                {
                                    $isFreeze=true;
                                }
                            }
                         @endphp

                         @if($progressExists)
                            
                            @if($isFreeze)
                                <span>Completed</span>
                            @elseif($s->progress[0]->p_isComplete=='yes')
                                <span>Completed but not freezed by District</span>
                            @else
                                <span>Sanction is <b>{{$s->progress[0]->completion_percentage}} %</b> Utilized</span>
                            @endif
                         @else
                            <span>Not Reported</span>
                         @endif
                    </td>    
                    <td>
                        @if($isFreeze)
                             <span>Freezed</span>
                        @elseif(!$isFreeze) 
                             <a href="{{url('dir/edit/').'/'.$s->id}}" class="btn btn-info text-white text-bold">Edit</a>
                        @endif
                    </td>
                    <td><a href="{{url('dir/gpDetails/').'/'.$s->gp}}">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection

