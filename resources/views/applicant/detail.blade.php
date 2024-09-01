@extends('dashboard.layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-4">
                    <h1 class="h3 mb-4">Applicant Detail</h1>

                    @if ($applicant)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex flex-wrap align-items-center gap-4">
                                @php
                                    $photoPath = $applicant->photo ?? 'assets/images/no-profile.jpeg';
                                @endphp

                                <div class="flex-shrink-0">
                                    <img src="{{ Storage::exists($photoPath) ? asset('storage/' . $photoPath) : asset('assets/images/no-profile.jpeg') }}"
                                        alt="avatar" class="rounded-circle img-fluid"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>

                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-3">{{ $applicant->name }}</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="card-text mb-1"><strong>Email:</strong>
                                                {{ $applicant->email ?? 'No Email' }}</p>
                                            <p class="card-text mb-1"><strong>Phone:</strong>
                                                {{ $applicant->phone ?? 'No Phone' }}</p>
                                            <p class="card-text mb-1"><strong>Gender:</strong>
                                                {{ $applicant->gender ?? 'No Gender' }}</p>
                                            <p class="card-text mb-1"><strong>Address:</strong>
                                                {{ $applicant->address ?? 'No Address' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Curriculum Vitae (CV)</h5>
                                            @if ($applicant->cv)
                                                <a href="{{ asset('storage/' . $applicant->cv) }}"
                                                    class="btn btn-secondary mt-2" target="_blank">
                                                    View CV
                                                </a>
                                            @else
                                                <p class="text-muted mt-2">No CV uploaded.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center d-flex justify-content-center gap-3">
                            <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-primary">Approve</button>
                            </form>
                            <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger" disabled>Reject</button>
                            </form>
                        </div>
                    @else
                        <p class="text-muted">No employee detail found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
