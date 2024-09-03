@extends('dashboard.layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-4">
                    <h1 class="h3 mb-4"><span><a class="text-black" href="{{ route('applicants.index') }}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg></a></span> Detail Pelamar</h1>

                    @if ($applicant)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex flex-wrap align-items-center gap-4">
                                @php
                                    $photoPath = $applicant->photo ?? 'assets/images/no-profile.jpeg';
                                @endphp

                                <div class="flex-shrink-0">
                                    <img src="{{ $applicant->photo ? asset('storage/' . $applicant->photo) : '../../dist/images/profile/user-1.jpg' }}"
                                        alt="avatar" class="rounded-circle img-fluid"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>

                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-3">{{ $applicant->name }}</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="card-text mb-1"><strong>Email:</strong>
                                                {{ $applicant->email ?? 'No Email' }}</p>
                                            <p class="card-text mb-1"><strong>No Hp:</strong>
                                                {{ $applicant->phone ?? 'No Phone' }}</p>
                                            <p class="card-text mb-1"><strong>Jenis Kelamin:</strong>
                                                {{ $applicant->gender ?? 'No Gender' }}</p>
                                            <p class="card-text mb-1"><strong>Alamat:</strong>
                                                {{ $applicant->address ?? 'No Address' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Curriculum Vitae (CV)</h5>
                                            @if ($applicant->cv)
                                                <a href="{{ asset('storage/' . $applicant->cv) }}"
                                                    class="btn btn-secondary mt-2" target="_blank">
                                                    Lihat CV
                                                </a>
                                            @else
                                                <p class="text-muted mt-2">No CV uploaded.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Include Modal -->
                        @include('applicant.partial.modal-aprrove')
                        @include('applicant.partial.modal-delete')

                        <div class="mt-4 text-center d-flex justify-content-center gap-3">
                            <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="status" value="approved">
                                <a class="btn btn-primary btn-md d-flex align-items-center justify-content-center fw-semibold"
                                href="#" data-id="{{ $applicant->id }}" data-bs-toggle="modal" data-bs-target="#ModalApproved">
                                 Lanjut
                             </a>
                            </form>
                            <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="status" value="rejected">
                            </form>
                            <button class="btn btn-danger btn-md" data-bs-toggle="modal"
                            data-bs-target="#ModalDelete{{ $applicant->id }}"
                            type="button">Tolak</button>
                        </div>
                    @else
                        <p class="text-muted">No employee detail found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
