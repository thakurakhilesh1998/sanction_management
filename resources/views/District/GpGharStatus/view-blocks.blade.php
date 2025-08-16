@extends('layouts/district')
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
                    <th>Block Name</th>
                    <th>No. of GPs updated Data</th>
                </thead>
                <tbody>
                    @foreach ($blocks as $index => $block)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$block->block_name}}</td>
                            <td>
                                @if($block->uploaded_count>0)
                                    <a href="{{url('district/viewGPs').'/'.$block->block_name}}" class="href">{{$block->uploaded_count}}</a>
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