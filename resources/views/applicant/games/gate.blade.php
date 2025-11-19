@extends('Layouts.vuexy')

@section('title', 'Guard Assessment - Gate Screening')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">Guard Assessment: Gate Screening</h4>
                    <p class="text-muted mb-0">Complete this assessment to proceed to the next test.</p>
                </div>
                <div class="card-body p-0">
                    <!-- Embed the game HTML with corrected image paths -->
                    {!! str_replace(['gate/', 'url("gate/', 'url(\'gate/'], ['/GUARD/gate/', 'url("/GUARD/gate/', 'url(\'/GUARD/gate/'], file_get_contents(public_path('GUARD/1 - Gate Screening.html'))) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for game completion event
    window.addEventListener('seekyu:gate-screening-finished', function(e) {
        const data = e.detail;
        console.log('Gate screening finished:', data);

        // Store scores in sessionStorage
        sessionStorage.setItem('gate_score', data.score);
        sessionStorage.setItem('gate_total', data.total);
        sessionStorage.setItem('gate_pct', data.pct);
        sessionStorage.setItem('gate_time', data.time);

        // Redirect to next game
        window.location.href = '{{ route("applicant.games.bag") }}';
    });
});
</script>
@endsection
