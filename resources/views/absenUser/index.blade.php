@extends('dashboard.layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-light-primary">
                <a href="" class="stretched-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-primary text-light rounded d-flex align-items-center justify-content-center p-2">
                                <i class='fas fa-paperclip'></i>
                            </div>
                        </div>

                        <div class="col-9">
                            <div>
                                <h6 class="card-subtitle mb-0">Total Absensi</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="d-flex justify-content-between">
                            <h3>{{ $total_attendance }} Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-light-success">
                <a href="" class="stretched-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-success text-light rounded d-flex align-items-center justify-content-center p-2">
                                <i class='fas fa-user-circle'></i>
                            </div>
                        </div>

                        <div class="col-9">
                            <div>
                                <h6 class="card-subtitle mb-0">Total Hadir</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="d-flex justify-content-between">
                            <h3>{{ $total_present }} Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-light-warning">
                <a href="" class="stretched-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-warning text-light rounded d-flex align-items-center justify-content-center p-2">
                                <i class="fa fa-home"></i>
                            </div>
                        </div>

                        <div class="col-9">
                            <div>
                                <h6 class="card-subtitle mb-0">Total Izin &amp;</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="d-flex justify-content-between">
                            <h3>{{ $total_absent }} Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-light-danger">
                <a href="" class="stretched-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-danger text-light rounded d-flex align-items-center justify-content-center p-2">
                                <i class='fas fa-ban'></i>
                            </div>
                        </div>

                        <div class="col-9">
                            <div>
                                <h6 class="card-subtitle mb-0">Total Alpha</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="d-flex justify-content-between">
                            <h3>{{ $total_alpha }} Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ABSENSII --}}
    <div class="d-flex justify-content-end mb-3 gap-2">
        <form action="{{ route('attendance.mark', ['route' => 'attendance.user']) }}" method="post">
            @csrf
            <button button type="submit" class="btn btn-success">Absen</button>
        </form>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Buat Izin
        </button>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendence)
                <tr>
                    <td>{{ $attendence->employee_detail->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendence->date)->translatedFormat('d F Y') }}</td>
                    <td>
                        @if ($attendence->status == 'present')
                            <span class="badge bg-success">masuk</span>
                        @elseif($attendence->status == 'late')
                            <span class="badge bg-danger">telat</span>
                        @elseif($attendence->status == 'absent')
                            <span class="badge bg-warning">izin</span>
                        @endif
                    </td>
                    <td>
                        {{ $attendence->created_at->format('H:i') }}
                    </td>
                </tr>
        </tbody>
        @endforeach
    </table>

    @include('absenUser.partial.add-modal')
@endsection
