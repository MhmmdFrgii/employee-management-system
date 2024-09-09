@extends('auth.layouts.main')

@section('content')
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed"
        data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100">
            <div class="position-relative z-index-5">
                <div class="row">
                    <div class="col-xl-6 col-xxl-6">
                        <a href="index-2.html" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg"
                                width="180" alt="">
                        </a>
                        <div class="d-none d-xl-flex align-items-center justify-content-center"
                            style="height: calc(100vh - 80px);">
                            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/login-security.svg"
                                alt="" class="img-fluid" width="500">
                        </div>
                    </div>
                    <div class="col-xl-6 col-xxl-6">

                        <div
                            class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
                            <div class="col-sm-8 col-md-8 col-xl-12">

                                <div class="card-body  wizard-content">
                                    <h4 class="card-title">Undangan Karyawan</h4>
                                    <form action="{{ route('store.invite') }}" class="validation-wizard wizard-circle mt-5"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="invite" value="{{ request('invite') }}">
                                        <!-- Step 1 -->
                                        <h6>Data Diri</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="name">Nama Lengkap<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" name="name" placeholder="Nama Lengkap"
                                                            value="{{ old('name') }}" />
                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="phone">No. Telepon<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            id="phone" name="phone" placeholder="No. Telepon"
                                                            value="{{ old('phone') }}" />
                                                        @error('phone')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="photo">Foto<span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="photo"
                                                            name="photo" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="gender">Jenis Kelamin<span
                                                                class="text-danger">*</span></label>
                                                        <div>
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    class="form-check-input @error('gender') is-invalid @enderror"
                                                                    type="radio" name="gender" id="male"
                                                                    value="male">
                                                                <label class="form-check-label"
                                                                    for="male">Laki-laki</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    class="form-check-input @error('gender') is-invalid @enderror"
                                                                    type="radio" name="gender" id="female"
                                                                    value="female">
                                                                <label class="form-check-label"
                                                                    for="female">Perempuan</label>
                                                            </div>
                                                        </div>
                                                        @error('gender')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                        <h6>Buat Akun</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="email" name="email" placeholder="Email"
                                                            value="{{ old('email') }}" />
                                                        @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password">Password</label>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password" placeholder="Password" />
                                                        @error('password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password_confirmation">Konfirmasi Password</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control" id="password_confirmation"
                                                            placeholder="Konfirmasi Password" />
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </form>
                                    <a class="text-black" href="/login">Sudah punya akun? <span
                                            class="text-primary">Masuk</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
