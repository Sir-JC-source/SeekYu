@extends('Layouts.vuexy')

@section('title', 'HR Assessment - Resume-ID Matching')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">HR Assessment: Resume-ID Matching</h4>
                    <p class="text-muted mb-0">Complete this assessment to proceed to the next test.</p>
                </div>
                <div class="card-body p-0">
                    {!! str_replace("resume-id/", "/HR/resume-id/", file_get_contents(base_path('HR/1 - Resume-ID Matching.html'))) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for HR game completion event
    window.addEventListener('seekyu:hr-resume-id-finished', function(e) {
        const data = e.detail || {};

        // Store scores in sessionStorage
        sessionStorage.setItem('resume_id_score', data.score ?? 0);
        sessionStorage.setItem('resume_id_total', data.total ?? 1);
        sessionStorage.setItem('resume_id_pct', data.pct ?? 0);
        sessionStorage.setItem('resume_id_time', data.time ?? data.timeTaken ?? '00:00');

        // Redirect to next game
        window.location.href = '{{ route("applicant.games.hr.sorting") }}';
    });
});
</script>
@endsection

