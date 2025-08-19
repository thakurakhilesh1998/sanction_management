@extends('layouts/xen')

@section('main')
<div class="card m-4">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <div class="card-header">
        <h3>Executive Engineer Home Page- RD sanctions</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
    </div>

    <div class="card-body">
        <h4 class="p-2">View Sanctions for the Executive Engineer</h4>

        @if($sanction->isEmpty())
            <div class="alert alert-info">No new sanction found.</div>
        @else 
            <div class="table-responsive">
                @php
                    $seen = [];   
                    $i = 1;
                @endphp
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>District Name</th>
                            <th>Block Name</th>
                            <th>Work</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sanction as $san)
                            @php
                                $uniqueKey = $san->work;
                            @endphp

                            @if(!in_array($uniqueKey, $seen))
                                @php $seen[] = $uniqueKey; @endphp
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $san->district }}</td>
                                    <td>{{ $san->block }}</td>
                                    <td>{{$san->sanction_purpose}}</td>
                                    <td>
                                        <a href="{{ url('xen/view-block-san/' . $san->district . '/' . $san->block. '/' . $san->work.'/'.$san->agency)}}">
                                            View Sanction
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Add any page-specific scripts here if needed -->
@endsection
