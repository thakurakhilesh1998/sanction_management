@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Panchayat Ghar Completed</h3>
    </div>
    <div class="card-body">
        @if($iscompletedWorks==1)
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
                        @php
                            $index=0;
                        @endphp
                        @foreach ($completedWorks as $works)
                            @if($works->progress!=null)
                            @php
                                $index=$index+1;
                            @endphp
                                <tr>
                                    <td>{{$index}}</td>
                                    <td>{{$works->district}}</td>
                                    <td>{{$works->block}}</td>
                                    <td>{{$works->gp}}</td>
                                    <td>{{$works->progress->completion_percentage}}</td>
                                    <td><a href="{{url('dir/viewGpDetails').'/'.$works->gp.'/'.$works->block}}">View Details</a></td>
                                </tr>
                            @else
                                @continue
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">No Work is Completed yet!</div>  
        @endif
    </div>
</div>
@endsection