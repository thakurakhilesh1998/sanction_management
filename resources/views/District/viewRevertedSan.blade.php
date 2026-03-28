@extends('layouts/district')
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
        <h3 class="h3 mb-3 text-gray-800">View Reverted Sanction(s) from Executive Engineer</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="font-size:0.9rem">
            <table class="table table-bordered text-center" id="datatable">
            <thead>
                    <th>Sr. No.</th>
                    <th>District Name</th>
                    <th>Block Name</th>
                    <th>Gram Panchayat Name</th>
                    <th>Financial Year</th>
                    <th>Sanction Amount</th>
                    <th>Sanction Date</th>
                    <th>View Sanction File</th>
                    <th>Delete Sanction</th>
            </thead>
            <tbody>
                @foreach($sanction as $index=>$s)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$s->district}}</td>
                    <td>{{$s->block}}</td>
                    <td>{{$s->gp}}</td>
                    <td>{{$s->financial_year}}</td>
                    <td>{{addCommas($s->san_amount)}}</td>
                    <td>{{$s->sanction_date}}</td>
                    <td><a href="{{ route('viewSignedSanctionPdf', ['filename' => $s->san_sign_pdf]) }}" target="_blank">View Sanction File</a></td>
                    <td><a href="javascript:void(0)" class="btn btn-danger" data-id="{{$s->id}}" id="delbtn">Delete</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    <div class="m-3">
        <a href="{{ url()->previous()}}" class="ml-3 btn btn-primary btn-sm float-right">Back</a>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function()
    {
        $('#datatable').on('click', 'button[data-bs-target="#uploadsan"]', function() {
        var sanctionId = $(this).data('sanction-id');
        $('#sanction_id').val(sanctionId);
    });
    });
    $(document).on('click','#delbtn',function(){
        
        var id=$(this).data('id');;
        if(confirm("Are you sure you want to delete this sanction?")){
            $.ajax({
                url: "{{url('district/sanction-delete')}}/"+id,
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

