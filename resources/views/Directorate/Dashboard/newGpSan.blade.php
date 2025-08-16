@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">

        <h3>
            @if($filter=='newGp')
                Sanctions for new Gram Panchayat
            @elseif($filter=='delay')
                Work where there is delay of more than 100 days
            @endif
        </h3>
    </div>
    <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center table-striped">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>District Name</th>
                            <th>Block Name</th>
                            <th>Gram Panchayat</th>
                            <th>Work Status</th>
                            <th>View Details</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($totalNewGPs as $index=>$works)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$works->district}}</td>
                                    <td>{{$works->block}}</td>
                                    <td>{{$works->gp}}</td>
                                    <td>@if($works->progress!=null)
                                            {{$works->progress->completion_percentage}}
                                        @else
                                            Progress not reported yet.
                                        @endif
                                    </td>
                                    <td><a href="{{url('dir/viewGpDetails').'/'.$works->gp.'/'.$works->block}}">View Details</a></td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</div>
@endsection