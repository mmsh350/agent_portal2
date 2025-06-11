@extends('layouts.dashboard')

@section('title', 'Services')
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
                            <h5 class="card-title">Services</h5>
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

                            <form method="GET" action="{{ route('admin.services.index') }}"
                                class="row g-2 mb-3 align-items-end">
                                <div class="col-sm-5 col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Search..."
                                        value="{{ request('search') }}">
                                </div>

                                <div class="col-sm-4 col-md-3">
                                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                                        @foreach ([10, 15, 25, 50, 100] as $size)
                                            <option value="{{ $size }}"
                                                {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                                Show {{ $size }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3 col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table text-nowrap" style="background:#fafafc !important">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $service)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ $service->category }}</td>
                                                <td>₦ {{ $service->amount }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $service->status == 'enabled' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ ucfirst($service->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.services.edit', $service->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="bx bx-edit"></i> Edit</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $services->links('pagination::bootstrap-5') }}
                            </div>



                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>


@endsection
