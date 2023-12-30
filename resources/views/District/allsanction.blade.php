@extends('layouts/district')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>All Sanctions For District Till Now</h3>
    </div>
    <div class="card-body">
        @if($sanction->isEmpty())
        <div class="alert alert-info">No Progress to show!</div>
        @else
        <div class="table table-responsive">
            <table class="table table-bordered text-center" id="datatable">
                <thead>
                    <th>Sr. No.</th>
                    <th>Block Name</th>
                    <th>Gram Panchayat Name</th>
                    <th>Financial Year</th>
                    <th>Sanction Date</th>
                    <th>Sanction Amount</th>
                    <th>IsCompleted?</th>
                    <th>IsFreezed?</th>
                    <th>New Gram Panchayat?</th>
                </thead>
                <tbody>
                    @php
                     $i=1;   
                    @endphp
                    @foreach ($sanction as $san)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$san->block}}</td>
                        <td>{{$san->gp}}</td>
                        <td>{{$san->financial_year}}</td>
                        <td>{{$san->sanction_date}}</td>
                        <td>{{$san->san_amount}}</td>
                        <td>
                            @php
                                $progressExists=optional($san->progress)->isNotEmpty();
                               
                            @endphp
                            {{ $progressExists?$san->progress[0]->p_isComplete:'';}}
                        </td>
                        <td>
                            {{ $progressExists?$san->progress[0]->isFreeze:'';}}
                        </td>
                        <td>{{$san->newGP}}</td>
                        @php
                        $i++
                        @endphp
                    </tr>
                    @endforeach
                    <tfoot>
                        <td colspan="5" class="text-center"><b>Total</b></td>
                        <td><b>Rs.{{$totalSanction}}</b></td>
                    </tfoot>
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
