@extends('dashboard.layouts.main')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Kotak Masuk Notifikasi</h1>
        <div class="row">
            <div class="col-md-12">
                <!-- Daftar Notifikasi -->
                <div class="list-group">
                    @forelse ($notifications as $notification)
                        <div
                            class="list-group-item list-group-item-action d-flex flex-column align-items-start mb-3 border border-{{ $notification->type }}">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1" style="font-size: 14px;">{{ $notification->title }}</h5>
                                    <p class="mb-1" style="font-size: 12px;">
                                        {{ $notification->message }}
                                    </p>
                                    <small class="text-muted"
                                        style="font-size: 12px;">{{ $notification->created_at }}</small>
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
                        <!-- Tandai sebagai dibaca -->
                        @php
                            $notification->markAsRead();
                        @endphp
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
