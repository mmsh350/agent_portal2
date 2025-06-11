@extends('layouts.dashboard')

@section('title', 'Modify Services')
@push('styles')
@endpush
@section('content')
    <div class="row">
        <div class="mb-3 mt-1">
            <h4 class="mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
        </div>
        <div class="col-lg-12 grid-margin d-flex flex-column">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card ">
                        <div class="card-header">
                            <h5 class="card-title">Modify Service</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {!! session('success') !!}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif


                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.services.update', $service->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Service Code</label>
                                    <input type="text" disabled name="service_code" class="form-control"
                                        value="{{ $service->service_code }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" disabled name="name" class="form-control" readonly
                                        value="{{ $service->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Amount (₦)</label>
                                    <input type="number" name="amount" class="form-control" value="{{ $service->amount }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control">{{ $service->description }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="enabled" {{ $service->status == 'enabled' ? 'selected' : '' }}>
                                            Enabled
                                        </option>
                                        <option value="disabled" {{ $service->status == 'disabled' ? 'selected' : '' }}>
                                            Disabled
                                        </option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i>
                                    Update Service</button>
                                <a href="{{ route('admin.services.index') }}" class="btn btn-danger"><i
                                        class="bx bx-arrow-back"></i> Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
