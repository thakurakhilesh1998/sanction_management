@extends('layouts/district')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>District Home Page</h3>
        <div>You can update your progress here!</div>
        @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif
    </div>
    <div class="card-body">
        @if($sanction->isEmpty())
        <div class="alert alert-info">No Progress to show!</div>
        @else
        <div class="table table-responsive">
            <table class="table table-bordered text-center">
                {{-- {{dd($sanction)}} --}}
                <thead>
                    <th>Sr. No.</th>
                    <th>Block Name</th>
                    <th>Gram Panchayat Name</th>
                    <th>Amount</th>
                    <th>Utilized Percentage</th>
                    <th>IsCompleted?</th>
                    <th>Update Progress</th>
                </thead>
                <tbody>
                    @foreach ($sanction as $san)
                    <td>1</td>
                    <td>{{$san->block}}</td>
                    <td>{{$san->gp}}</td>
                    <td>{{$san->san_amount}}</td>
                    <td>{{$san->progress()->completion_percentage}}</td>
                    <td>{{$san->progress()->p_isComplete}}</td>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

