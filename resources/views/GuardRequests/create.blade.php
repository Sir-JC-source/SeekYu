@extends('Layouts.vuexy')

@section('content')
<div class="container mt-3">
    <h2>Request Security Guards</h2>
    <form method="POST" action="{{ route('guard-requests.store') }}">
        @csrf
        <div class="mb-3">
            <label for="number_of_guards" class="form-label">Number of Guards Needed</label>
            <input type="number" id="number_of_guards" name="number_of_guards" class="form-control @error('number_of_guards') is-invalid @enderror" value="{{ old('number_of_guards') }}" min="1" required>
            @error('number_of_guards')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="request_details" class="form-label">Request Details</label>
            <textarea id="request_details" name="request_details" class="form-control @error('request_details') is-invalid @enderror" rows="4">{{ old('request_details') }}</textarea>
            @error('request_details')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</div>
@endsection
