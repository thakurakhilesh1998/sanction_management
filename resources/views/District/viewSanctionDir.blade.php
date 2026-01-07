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
        <h3 class="h3 mb-3 text-gray-800">View Sanctions From Directorate
            <a href="{{ url()->previous()}}" class="ml-3 btn btn-primary btn-sm float-right">Back</a>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="font-size:0.9rem">
            <table class="table table-bordered text-center" id="datatable">
            <thead>
                    <th>Sr. No.</th>
                    <th>Sanction Id</th>
                    <th>Development Block</th>
                    <th>Gram Panchayat</th>
                    <th>Sanction Head</th>
                    <th>Amount</th>
                    <th>Sanction Order</th>
                    <th>Action</th>
                    <th>View Progress</th>
            </thead>
            <tbody>
                @php
                    $i=1;
                @endphp
                @foreach($sanction as $s)
                @if($s->san_sign_pdf!=null)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$s->id}}</td>
                        <td>{{$s->block}}</td>
                        <td>{{$s->gp}}</td>
                        <td>{{$s->sanction_head}}</td>
                        <td>{{$s->san_amount}}</td>
                        <td>
                            @if($s->san_sign_pdf!=null)
                                <a href="{{ route('viewSignedSanctionFileD', ['filename' => $s->san_sign_pdf]) }}" target="_blank">View Sanction File</a>    
                            @endif
                        </td>
                        <td>
                            @if($s->status==null)
                            <select name="forward_san" id="forward_san" class="form-control forward_sans" data-id="{{$s->id}}">
                                <option value="-1">-Select Action-</option>
                                <option value="xen">Forward to XEN</option>
                                <option value="gp">Forward to GP</option>
                            </select>
                            @else
                                <span><strong>Sanction Forwarded to {{$s->status}}</strong></span>
                            @endif

                        </td>
                        <td><a href="{{url('district/view-progress-gp/'.$s->gp.'/'.$s->block)}}">View</a></td>
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Attach the event listener to all select elements with the id 'forward_san'
    $(document).on("change",".forward_sans",function() {
    // $('.forward_sans').on("change", function() {
        let forward = $(this).val();
        let sanctionId = $(this).data('id');

        if (forward != -1) {
            if (confirm("Are you sure to forward this Sanction to " + forward + " ? Once forwarded, this action is not reversible.")) {
                $.ajax({
                    url: '{{ url('district/update-status') }}', // Correct URL syntax
                    type: 'POST',
                    data: {
                        status: forward,
                        id: sanctionId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) { // Moved success inside the AJAX object
                        if (response.success) {
                            alert("Sanction forwarded");
                            window.location.reload(); 

                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) { // Moved error inside the AJAX object
                        if (xhr.status === 404) {
                            alert("Sanction not found: " + xhr.responseJSON.error);
                        } else {
                            alert("Error Forwarding Sanction: " + xhr.responseJSON.message);
                        }
                    }
                });
            }
        }
    });
});
</script>
@endsection

