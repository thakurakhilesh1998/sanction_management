@extends('layouts/admin')
@section('main')
<div class="container-fluid">
    <div class="container text-black">
        <div class="card">
           @if(session('success'))
            <div class="alert alert-success" id="successMessage">
                <strong>Success!</strong> Sanction Added successfully.
            </div>
            @endif
            <div class="card-header">
                <h3 class="h3 mb-3 text-gray-800">View Sanction</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="datatable">
                        <thead>
                            <th>Sanction ID</th>
                            <th>District Name</th>
                            <th>Block Name</th>
                            <th>Gram Panchayat</th>
                            <th>Sanction Date</th>
                            <th>Amount</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @foreach($sanctions as $s)
                                <tr>
                                    <td>{{$s->id}}</td>
                                    <td>{{$s->district}}</td>
                                    <td>{{$s->block}}</td>
                                    <td>{{$s->gp}}</td>
                                    <td>{{$s->sanction_date}}</td>
                                    <td>{{$s->san_amount}}</td>
                                    <td><a href="{{route('admin.edit',$s->id)}}" class="btn btn-primary">Edit</a> </td>
                                    <td><a href="javascript:void(0)" class="btn btn-danger" data-id="{{$s->id}}" id="delbtn">Delete</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
   <script>
    $(document).on('click','#delbtn',function(){
        var id=$(this).data('id');;

        if(confirm("Are you sure you want to delete this sanction?")){
            $.ajax({
                url: "{{url('admin/sanction-delete')}}/"+id,
                type:'POST',
                data:{
                    "_token":'{{ csrf_token() }}',
                    "_method": "DELETE"
                },
                success: function(response){
                    alert("Deleted Successfully");
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error Deleting the record");
                }
            });
        }
    });
    </script>
@endsection
@endsection
