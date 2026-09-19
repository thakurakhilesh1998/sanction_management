@extends('layouts.gp')

@section('main')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            PRI Asset Module Diagnostic
        </h2>

        <p class="text-muted mb-0">
            Temporary diagnostic tool for GP Asset module
        </p>

    </div>


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        Passed
                    </div>

                    <div class="fs-1 fw-bold text-success">
                        {{ $passed }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        Failed
                    </div>

                    <div class="fs-1 fw-bold text-danger">
                        {{ $failed }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        Skipped
                    </div>

                    <div class="fs-1 fw-bold text-warning">
                        {{ $skipped }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        Information
                    </div>

                    <div class="fs-1 fw-bold text-primary">
                        {{ $infoCount }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Main status --}}

    @if($failed > 0)

        <div class="alert alert-danger shadow-sm">

            <h5 class="alert-heading fw-bold">
                Problem Detected
            </h5>

            <p class="mb-0">
                One or more diagnostic checks failed.
                Please check the red <strong>FAIL</strong>
                rows below.
            </p>

        </div>

    @else

        <div class="alert alert-success shadow-sm">

            <h5 class="alert-heading fw-bold">
                No Diagnostic Failure Detected
            </h5>

            <p class="mb-0">
                All executable checks passed.
                If the actual GP page still shows HTTP 500,
                the next step will be to inspect the HTTP request
                and server-side Laravel error for that request.
            </p>

        </div>

    @endif


    {{-- Results --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Diagnostic Results
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width:60px;">
                                #
                            </th>

                            <th style="width:300px;">
                                Check
                            </th>

                            <th style="width:110px;">
                                Status
                            </th>

                            <th>
                                Result / Error
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($results as $index => $result)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>


                            <td>
                                <strong>
                                    {{ $result['name'] }}
                                </strong>
                            </td>


                            <td>

                                @if($result['status'] === 'PASS')

                                    <span class="badge bg-success">
                                        PASS
                                    </span>

                                @elseif($result['status'] === 'FAIL')

                                    <span class="badge bg-danger">
                                        FAIL
                                    </span>

                                @elseif($result['status'] === 'SKIPPED')

                                    <span class="badge bg-warning text-dark">
                                        SKIPPED
                                    </span>

                                @else

                                    <span class="badge bg-info text-dark">
                                        INFO
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($result['status'] === 'FAIL')

                                    <div class="text-danger">

                                        <div class="fw-bold fs-6 mb-2">

                                            {{ $result['message'] }}

                                        </div>


                                        @if(!empty($result['file']))

                                            <div class="small mb-1">

                                                <strong>
                                                    File:
                                                </strong>

                                                {{ $result['file'] }}

                                            </div>

                                        @endif


                                        @if(!empty($result['line']))

                                            <div class="small mb-2">

                                                <strong>
                                                    Line:
                                                </strong>

                                                {{ $result['line'] }}

                                            </div>

                                        @endif


                                        @if(!empty($result['trace']))

                                            <details>

                                                <summary
                                                    class="text-dark fw-bold"
                                                    style="cursor:pointer;">

                                                    Show Full Stack Trace

                                                </summary>


                                                <pre
                                                    class="mt-3 p-3 bg-dark text-white rounded"
                                                    style="
                                                        white-space:pre-wrap;
                                                        word-break:break-word;
                                                        font-size:12px;
                                                        max-height:500px;
                                                        overflow:auto;
                                                    "
                                                >{{ $result['trace'] }}</pre>

                                            </details>

                                        @endif

                                    </div>


                                @elseif($result['status'] === 'PASS')

                                    <span class="text-success">
                                        {{ $result['message'] }}
                                    </span>


                                @elseif($result['status'] === 'SKIPPED')

                                    <span class="text-warning">
                                        {{ $result['message'] }}
                                    </span>


                                @else

                                    <span class="text-info">
                                        {{ $result['message'] }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Important warning --}}

    <div class="alert alert-warning mt-4">

        <strong>Temporary Diagnostic Page</strong>

        <p class="mb-2 mt-2">

            This page is only for troubleshooting the PRI Asset module.
            It should be removed after the problem is identified.

        </p>

        <strong>Remove after testing:</strong>

        <ul class="mb-0">

            <li>
                <code>GpAssetDiagnosticController.php</code>
            </li>

            <li>
                Diagnostic route from <code>web.php</code>
            </li>

            <li>
                <code>diagnostic.blade.php</code>
            </li>

        </ul>

    </div>

</div>

@endsection