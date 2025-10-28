@extends('Layouts.vuexy')

@section('title', 'Submit Incident Report')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <!-- Header Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="avatar avatar-xl mx-auto mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-alert-triangle text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="card-title mb-1 text-primary">Submit Incident Report</h4>
                    <p class="text-muted mb-0">Please provide detailed information about the incident</p>
                </div>
            </div>

            <form action="{{ route('incident-reports.store') }}" method="POST">
                @csrf

                <!-- Incident Details Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <i class="ti ti-info-circle me-2 text-primary"></i>
                        <h5 class="card-title mb-0">Incident Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Incident Name -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="incident_name" name="incident_name" class="form-control" placeholder="Enter incident name" required>
                                    <label for="incident_name">
                                        <i class="ti ti-tag me-1"></i>Incident Name
                                    </label>
                                </div>
                            </div>

                            <!-- Date of Incident -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" id="date_of_incident" name="date_of_incident" class="form-control" required>
                                    <label for="date_of_incident">
                                        <i class="ti ti-calendar me-1"></i>Date of Incident
                                    </label>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="location" name="location" class="form-control" placeholder="Enter location" required>
                                    <label for="location">
                                        <i class="ti ti-map-pin me-1"></i>Location
                                    </label>
                                </div>
                            </div>

                            <!-- Specific Area -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="specific_area" name="specific_area" class="form-control" placeholder="Enter specific area" required>
                                    <label for="specific_area">
                                        <i class="ti ti-target me-1"></i>Specific Area
                                    </label>
                                </div>
                            </div>

                            <!-- Incident Description -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea id="incident_description" name="incident_description" class="form-control" style="height: 120px;" placeholder="Describe the incident in detail" required></textarea>
                                    <label for="incident_description">
                                        <i class="ti ti-file-text me-1"></i>Incident Description
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parties Involved Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-users me-2 text-primary"></i>
                            <h5 class="card-title mb-0">Parties Involved</h5>
                        </div>
                        <button type="button" id="add-party" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i>Add Party
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="parties-container">
                            <!-- Initial Party -->
                            <div class="party-card border rounded p-4 mb-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-primary">
                                        <i class="ti ti-user me-1"></i>Party #1
                                    </h6>
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-party" disabled>
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" name="parties[0][name]" class="form-control" placeholder="Full Name" required>
                                            <label><i class="ti ti-id me-1"></i>Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" name="parties[0][role]" class="form-control" placeholder="Role/Position" required>
                                            <label><i class="ti ti-briefcase me-1"></i>Role</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" name="parties[0][contact]" class="form-control" placeholder="Contact Number" required>
                                            <label><i class="ti ti-phone me-1"></i>Contact</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea name="parties[0][statement]" class="form-control" style="height: 80px;" placeholder="Statement or description" required></textarea>
                                            <label><i class="ti ti-message me-1"></i>Statement</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="ti ti-send me-2"></i>Submit Incident Report
                        </button>
                        <p class="text-muted mt-2 mb-0">Please review all information before submitting</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let partyCount = 1;
    const container = document.getElementById('parties-container');
    const addButton = document.getElementById('add-party');

    // Add new party
    addButton.addEventListener('click', function() {
        partyCount++;
        const newGroup = document.createElement('div');
        newGroup.classList.add('party-card', 'border', 'rounded', 'p-4', 'mb-3', 'bg-light');
        newGroup.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-primary">
                    <i class="ti ti-user me-1"></i>Party #${partyCount}
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm remove-party">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" name="parties[${partyCount-1}][name]" class="form-control" placeholder="Full Name" required>
                        <label><i class="ti ti-id me-1"></i>Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" name="parties[${partyCount-1}][role]" class="form-control" placeholder="Role/Position" required>
                        <label><i class="ti ti-briefcase me-1"></i>Role</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" name="parties[${partyCount-1}][contact]" class="form-control" placeholder="Contact Number" required>
                        <label><i class="ti ti-phone me-1"></i>Contact</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea name="parties[${partyCount-1}][statement]" class="form-control" style="height: 80px;" placeholder="Statement or description" required></textarea>
                        <label><i class="ti ti-message me-1"></i>Statement</label>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newGroup);
    });

    // Remove party
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-party') || e.target.closest('.remove-party')) {
            e.target.closest('.party-card').remove();
            // Renumber parties after removal
            const parties = container.querySelectorAll('.party-card');
            parties.forEach((party, index) => {
                const title = party.querySelector('h6');
                if (title) {
                    title.innerHTML = `<i class="ti ti-user me-1"></i>Party #${index + 1}`;
                }
            });
            partyCount = parties.length;
        }
    });
});
</script>
@endsection
