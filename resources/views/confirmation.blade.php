@extends('layouts.app')

@section('content')
    <div class="main">
        <!-- ***** Confirmation Pending Area Start ***** -->
        <section id="confirmation-pending" class="section confirmation-area style-two overflow-hidden ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <!-- Section Heading -->
                        <div class="section-heading text-center">
                            <span class="d-inline-block rounded-pill shadow-sm fw-5 px-4 py-2 mb-3">
                                <i class="fas fa-hourglass-half text-warning mr-1"></i>
                                <span class="text-warning">Konfirmasi</span>
                                Tertunda
                            </span>
                            <h2>Akun Anda Sedang Menunggu Konfirmasi</h2>
                            <p class="d-none d-sm-block mt-4">Terima kasih telah mendaftar. Akun Anda sedang dalam
                                proses konfirmasi oleh administrator. Anda akan menerima notifikasi setelah akun Anda
                                disetujui.</p>
                            <p class="d-block d-sm-none mt-4">Terima kasih telah mendaftar. Akun Anda sedang dalam
                                proses konfirmasi oleh administrator. Anda akan menerima notifikasi setelah akun Anda
                                disetujui.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Confirmation Pending Area End ***** -->
    </div>
@endsection
