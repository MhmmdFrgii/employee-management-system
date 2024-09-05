@extends('dashboard.layouts.main')

@section('content')
    <h3>Proyek Saya</h3>
    @forelse ($kanbanBoards as $data)
        <div class="card mb-4 shadow-md">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $data->project->name }}</h5>
                <a href="{{ route('kanban-boards.index', ['id' => $data->id]) }}" class="btn btn-primary btn-sm">Kanban</a>
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
                    <p class="text-center">Tidak ada tugas tersedia di proyek ini</p>
                @endforelse
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <!-- Button to Open the Modal -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#detailModal{{ $data->project->id }}">
                    Detail
                </button>
            </div>
        </div>

        <!-- Modal for Project Details -->
        <div class="modal fade" id="detailModal{{ $data->project->id }}" tabindex="-1" aria-labelledby="detailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Proyek: {{ $data->project->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Card for Project Details -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-building"></i> <strong>Nama Proyek:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data->project->name }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-file-text"></i> <strong>Deskripsi:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data->project->description }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-file-text"></i> <strong>Karyawan:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data->project->employee_details->pluck('name')->implode(', ') }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-cash-coin"></i> <strong>Harga:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        Rp {{ number_format($data->project->price, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-calendar-event"></i> <strong>Tanggal Mulai:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data->project->start_date }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-calendar-check"></i> <strong>Tanggal Selesai:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data->project->end_date }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-flag-fill"></i> <strong>Status:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ ucfirst($data->project->status) }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <i class="bi bi-calendar2-check-fill"></i> <strong>Selesai Pada:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data->project->completed_at ? date('d M Y', strtotime($data->project->completed_at)) : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-3">
            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                style="width: clamp(150px, 50vw, 300px);">
            <p class="mt-3">Tidak ada papan kanban yang tersedia</p>
        </div>
    @endforelse
@endsection
