@extends('Layouts.vuexy')

@section('title', 'Submit Incident Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Submit Incident Report</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('incident-reports.store') }}" method="POST">
            @csrf

            {{-- Incident Name --}}
            <div class="mb-3">
                <label for="incident_name" class="form-label">Incident Name</label>
                <input type="text" id="incident_name" name="incident_name" class="form-control" required>
            </div>

            {{-- Date of Incident --}}
            <div class="mb-3">
                <label for="date_of_incident" class="form-label">Date of Incident</label>
                <input type="date" id="date_of_incident" name="date_of_incident" class="form-control" required>
            </div>

            {{-- Location --}}
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>

            {{-- Specific Area --}}
            <div class="mb-3">
                <label for="specific_area" class="form-label">Specific Area</label>
                <input type="text" id="specific_area" name="specific_area" class="form-control" required>
            </div>

            {{-- Incident Description --}}
            <div class="mb-3">
                <label for="incident_description" class="form-label">Incident Description</label>
                <textarea id="incident_description" name="incident_description" class="form-control" rows="4" required></textarea>
            </div>

            {{-- Parties Involved --}}
            <div class="mb-3">
                <label class="form-label">Parties Involved</label>

                <div id="parties-container">
                    <div class="party-group mb-3">
                        <div class="row g-2 align-items-center party-row">
                            <div class="col-md-4">
                                <input type="text" name="parties[0][name]" class="form-control" placeholder="Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="parties[0][role]" class="form-control" placeholder="Role" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="parties[0][contact]" class="form-control" placeholder="Contact No." required>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-party" disabled>&times;</button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <textarea name="parties[0][statement]" class="form-control" placeholder="Statement" rows="2" required></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-party" class="btn btn-outline-primary btn-sm mt-2">
                    + Add Another
                </button>
            </div>

            {{-- Submit --}}
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Submit IR</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let partyCount = 1;
    const container = document.getElementById('parties-container');
    const addButton = document.getElementById('add-party');

    // Add new party
    addButton.addEventListener('click', function() {
        const newGroup = document.createElement('div');
        newGroup.classList.add('party-group', 'mb-3');
        newGroup.innerHTML = `
            <div class="row g-2 align-items-center party-row">
                <div class="col-md-4">
                    <input type="text" name="parties[${partyCount}][name]" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="parties[${partyCount}][role]" class="form-control" placeholder="Role" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="parties[${partyCount}][contact]" class="form-control" placeholder="Contact No." required>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-party">&times;</button>
                </div>
            </div>
            <div class="mt-2">
                <textarea name="parties[${partyCount}][statement]" class="form-control" placeholder="Statement" rows="2" required></textarea>
            </div>
        `;
        container.appendChild(newGroup);
        partyCount++;
    });

    // Remove party
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-party')) {
            e.target.closest('.party-group').remove();
        }
    });
});
</script>
@endsection
