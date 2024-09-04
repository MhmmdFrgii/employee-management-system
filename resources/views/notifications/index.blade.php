@extends('dashboard.layouts.main')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Kotak Masuk Notifikasi</h1>
        <div class="row">
            <div class="col-md-12">
                <!-- Daftar Notifikasi -->
                <div class="list-group">
                    @forelse ($notifications as $notification)
                        <!-- Notifikasi -->
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
                                <!-- Tombol Hapus -->
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
                    @endforelse
                </div>
                {{-- <!-- Pagination -->
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .list-group-item {
            display: flex;
            flex-direction: column;
            padding: 10px;
            /* Menambahkan padding untuk tampilan lebih baik */
            font-size: 14px;
            /* Ukuran font untuk konten utama */
            border: none;
            /* Menghilangkan border untuk tampilan bersih */
        }

        .list-group-item .w-100 {
            width: 100%;
            /* Memastikan div mengambil lebar penuh */
        }

        .list-group-item h5,
        .list-group-item p {
            margin: 0;
            /* Menghapus margin untuk mengurangi ruang kosong */
        }

        .badge {
            font-size: 12px;
            /* Menyesuaikan ukuran font badge */
        }

        small {
            font-size: 12px;
            /* Ukuran font untuk tanggal */
            color: #6c757d;
            /* Warna teks tanggal */
        }

        .container-fluid {
            padding: 0;
            /* Menghapus padding untuk tampilan penuh */
        }

        .list-group-item:last-child {
            margin-bottom: 0;
            /* Menghapus margin bawah pada item terakhir */
        }

        .list-group-item .d-flex {
            margin-bottom: 0;
            /* Menghapus margin bawah untuk tampilan bersih */
        }
    </style>
@endpush
