@extends('layouts/dir')

@section('main')
<div class="card m-4 shadow">
    
    <div class="card-header text-white" style="background: linear-gradient(90deg, #0d6efd, #198754);">
        <h4 class="mb-0">XEN Division Wise Progress Report</h4>
    </div>

    <div class="card-body">

        @if(!empty($report))

        {{-- 🔷 Summary Cards --}}
        <div class="row mb-4">
            @foreach($report as $division => $data)
                @php
                    $percent = $data['total'] > 0 ? round(($data['completed'] / $data['total']) * 100, 1) : 0;
                @endphp

                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #0d6efd;">
                        <div class="card-body text-center">

                            <h5 class="fw-bold text-primary">{{ $division }}</h5>

                            <h2 class="fw-bold text-dark">{{ $data['total'] }}</h2>
                            <small class="text-muted">Total Works</small>

                            <hr>

                            <div class="text-start" style="font-size:14px;">
                                <div class="mb-1">
                                    <span class="badge bg-danger">Not Reported</span>
                                    <span class="float-end fw-bold">{{ $data['not_reported'] }}</span>
                                </div>
                                <div class="mb-1">
                                    <span class="badge bg-warning text-dark">In Progress</span>
                                    <span class="float-end fw-bold">{{ $data['in_progress'] }}</span>
                                </div>
                                <div>
                                    <span class="badge bg-success">Completed</span>
                                    <span class="float-end fw-bold">{{ $data['completed'] }}</span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <strong>Completion: {{ $percent }}%</strong>
                            </div>

                            <div class="progress mt-2" style="height:10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $percent }}%;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 🔷 Table View --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                
                <thead style="background-color:#0d6efd; color:white;">
                    <tr>
                        <th>Division</th>
                        <th>Total Works</th>
                        <th>Not Reported</th>
                        <th>In Progress</th>
                        <th>Completed</th>
                        <th>Completion Percentage</th>
                    </tr>
                </thead>

               <tbody>
@foreach($report as $division => $data)
    @php
        $percent = $data['total'] > 0 ? round(($data['completed'] / $data['total']) * 100, 1) : 0;
    @endphp

    <tr>
        <td class="fw-bold text-primary">{{ $division }}</td>

        <td>
            <a href="{{ route('xen.details', ['division'=>$division,'type'=>'total']) }}">
                {{ $data['total'] }}
            </a>
        </td>

        <td class="text-danger fw-bold">
            <a href="{{ route('xen.details', ['division'=>$division,'type'=>'not_reported']) }}" class="text-danger">
                {{ $data['not_reported'] }}
            </a>
        </td>

        <td class="text-warning fw-bold">
            <a href="{{ route('xen.details', ['division'=>$division,'type'=>'in_progress']) }}" class="text-warning">
                {{ $data['in_progress'] }}
            </a>
        </td>

        <td class="text-success fw-bold">
            <a href="{{ route('xen.details', ['division'=>$division,'type'=>'completed']) }}" class="text-success">
                {{ $data['completed'] }}
            </a>
        </td>

        <td>
            <span class="badge bg-success">{{ $percent }}%</span>
        </td>
    </tr>
@endforeach
</tbody>

            </table>
        </div>

        @else
            <div class="alert alert-info text-center fw-bold">
                No data available!
            </div>
        @endif

    </div>
</div>
@endsection