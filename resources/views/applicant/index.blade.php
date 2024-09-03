@extends('dashboard.layouts.main')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3">Daftar Pelamar</h1>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 gap-4">
                        <input type="text" readonly value="{{ $company->company_code }}" class="form-control">
                        <form action="{{ route('companies.reset', $company->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <button type="submit" class="btn btn-primary">Reset</button>
                        </form>
                    </div>

                    <div class="row">
                        @forelse ($applicants as $applicant)
                            <div class="col-lg-4 col-md-6">
                                <div class="card justify-content-center align-items-center" style="width: 16rem;">
                                    <div class="card-body d-flex flex-column text-center">
                                        {{-- Gunakan data gambar dari user atau default gambar jika tidak ada --}}
                                        <img src="{{ $applicant->photo ? asset('storage/' . $applicant->photo) : '../../dist/images/profile/user-1.jpg' }}"
                                        alt="avatar"
                                        class="rounded-1 img-fluid"
                                        style="width: 200px; height: 150px; object-fit: cover;">


                                        <div class="card-body p-0 mt-2">
                                            <h5 class="card-title">{{ $applicant->name }}</h5>
                                            {{-- <p class="card-text">
                                                {{ $applicant->name ?? 'No Department' }}</p> --}}
                                        </div>
                                        <a href="{{ route('applicants.detail', $applicant->id) }}"
                                            class="btn btn-primary mt-3">Rincian</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div colspan="7" class="text-center">
                                <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                                    style="width: clamp(150px, 50vw, 300px);">
                                <p class="mt-3">Tidak ada data tersedia</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="justify-content-end">
                        {{ $applicants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
