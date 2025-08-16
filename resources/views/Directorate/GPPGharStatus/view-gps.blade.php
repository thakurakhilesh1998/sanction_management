@extends('layouts/dir')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>View Details of Panchayat Ghar of Development Block: {{$block}} </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <th>Sr. No.</th>
                    <th>Gram Panchayt Name</th>
                    <th>GP updated Data?</th>
                </thead>
                <tbody>
                    @foreach ($gps as $index => $gp)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$gp->gp_name}}</td>
                            <td>
                                @if($gp->uploaded_count>0)

                                    <a href="{{url('dir/viewGPData').'/'.$gp->gp_name.'/'.$block}}" class="href">Yes</a>
                                @else
                                    No
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