@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>View Images of Panchayat Ghar</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <th>Sr. No.</th>
                    <th>District Name</th>
                    <th>No. of GPs updated Data</th>
                </thead>
                <tbody>
                    @foreach ($districts as $index => $district)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$district->district_name}}</td>
                            <td>
                                @if($district->uploaded_count>0)
                                    <a href="{{url('dir/viewBlocksGp').'/'.$district->district_name}}" class="href">{{$district->uploaded_count}}</a>
                                @else
                                    0
                                @endif
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection