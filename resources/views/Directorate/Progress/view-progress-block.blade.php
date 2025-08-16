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
        <h3 class="h3 mb-3 text-gray-800">View Progress of Sanctions of <strong>{{$district}}</strong> 
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center table-striped" id="districtTable">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Block Name</th>
                        <th>Total Number of Sanctions</th>
                        <th>Total Amount Sent</th>
                        <th>Total Utilized Amount</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $totalSanAmount=0;
                            $totalUtilized=0;    
                            $totalSan=0;
                        @endphp
                        @foreach ($blockSanctions as $index => $san)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$san->block}}</td>
                                <td><a href="{{url('dir/viewgpprogress'.'/'.$san->block.'/'.$district)}}">{{$san->total_sanctions}}</a></td>
                                <td>{{$san->total_sanction_amount}}</td>
                                <td>{{$san->utilized_amount}}</td>
                                @php
                                    $totalSanAmount+=$san->total_sanction_amount;
                                    $totalUtilized+=$san->utilized_amount;
                                    $totalSan+=$san->total_sanctions;
                            @endphp
                            </tr>
                        @endforeach
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th>{{$totalSan}}</th>
                                <th>{{$totalSanAmount}}</th>
                                <th>{{$totalUtilized}}</th>
                            </tr>
                        </tfoot>
                </tbody>
            </table>
        </div>
    </div>
    <div class="m-3">
        <button  id='back' class="ml-3 btn btn-primary btn-sm float-right">Back</button>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.getElementById('back').addEventListener('click', function() {
        window.history.back();
    });
</script>
@endsection