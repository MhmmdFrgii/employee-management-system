{{-- @extends('dashboard.layouts.main')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-4">
                @if (session()->has('success'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- <h1 class="h3">Detail Pelamar - Asanti Muda Syantika</h1>
                    <h1 class="h3">{{ __('Detail Pelamar - ') . $user->name }}</h1>
                </div>

                <div class="row">
                    <div class="d-flex">
                        <!-- Gambar -->
                        <div class="p-3">
                            <img src="{{ asset('assets/images/no-data.png') }}" alt="avatar" class="rounded-1 img-fluid" width="350px" height="350px">
                        </div>
                        
                        <!-- Data -->
                        <div class="p-3">
                            <table class="table table-borderless">
                                <div class="row">
                                    <div class="col-md-6">
                                        <tr>
                                            <td>NIK</td>
                                            <td>: {{ $user->nik }} </td>
                                        </tr>
                                    </div>
                                    <div class="col-md-6">
                                        <tr>
                                            <td>Email</td>
                                            <td>: {{ $user->email }} </td>
                                        </tr>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <tr>
                                                <td>Jenis Kelamin</td>
                                                <td>: {{ $user->gender }} </td>
                                            </tr>
                                        </div>
                                        <div class="col-md-6">
                                            <tr>
                                                <td>No Telepon</td>
                                                <td>: {{ $user->phone }}</td>
                                            </tr>
                                        </div>
                                        <div class="col-md-6">
                                            <tr>
                                                <td>Departemen</td>
                                                <td>: {{ $user->department_id }}</td>
                                            </tr>
                                        </div>
                                        </div>
                                    <div class="form-group">
                                        <tr>
                                            <td>Alamat</td>
                                            <td>: {{ $user->addres }}</td>
                                        </tr>
                                    </div>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


@extends('dashboard.layouts.main')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-4">
                @if (session()->has('success'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <h1 class="h3">Applicant Detail</h1>

                @if ($employeeDetails)
                    <div class="card">
                        <div class="card-body">
                            @if ($employeeDetails->photo)
                                <img src="{{ asset('storage/' . $employeeDetails->photo) }}" alt="{{ $employeeDetails->fullname }}" class="img-fluid">
                            @else
                                <img src="https://source.unsplash.com/170x170?person" alt="Default avatar" class="img-fluid">
                            @endif

                            <h5 class="card-title">{{ $employeeDetails->fullname }}</h5>
                            <p class="card-text">Department: {{ $employeeDetails->department->name ?? 'No Department' }}</p>
                            <p class="card-text">Position: {{ $employeeDetails->position->name ?? 'No Position' }}</p>
                            <p class="card-text">Phone: {{ $employeeDetails->phone ?? 'No Phone' }}</p>
                            <p class="card-text">Address: {{ $employeeDetails->address ?? 'No Address' }}</p>
                            <p class="card-text">Hire Date: {{ $employeeDetails->hire_date ?? 'No Hire Date' }}</p>
                        </div>
                    </div>
                @else
                    <p>No employee detail found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
