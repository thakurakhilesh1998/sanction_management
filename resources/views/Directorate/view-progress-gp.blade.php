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
        <h3 class="h3 mb-3 text-gray-800">View Progress of Sanctions
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center table-striped" id="districtTable">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Gram Panchayat Name</th>
                        <th>Total Sanction Amounts(Rs.)</th>
                        <th>Total Utilized</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i=1;
                        $grandTotalSanction = 0;
                        $grandTotalUtilized = 0;
                    @endphp
                    @foreach ($gps as $gp)
                        @php
                            $totalSanction=0;
                            $totalUtilized=0;    
                        @endphp    
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <a href="{{url('dir/gpDetails').'/'.$gp.'/'.$sanctions[0]->block.'/'.$sanctions[0]->district}}" class="districtCell">
                                    {{$gp}}
                                </a>
                            </td>

                            @php
                                $i++;
                            @endphp
                            @foreach($sanctions as $san)
                                @if($san->gp==$gp)
                                    @php
                                        $totalSanction += floatval($san->san_amount);
                                        $progressExists = optional($san->progress)->isNotEmpty();
                                        if($progressExists)
                                        {
                                            $totalUtilized += ($san->progress && $san->progress[0]->p_isComplete == 'yes' && $san->progress[0]->isFreeze=='yes') ? floatval($san->san_amount) : 0;
                                        }
                                        else {
                                            $totalUtilized += 0;
                                        }
                                    @endphp
                                @endif
                            @endforeach
                            <td>{{ number_format($totalSanction, 2) }}</td>
                            <td>{{ number_format($totalUtilized, 2) }}</td>
                        </tr>
                        @php
                            $grandTotalSanction +=$totalSanction;
                            $grandTotalUtilized += $totalUtilized;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th>{{ number_format($grandTotalSanction, 2) }}</th>
                        <th>{{ number_format($grandTotalUtilized, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
      
    </div>
</div>
@endsection
