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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">Applicants</h1>
                    {{-- <form method="GET" action="{{ route('attendance.index') }}" class="mb-4">
                        <div class="row g-2">
                            <!-- Input Pencarian -->
                            <div class="col-md-9">
                                <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari Data..." value="{{ request('search') }}">
                            </div>
                            <!-- Tombol Cari -->
                            <div class="col-md-1">
                                <button class="btn btn-outline-secondary rounded shadow-sm" type="submit">Cari</button>
                            </div>
                        </div>
                    </form> --}}
                </div>

                <div class="row">
                    @forelse ($users as $user)
                        <div class="col-lg-4 col-md-6">
                            <div class="card justify-content-center align-items-center" style="width: 16rem;">
                                <div class="card-body d-flex flex-column text-center">
                                    {{-- Gunakan data gambar dari user atau default gambar jika tidak ada --}}
                                    @if ($user && $user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="avatar" class="rounded-1 img-fluid" width="170px" height="170px">
                                    @else
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="avatar" class="rounded-1 img-fluid" width="170px" height="170px">
                                    @endif

                                    <div class="card-body p-0 mt-2">
                                        <h5 class="card-title">{{ $user->name}}</h5>
                                        <p class="card-text">{{ $user->employeeDetail->department->name ?? 'No Department'}}</p>
                                    </div>
                                    <a href="{{ route('applicant.detail', $user->id) }}" class="btn btn-primary mt-3">Detail</a>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                    <div class="justify-content-end">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
