@extends('dashboard.layouts.main')

@section('content')

<div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
    <div class="row g-2 mt-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="row g-2">
                <h3 class="mx-1">Proyek</h3>
            </div>
        </div>
        @include('projects.partial.filter')
    </div>
</div>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
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
