@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>View Details of Panchayat Ghar</h3>
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
                                    <a href="{{url('dir/viewGPs').'/'.$block->block_name}}" class="href">{{$block->uploaded_count}}</a>
                                @else
                                    0
                                @endif
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="m-3">
            <button  id='back' class="ml-3 btn btn-primary btn-sm float-right">Back</button>
        </div>
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