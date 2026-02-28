@extends('layouts.vuexy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Applicant Details</h4>
                    <a href="{{ route('201file.applicants') }}" class="btn btn-secondary">Back to Applicants</a>
                </div>
                <div class="card-body">
                    @if($applicant)
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Personal Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $applicant->user->first_name ?? '' }} {{ $applicant->user->middle_name ?? '' }} {{ $applicant->user->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $applicant->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $applicant->user->contact_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Applied Position:</th>
                                    <td>{{ $applicant->applied_position ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Government IDs</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>SSS Number:</th>
                                    <td>{{ $applicant->sss_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Pag-IBIG Number:</th>
                                    <td>{{ $applicant->pagibig_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>PhilHealth Number:</th>
                                    <td>{{ $applicant->philhealth_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <p class="text-center">Applicant not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
