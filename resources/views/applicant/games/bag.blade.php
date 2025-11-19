@extends('Layouts.vuexy')

@section('title', 'Guard Assessment - Bag Inspection')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">Guard Assessment: Bag Inspection</h4>
                    <p class="text-muted mb-0">Complete this assessment to proceed to the final test.</p>
                </div>
                <div class="card-body p-0">
                    <!-- Embed the game HTML with corrected image paths -->
                    {!! str_replace(['bag/', 'url("bag/', 'url(\'bag/'], ['/GUARD/bag/', 'url("/GUARD/bag/', 'url(\'/GUARD/bag/'], file_get_contents(public_path('GUARD/2 - Bag Inspection.html'))) !!}
                    {!! str_replace(['images/bag.png'], ['/GUARD/bag/bag.png'], file_get_contents(public_path('GUARD/2 - Bag Inspection.html'))) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for game completion event
    window.addEventListener('seekyu:bag-inspection-finished', function(e) {
        const data = e.detail;
        console.log('Bag inspection finished:', data);

        // Store scores in sessionStorage
        sessionStorage.setItem('bag_correct', data.correctConfiscated);
        sessionStorage.setItem('bag_wrong', data.wrongConfiscated);
        sessionStorage.setItem('bag_pct', data.pct);
        sessionStorage.setItem('bag_time', data.time);

        // Redirect to next game
        window.location.href = '{{ route("applicant.games.memory") }}';
    });
});
</script>
@endsection
