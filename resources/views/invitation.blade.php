@extends('layouts.app')

@section('content')
<section id="invitation-form" class="section invitation-area ptb_50 ">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="invitation-form p-5 text-center bg-white rounded shadow">
                    <h3 class="text-dark mb-4">Masukkan Kode Undangan atau Kode Lamaran</h3>

                    <!-- Form Kode Lamaran -->
                    <form action="{{ route('create.applicant') }}">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control @error('applicant') is-invalid @enderror"
                                id="applicant" name="applicant" placeholder="Kode Lamaran">
                            @error('applicant')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Lanjutkan dengan Kode Lamaran</button>
                    </form>

                    <div class="my-4">
                        <span class="text-muted">Atau</span>
                    </div>

                    <!-- Form Kode Undangan -->
                    <form action="{{ route('create.invite') }}">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control @error('invite') is-invalid @enderror"
                                id="invite" name="invite" value="{{ request('invite') }}" placeholder="Kode Undangan">
                            @error('invite')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Lanjutkan dengan Kode Undangan</button>
                    </form>

                    <!-- Tombol Kembali -->
                    <div class="mt-4">
                        <a href="javascript:history.back()" class="btn btn-danger btn-block">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
