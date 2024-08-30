@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <h1 class="h3">Project</h1>
                <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah Project
                    </button>
                    <form action="{{ route('projects.index') }}" method="GET">
                        <div class="d-flex gap-2">
                            <!-- Dropdown Filter Status -->
                            <div class="dropdown ms-2">
                                <button class="btn btn-secondary dropdown-toggle shadow" type="button" id="statusDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Status
                                </button>
                                <ul class="dropdown-menu mt-2" aria-labelledby="statusDropdown">
                                    <li>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="active"
                                                id="statusActive"
                                                {{ in_array('active', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusActive">
                                                Active
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" name="status[]"
                                                value="completed" id="statusCompleted"
                                                {{ in_array('completed', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusCompleted">
                                                Completed
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="position-relative">
                                <input type="text" name="search" class="form-control rounded shadow search-input"
                                    placeholder="Cari Projek..." value="{{ request('search') }}">
                                <a href="{{ route('projects.index') }}"
                                    class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                    style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                    X
                                </a>
                            </div>
                            <button type="submit" class="btn btn-secondary">Cari</button>
                        </div>
                    </form>
                </div>

                <div class="row">
                    @forelse ($projects as $project)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $project->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                                    <p class="card-text">
                                        <strong>Status:</strong> {{ ucfirst($project->status) }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Completed At:</strong>
                                        {{ $project->completed_at ? date('d M Y', strtotime($project->completed_at)) : '-' }}
                                    </p>
                                </div>
                                <div class="card-footer justify-content-between d-flex ">
                                    <button class="btn btn-info btn-sm item-start" data-bs-toggle="modal"
                                        data-bs-target="#completeModal{{ $project->id }}" type="button">Kanban</button>
                                    <div class="d-flex  gap-1">
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#completeModal{{ $project->id }}"
                                            type="button">Complete</button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $project->id }}" type="button">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#vertical-center-modal{{ $project->id }}"
                                            type="button">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('projects.partial.delete-modal')
                        @include('projects.partial.complete-modal')
                        @include('projects.partial.edit-modal')
                    @empty
                        <div class="col-12 text-center">
                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                                style="width: clamp(150px, 50vw, 300px);">
                            <p class="mt-3">No data available.</p>
                        </div>
                    @endforelse
                </div>

                {{ $projects->links() }}
            </div>
        </div>
    </div>

    @include('projects.partial.add-modal')
@endsection
