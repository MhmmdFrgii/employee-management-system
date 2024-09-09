@extends('dashboard.layouts.main')

@section('content')


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <h1 class="h3">Proyek</h1>
                <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah Proyek
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
                                        <label for="statusActive" class="w-100">
                                            <div class="form-check ms-4">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="active" id="statusActive"
                                                    {{ in_array('active', request('status', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label" for="statusActive">
                                                    Aktif
                                                </span>
                                            </div>
                                        </label>
                                        <label for="statusCompleted" class="w-100">
                                            <div class="form-check ms-4">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="completed" id="statusCompleted"
                                                    {{ in_array('completed', request('status', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label" for="statusCompleted">
                                                    Selesai
                                                </span>
                                            </div>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="position-relative">
                                <input type="text" name="search" class="form-control rounded shadow search-input"
                                    placeholder="Cari Proyek..." value="{{ request('search') }}">
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
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title m-0">{{ $project->name }}</h4>
                                    <span class="badge
                                        @if($project->status === 'completed') bg-success
                                        @elseif($project->status === 'active') bg-primary
                                        @else bg-secondary
                                        @endif me-1">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                                    <p class="card-text">
                                    <div class="d-flex flex-wrap gap-1">
                                        <strong>Departemen:</strong>
                                        @if ($project->employee_details->isNotEmpty())
                                                @foreach ($project->employee_details->unique('department_id') as $employee_detail)
                                                    <span class="badge bg-primary me-1">
                                                        {{ $employee_detail->department->name ?? 'Tidak Diketahui' }}
                                                    </span>
                                                @endforeach
                                        @else
                                            <span>-</span>
                                        @endif
                                    </div>
                                    </p>
                                    <p class="card-text">
                                        <strong>Selesai pada: </strong>
                                        {{ $project->completed_at ? date('d M Y', strtotime($project->completed_at)) : '-' }}
                                    </p>
                                </div>
                                <div class="card-footer justify-content-between d-flex ">
                                    <div class="d-flex  gap-1">
                                        @if ($project->status !== 'completed')
                                            @if ($project->employee_details->isNotEmpty())
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#completeModal{{ $project->id }}"
                                                    type="button"><i class='bx bx-check-square'></i></button>
                                            @endif
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $project->id }}" type="button"><i class='bx bx-edit-alt' ></i></button>

                                        @endif
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#vertical-center-modal{{ $project->id }}"
                                            type="button"><i class='bx bx-trash' ></i></button>
                                    </div>
                                    @if ($project->kanban_board)
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('kanban-boards.index', ['id' => $project->kanban_board->id]) }}">Kanban</a>
                                    @endif

                                </div>
                            </div>
                        </div>

                        @if ($project->employee_details->isNotEmpty())
                            @include('projects.partial.complete-modal')
                        @endif

                        @include('projects.partial.delete-modal')
                        @include('projects.partial.edit-modal')
                    @empty
                        <div class="col-12
                                        text-center">
                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                                style="width: clamp(150px, 50vw, 300px);">
                            <p class="mt-3">Tidak ada data tersedia</p>
                        </div>
                    @endforelse
                </div>

                {{ $projects->links() }}
            </div>
        </div>
    </div>

    @include('projects.partial.add-modal')

    <script>
        $(document).ready(function() {
    // Event ketika tombol Edit ditekan
    $('.btn-edit').on('click', function() {
        var projectId = $(this).data('id');

        // Buka modal edit
        $('#editModal').modal('show');
            });
        });

    </script>

@endsection
