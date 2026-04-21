@extends('layouts/dir')

@section('main')
<div class="card m-4 shadow">

    {{-- Header --}}
    <div class="card-header text-white d-flex justify-content-between align-items-center"
        style="background: linear-gradient(90deg, #0d6efd, #198754);">

        <h4 class="mb-0">
            GP: <strong>{{$sanctionsForGP[0]->gp}}</strong> |
            Block: <strong>{{$sanctionsForGP[0]->block}}</strong>
        </h4>

        <button id="back" class="btn btn-light btn-sm">⬅ Back</button>
    </div>

    <div class="card-body">

        {{-- Alerts --}}
        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif

        {{-- 🔷 Sanction Table --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Sanction Details
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="table-primary">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Sanction Amount</th>
                            <th>Date</th>
                            <th>Head</th>
                            <th>Purpose</th>
                            <th>UC</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sanctionsForGP as $index=>$detail)
                        <tr>
                            <td>{{$index+1}}</td>

                            <td class="fw-bold text-primary">
                                ₹ {{ number_format($detail->san_amount,2) }}
                            </td>

                            <td>{{$detail->sanction_date}}</td>
                            <td>{{$detail->sanction_head}}</td>
                            <td>{{$detail->sanction_purpose}}</td>

                            <td>
                                @if($detail->uc)
                                    <a href="{{url('dir/viewUCgp',['filename'=>$detail->uc])}}"
                                       target="_blank"
                                       class="btn btn-sm text-white"
                                       style="background:#198754;">
                                        View UC
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Not Uploaded</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        {{-- 🔷 Status Cards --}}
        <div class="row text-center mb-4">

            {{-- Executing Authority --}}
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6 class="text-muted">Executing Authority</h6>
                    <h5 class="text-primary">
                        @if($sanctionsForGP[0]->status=='xen')
                            Executive Engineer
                        @elseif($sanctionsForGP[0]->status=='gp')
                            Gram Panchayat
                        @else
                            Not Available
                        @endif
                    </h5>
                </div>
            </div>

            {{-- Work Status --}}
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6 class="text-muted">Work Status</h6>
                    <h5 class="text-success">{{$completion}}</h5>
                </div>
            </div>

            {{-- Delay --}}
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6 class="text-muted">Delay</h6>
                    <h5 class="text-danger">
                        @if($days===0)
                            Not Reported
                        @elseif($completion==='Work Completed')
                            Completed
                        @else
                            {{$days}} days
                        @endif
                    </h5>
                </div>
            </div>

            {{-- Remarks --}}
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6 class="text-muted">Remarks</h6>
                    <p class="mb-0">
                        {{ $sanctionsForGP[0]->progress->remarks ?? 'No Remarks' }}
                    </p>
                </div>
            </div>

        </div>

        {{-- 🔷 Images Section --}}
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                Work Progress Images
            </div>

            <div class="card-body">
                <div class="row">

                    @if(!empty($sanctionsForGP[0]->progress->image->work_started_image))
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <img src="{{asset('uploads/images/'.$sanctionsForGP[0]->progress->image->work_started_image)}}"
                                 class="card-img-top">
                            <div class="card-body text-center">
                                <strong>Work Started</strong><br>
                                <a href="{{asset('uploads/images/'.$sanctionsForGP[0]->progress->image->work_started_image)}}"
                                   target="_blank">View</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($sanctionsForGP[0]->progress->image->work_partial_image))
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <img src="{{asset('uploads/images/'.$sanctionsForGP[0]->progress->image->work_partial_image)}}"
                                 class="card-img-top">
                            <div class="card-body text-center">
                                <strong>Partial Completion</strong><br>
                                <a href="{{asset('uploads/images/'.$sanctionsForGP[0]->progress->image->work_partial_image)}}"
                                   target="_blank">View</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($sanctionsForGP[0]->progress->image->work_completed_image))
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <img src="{{asset('uploads/images/'.$sanctionsForGP[0]->progress->image->work_completed_image)}}"
                                 class="card-img-top">
                            <div class="card-body text-center">
                                <strong>Work Completed</strong><br>
                                <a href="{{asset('uploads/images/'.$sanctionsForGP[0]->progress->image->work_completed_image)}}"
                                   target="_blank">View</a>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
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