<div class="card">
    <div class="card-header">
        <h5>Deploy Guard</h5>
    </div>
    <div class="card-body">
        <form action="{{ isset($guard) ? route('security.deploy.store', $guard->id) : '#' }}" method="POST" id="deployGuardForm" data-guard-id="{{ isset($guard) ? $guard->id : '' }}">
            @csrf

            <div class="row g-2 mb-2">
                <div class="col-md-3">
                    <label for="employee_number" class="form-label small">Employee No.</label>
                    <input type="text" class="form-control form-control-sm" id="employee_number" 
                           value="{{ $guard->employee_number ?? '' }}" name="employee_number" readonly>
                </div>
                <div class="col-md-5">
                    <label for="full_name" class="form-label small">Full Name</label>
                    <input type="text" class="form-control form-control-sm" id="full_name" 
                           value="{{ $guard->full_name ?? '' }}" name="full_name" readonly>
                </div>
                <div class="col-md-4">
                    <label for="position" class="form-label small">Position</label>
                    <input type="text" class="form-control form-control-sm" id="position" 
                           value="{{ $guard->position ?? '' }}" name="position" readonly>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <label for="deployment_date" class="form-label small">Deployment Date</label>
                    <input type="date" class="form-control form-control-sm" id="deployment_date" name="deployment_date"
                           min="{{ date('Y-m-d') }}" max="{{ now()->endOfYear()->addYear()->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="client_id" class="form-label small">Designate to:</label>
                    <select class="form-select form-select-sm" id="client_id" name="client_id">
                        <option value="">Select Client</option>
                        @foreach($clients ?? [] as $client)
                            <option value="{{ $client->id }}" {{ (old('client_id') == $client->id) ? 'selected' : '' }}>
                                {{ $client->fullname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-12">
                    <label for="assigned_head_guard_id" class="form-label small">Head Guard</label>
                    <select class="form-select form-select-sm" id="assigned_head_guard_id" name="assigned_head_guard_id" required>
                        @if(isset($guard) && $guard->position === 'Head Guard')
                            <option value="{{ $guard->id }}" selected>{{ $guard->full_name }}</option>
                        @else
                            <option value="">Select Head Guard</option>
                            @foreach($headGuards ?? [] as $head)
                                <option value="{{ $head->id }}">{{ $head->full_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm w-100" {{ isset($guard) ? '' : 'disabled' }}>Deploy Guard</button>
                </div>
            </div>

        </form>
    </div>
</div>

