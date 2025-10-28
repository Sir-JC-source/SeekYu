@extends('Layouts.vuexy')

@section('title', 'Shift In and Out')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Shift In and Out</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('shift.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shift_in" class="form-label">Shift In Time</label>
                                    <input type="time" class="form-control" id="shift_in" name="shift_in" value="{{ $employee->shift_in ?? '08:00' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shift_out" class="form-label">Shift Out Time</label>
                                    <input type="time" class="form-control" id="shift_out" name="shift_out" value="{{ $employee->shift_out ?? '17:00' }}" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Shift Times</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
