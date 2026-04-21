@extends('layouts/dir')

@section('main')
<div class="card m-4 shadow">

    <div class="card-header text-white" style="background:#0d6efd;">
        <h4 class="mb-0">
            {{ $division }} Division - {{ ucwords(str_replace('_',' ', $type)) }} Works
        </h4>
    </div>

    <div class="card-body">

        @if(count($filtered) > 0)

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                
                <thead class="table-primary">
                    <tr>
                        <th>Sr. No.</th>
                        <th>District</th>
                        <th>Block</th>
                        <th>Gram Panchayat</th>
                        <th>Sanction Amount</th>
                        <th>Status</th>
                        <th>View Details</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($filtered as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->district }}</td>
                            <td>{{ $item->block }}</td>
                            <td>{{ $item->gp }}</td>
                            <td> <span class="badge bg-primary fs-6 px-3 py-2"> ₹ {{ number_format($item->total_amount, 2) }}
                                 </span>
                            </td>
                            <td>
                                @if(!$item->progress)
                                    <span class="badge bg-danger">Not Reported</span>
                                @elseif($item->progress->completion_percentage == 'Work Completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">In Progress</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('dir/viewGpDetails/' . $item->gp.'/'.$item->block) }}" class="btn btn-sm btn-info">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        @else
            <div class="alert alert-info text-center">
                No records found.
            </div>
        @endif

    </div>
</div>
@endsection