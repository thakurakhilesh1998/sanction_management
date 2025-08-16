@extends('layouts/gp')
@section('main')
<div class="card m-4">
    <div class="card-header">
        <h3>Gram Panchayat Home Page</h3>
        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
             </div>
        @endif

        <div>Manage the progress you have reported.</div>
        @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
        @endif
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
                    <th>Amount</th>
                    <th>Utilized Percentage</th>
                    <th>IsCompleted?</th>
                    <th>Edit/View Progress</th>
                    <th>Freeze Progress</th>
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
                        <td>{{$san->san_amount}}</td>
                        <td>{{$san->progress[0]->p_isComplete=='yes'?"Completed":$san->progress[0]->completion_percentage}}</td>
                        <td>{{$san->progress[0]->p_isComplete}}</td> 
                        <td>
                            @if($san->progress[0]->isFreeze=='no')
                                <a href="{{ url('gp/update-progress/'.$san->id) }}" class="btn btn-primary notforzen" data-id="{{ $san->progress[0]->id }}">Edit</a>
                            @elseif($san->progress[0]->isFreeze=='yes')
                                <a href="{{ url('gp/view-progress/'.$san->id) }}" class="btn btn-primary notforzen" data-id="{{ $san->progress[0]->id }}">View</a>
                            @endif
                        </td>
                        <td>
                            @if ($san->progress[0]->p_isComplete == 'yes' && $san->progress[0]->isFreeze == 'no')
                                <button href="" class="btn btn-success freeze-btn" data-id="{{ $san->progress[0]->id }}">Freeze</button>
                            @elseif ($san->progress[0]->isFreeze == 'yes')
                                <span class="bg-success text-white p-1 rounded">Already Frozen</span>
                            @endif
                        </td>
                        @php
                        $i++
                        @endphp
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function()
{ 
    $('.freeze-btn').on('click',function(e)
    {
        e.preventDefault();
        let button=$(this);
        let progressId = button.data('id');
        if(confirm("Are you sure want to freeze the progress? Once freezed this can not be reverted?"))
        {
            $.ajax(
                {
                    url:'{{ url('gp/update-freeze') }}',
                    type:'POST',
                    data:{
                        id:progressId,
                        _token: '{{ csrf_token() }}',
                    },
                    success:function(response)
                    {
                        if(response.success)
                        {
                            button.replaceWith('<span class="bg-success text-white p-1 rounded">Already Frozen</span>');
                            hideEditButton(progressId); 
                        }
                        else
                        {
                            alert(response.message);
                        }
                    }
                    ,error:function(xhr, status, error)
                    {
                        if (xhr.status === 404) {
                        alert("Progress not found: " + xhr.responseJSON.error);
                    } else {
                        alert("Error Updating Freezing: " + xhr.responseJSON.message);
                    }
                    }
                }
            );
        }
    });
    function hideEditButton(progressId)
    {
        $('.notforzen[data-id="' + progressId + '"]').hide();
    }
});
</script>
@endsection
