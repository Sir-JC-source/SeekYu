@extends('Layouts.vuexy')

@section('title', 'HR Assessment - Client-Guard Match')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">HR Assessment: Client-Guard Match</h4>
                    <p class="text-muted mb-0">Complete this assessment to submit your HR Officer application.</p>
                </div>
<div class="card-body p-0">
{!! str_replace(
                    ['resume-id/', 'sorting/'],
                    ['/HR/resume-id/', '/HR/sorting/'],
                    str_replace("endBtn.onclick = () => console.log('END clicked');",
                                "endBtn.onclick = () => {\n        window.dispatchEvent(new CustomEvent('seekyu:hr-client-guard-finished', {\n            detail: {\n                score: matched,\n                total: total,\n                pct: isPass ? 100 : 0,\n                time: document.getElementById('pixelTimer').textContent\n            }\n        }));\n    };",
                                file_get_contents(base_path('HR/3 - Client-Guard Match.html')))
                ) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
window.addEventListener('seekyu:hr-client-guard-finished', function(e) {
        const data = e.detail || {};

        // Store scores in sessionStorage
        sessionStorage.setItem('client_guard_match_score', data.score ?? 0);
        sessionStorage.setItem('client_guard_match_total', data.total ?? 1);
        sessionStorage.setItem('client_guard_match_pct', data.pct ?? 0);
        sessionStorage.setItem('client_guard_match_time', data.time ?? data.timeTaken ?? '00:00');

        // Show simple confirmation and auto-submit
        // (Final submission uses the same endpoint for all 3 HR screens.)
        submitHrApplication();
    });

    function submitHrApplication() {
        const scores = {
            resume_id_score: parseInt(sessionStorage.getItem('resume_id_score')) || 0,
            resume_id_total: parseInt(sessionStorage.getItem('resume_id_total')) || 1,
            resume_id_pct: parseFloat(sessionStorage.getItem('resume_id_pct')) || 0,
            resume_id_time: sessionStorage.getItem('resume_id_time') || '00:00',

            folder_sort_score: parseInt(sessionStorage.getItem('folder_sort_score')) || 0,
            folder_sort_total: parseInt(sessionStorage.getItem('folder_sort_total')) || 1,
            folder_sort_pct: parseFloat(sessionStorage.getItem('folder_sort_pct')) || 0,
            folder_sort_time: sessionStorage.getItem('folder_sort_time') || '00:00',

            client_guard_match_score: parseInt(sessionStorage.getItem('client_guard_match_score')) || 0,
            client_guard_match_total: parseInt(sessionStorage.getItem('client_guard_match_total')) || 1,
            client_guard_match_pct: parseFloat(sessionStorage.getItem('client_guard_match_pct')) || 0,
            client_guard_match_time: sessionStorage.getItem('client_guard_match_time') || '00:00',
        };

        fetch('{{ route("applicant.games.hr.submit-scores") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(scores)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Cleanup
                sessionStorage.removeItem('resume_id_score');
                sessionStorage.removeItem('resume_id_total');
                sessionStorage.removeItem('resume_id_pct');
                sessionStorage.removeItem('resume_id_time');

                sessionStorage.removeItem('folder_sort_score');
                sessionStorage.removeItem('folder_sort_total');
                sessionStorage.removeItem('folder_sort_pct');
                sessionStorage.removeItem('folder_sort_time');

                sessionStorage.removeItem('client_guard_match_score');
                sessionStorage.removeItem('client_guard_match_total');
                sessionStorage.removeItem('client_guard_match_pct');
                sessionStorage.removeItem('client_guard_match_time');

                window.location.href = '{{ route("applicant.applications") }}';
            } else {
                alert('Error submitting HR application: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error submitting HR application');
        });
    }
});
</script>
@endsection

