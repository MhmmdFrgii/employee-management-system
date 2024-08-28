@extends('dashboard.layouts.main')

@section('content')
    @foreach ($kanbanboard as $data)
        <div class="card">
            <div class="card-header">
                <!-- Mengubah 'projects' menjadi 'project' -->
                <h5>{{ $data->project->name }}</h5>
            </div>

            <div class="card-body p-0">
                @foreach ($data->kanbantasks as $item)
                    <ul class="list-group list-group-flush">
                        <!-- Task Item -->
                        <li class="list-group-item d-flex justify-content-between align-items-center border">
                            <!-- Tugas -->
                            <div class="d-flex align-items-center">
                                <span>{{ $item->title }}</span>
                            </div>

                            <!-- Tanggal dan Status -->
                            <div class="d-flex align-items-center">
                                <!-- Badge Status -->
                                @php
                                    $badgeClass = match ($item->status) {
                                        'todo' => 'badge bg-primary',
                                        'progress' => 'badge bg-warning',
                                        'done' => 'badge bg-success',
                                        default => 'badge bg-secondary',
                                    };
                                @endphp
                                <span class="{{ $badgeClass }} ms-2">{{ ucfirst($item->status) }}</span>

                                <!-- Tanggal dengan Margin Kiri -->
                                <span class="ms-5">{{ $item->date }}</span>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
