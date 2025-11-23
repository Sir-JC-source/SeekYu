@extends('Layouts.vuexy')

@section('title', 'Guard Assessment - Memory Test')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">Guard Assessment: Memory Test</h4>
                    <p class="text-muted mb-0">Complete this final assessment to submit your application.</p>
                </div>
                <div class="card-body p-0">
                    <!-- Embed the game HTML with corrected image paths -->
                    {!! str_replace(['memory/', 'url("memory/', 'url(\'memory/'], ['/GUARD/memory/', 'url("/GUARD/memory/', 'url(\'/GUARD/memory/'], file_get_contents(public_path('GUARD/3 - Memory Test.html'))) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scores Overview Modal -->
<div class="modal fade" id="scoresModal" tabindex="-1" aria-labelledby="scoresModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scoresModalLabel">Assessment Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Gate Screening</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="text-primary" id="gate-score">0/10</h4>
                                <p class="text-muted mb-1">Accuracy: <span id="gate-pct">0%</span></p>
                                <p class="text-muted mb-0">Time: <span id="gate-time">00:00</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Bag Inspection</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="text-primary" id="bag-score">0/7</h4>
                                <p class="text-muted mb-1">Accuracy: <span id="bag-pct">0%</span></p>
                                <p class="text-muted mb-0">Time: <span id="bag-time">00:00</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Memory Test</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="text-primary" id="memory-score">0/5</h4>
                                <p class="text-muted mb-1">Accuracy: <span id="memory-pct">0%</span></p>
                                <p class="text-muted mb-0">Time: <span id="memory-time">00:00</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <h5>Overall Assessment Status: <span id="overall-status" class="text-success">Passed</span></h5>
                        <p class="text-muted">Your application will be submitted with these results.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Review Later</button>-->
                <button type="button" class="btn btn-primary" id="submitApplicationBtn">Submit Application</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for game completion event
    window.addEventListener('seekyu:memory-finished', function(e) {
        const data = e.detail;
        console.log('Memory test finished:', data);

        // Store scores in sessionStorage
        sessionStorage.setItem('memory_score', data.score);
        sessionStorage.setItem('memory_total', data.total);
        sessionStorage.setItem('memory_pct', data.pct);
        sessionStorage.setItem('memory_time', data.totalTime);

        // Show scores overview modal
        showScoresModal(data);
    });

    function showScoresModal(memoryData) {
        // Get all scores from sessionStorage
        const gateScore = parseInt(sessionStorage.getItem('gate_score')) || 0;
        const gateTotal = parseInt(sessionStorage.getItem('gate_total')) || 10;
        const gatePct = parseFloat(sessionStorage.getItem('gate_pct')) || 0;
        const gateTime = sessionStorage.getItem('gate_time') || '00:00';

        const bagScore = parseInt(sessionStorage.getItem('bag_correct')) || 0;
        const bagTotal = 7;
        const bagPct = parseFloat(sessionStorage.getItem('bag_pct')) || 0;
        const bagTime = sessionStorage.getItem('bag_time') || '00:00';

        const memoryScore = memoryData.score || 0;
        const memoryTotal = memoryData.total || 5;
        const memoryPct = memoryData.pct || 0;
        const memoryTime = memoryData.totalTime || '00:00';

        // Update modal content
        document.getElementById('gate-score').textContent = `${gateScore}/${gateTotal}`;
        document.getElementById('gate-pct').textContent = `${gatePct}%`;
        document.getElementById('gate-time').textContent = gateTime;

        document.getElementById('bag-score').textContent = `${bagScore}/${bagTotal}`;
        document.getElementById('bag-pct').textContent = `${bagPct}%`;
        document.getElementById('bag-time').textContent = bagTime;

        document.getElementById('memory-score').textContent = `${memoryScore}/${memoryTotal}`;
        document.getElementById('memory-pct').textContent = `${memoryPct}%`;
        document.getElementById('memory-time').textContent = memoryTime;

        // Calculate overall status
        const overallPct = Math.round(((gatePct + bagPct + memoryPct) / 3));
        const overallStatus = overallPct >= 60 ? 'Passed' : 'Failed';
        document.getElementById('overall-status').textContent = overallStatus;
        document.getElementById('overall-status').className = overallPct >= 60 ? 'text-success' : 'text-danger';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('scoresModal'));
        modal.show();

        // Handle submit button
        document.getElementById('submitApplicationBtn').onclick = function() {
            modal.hide();
            submitApplication();
        };
    }

    function submitApplication() {
        // Collect all scores from sessionStorage and parse as numbers where needed
        const scores = {
            gate_score: parseInt(sessionStorage.getItem('gate_score')) || 0,
            gate_total: parseInt(sessionStorage.getItem('gate_total')) || 10,
            gate_pct: parseFloat(sessionStorage.getItem('gate_pct')) || 0,
            gate_time: sessionStorage.getItem('gate_time') || '00:00',
            bag_score: parseInt(sessionStorage.getItem('bag_correct')) || 0,
            bag_total: 7, // Total prohibited items
            bag_pct: parseFloat(sessionStorage.getItem('bag_pct')) || 0,
            bag_time: sessionStorage.getItem('bag_time') || '00:00',
            memory_score: parseInt(sessionStorage.getItem('memory_score')) || 0,
            memory_total: parseInt(sessionStorage.getItem('memory_total')) || 5,
            memory_pct: parseFloat(sessionStorage.getItem('memory_pct')) || 0,
            memory_time: sessionStorage.getItem('memory_time') || '00:00'
        };

        // Send AJAX request to submit application
        fetch('{{ route("applicant.games.submit-scores") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(scores)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear session storage
                sessionStorage.removeItem('job_id');
                sessionStorage.removeItem('gate_score');
                sessionStorage.removeItem('gate_total');
                sessionStorage.removeItem('gate_pct');
                sessionStorage.removeItem('gate_time');
                sessionStorage.removeItem('bag_correct');
                sessionStorage.removeItem('bag_wrong');
                sessionStorage.removeItem('bag_pct');
                sessionStorage.removeItem('bag_time');
                sessionStorage.removeItem('memory_score');
                sessionStorage.removeItem('memory_total');
                sessionStorage.removeItem('memory_pct');
                sessionStorage.removeItem('memory_time');

                // Redirect to success page
                window.location.href = '{{ route("applicant.applications") }}';
            } else {
                alert('Error submitting application: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting application');
        });
    }
});
</script>
@endsection
