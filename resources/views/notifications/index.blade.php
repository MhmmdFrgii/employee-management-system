@extends('dashboard.layouts.main')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Kotak Masuk Notifikasi</h1>
        <div class="row">
            <div class="col-md-12">
                <!-- Daftar Notifikasi -->
                <div class="list-group">
                    @forelse ($notifications as $notification)
                        @php
                            // Tentukan border color berdasarkan type notifikasi
                            switch ($notification->data['type']) {
                                case 'info':
                                    $borderColor = 'primary';
                                    break;
                                case 'success':
                                    $borderColor = 'success';
                                    break;
                                case 'warning':
                                    $borderColor = 'warning';
                                    break;
                                case 'danger':
                                    $borderColor = 'danger';
                                    break;
                                default:
                                    $borderColor = 'secondary'; // default jika tipe tidak terdefinisi
                            }
                        @endphp
                        <div class="list-group-item list-group-item-action d-flex flex-column align-items-start mb-3 border border-{{ $borderColor }}"
                            onclick="window.location.href='{{ $notification->data['url'] }}'">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1" style="font-size: 14px;">{{ $notification->data['title'] }}</h5>
                                    <p class="mb-1" style="font-size: 12px;">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <small class="text-muted"
                                        style="font-size: 12px;">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                    style="margin-left: auto;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item d-flex justify-content-center align-items-center">
                            <p class="mb-0" style="font-size: 14px;">Tidak ada notifikasi saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
