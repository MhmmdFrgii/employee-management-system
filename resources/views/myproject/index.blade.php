@extends('dashboard.layouts.main')

@section('content')
    <h1>My Project</h1>
    @forelse ($kanbanboard as $data)
        <div class="card mb-3">
            <div class="card-header">
                <h5>{{ $data->project->name }}</h5>
            </div>

            <div class="card-body p-0">
                @forelse ($data->kanbantasks as $item)
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center border">
                            <div class="d-flex align-items-center">
                                <span>{{ $item->title }}</span>
                            </div>

                            <div class="d-flex align-items-center">
                                @php
                                    $badgeClass = match ($item->status) {
                                        'todo' => 'badge bg-primary',
                                        'progress' => 'badge bg-warning',
                                        'done' => 'badge bg-success',
                                        default => 'badge bg-secondary',
                                    };
                                @endphp
                                <span class="{{ $badgeClass }} ms-2">{{ ucfirst($item->status) }}</span>
                                <span class="ms-5">{{ $item->date }}</span>
                            </div>
                        </li>
                    </ul>
                @empty
                    <div class="text-center py-3">
                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Tasks" class="img-fluid"
                            style="width: clamp(150px, 50vw, 300px);">
                        <p class="mt-3">No tasks available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="text-center py-3">
            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                style="width: clamp(150px, 50vw, 300px);">
            <p class="mt-3">No Kanban boards available.</p>
        </div>
    @endforelse
@endsection
