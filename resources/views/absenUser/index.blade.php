@extends('dashboard.layouts.main')
@section('content')
    <div class="row">
        <div class="col d-flex align-items-stretch">
            <div class="card w-100 overflow-hidden shadow-none" style="background-color: #cacae8;">
                <div class="card-body position-relative">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center mb-7">

                                <h5 class="fw-semibold mb-0 fs-5">Kehadiran yang Baik Membangun Kepercayaan
                                    dan Meningkatkan Produktivitas
                                </h5>
                            </div>

                        </div>
                        <div class="col-sm-5">
                            <div class="welcome-bg-img mb-n7 text-end">
                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/welcome-bg.svg"
                                    alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-info">
                <a href="/siswa-offline/absensi" class="stretched-link"></a>
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
                            <h3>0 Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-success">
                <a href="/siswa-offline/absensi?status=attend" class="stretched-link"></a>
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
                            <h3>0 Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-warning">
                <a href="/siswa-offline/absensi?status=absent" class="stretched-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-warning text-light rounded d-flex align-items-center justify-content-center p-2">
                                <i class="fa fa-home"></i>
                            </div>
                        </div>

                        <div class="col-9">
                            <div>
                                <h6 class="card-subtitle mb-0">Total Izin &amp; Sakit</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="d-flex justify-content-between">
                            <h3>0 Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card mb-4 bg-danger">
                <a href="/siswa-offline/absensi?status=alpha" class="stretched-link"></a>
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
                            <h3>0 Kali</h3>
                            <span class="ml-auto">Absensi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ABSENSII --}}
    <div class="d-flex justify-content-end mb-3 gap-2">
        <form action="{{ route('absensi.store') }}" method="post">
            @csrf
            <button button type="submit" class="btn btn-success">Absen</button>
        </form>
        <button type="button" class="btn btn-primary">
            Buat Izin
        </button>
    </div>
    {{--  --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Masuk</th>
                <th>Pulang</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>User</td>
                <td>27 Agustus 2024</td>
                <td>
                    <span class="badge bg-success">Masuk</span>
                </td>
                <td>07:20</td>
                <td>05:15</td>
            </tr>
        </tbody>
    </table>
@endsection
