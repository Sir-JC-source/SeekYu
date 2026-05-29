@extends('Layouts.vuexy')

@section('title', 'HR Assessment - Folder Sorting')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">HR Assessment: Folder Sorting</h4>
                    <p class="text-muted mb-0">Complete this assessment to proceed to the final test.</p>
                </div>
                <div class="card-body p-0">
{!! str_replace(['sorting/'], ['/HR/sorting/'], file_get_contents(base_path('HR/2 - Folder Sorting.html'))) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.addEventListener('seekyu:hr-folder-sorting-finished', function(e) {
        const data = e.detail || {};

        // Store scores in sessionStorage
        sessionStorage.setItem('folder_sort_score', data.score ?? 0);
        sessionStorage.setItem('folder_sort_total', data.total ?? 1);
        sessionStorage.setItem('folder_sort_pct', data.pct ?? 0);
        sessionStorage.setItem('folder_sort_time', data.time ?? data.timeTaken ?? '00:00');

        // Redirect to next game
        window.location.href = '{{ route("applicant.games.hr.client-guard") }}';
    });
});
</script>
@endsection

