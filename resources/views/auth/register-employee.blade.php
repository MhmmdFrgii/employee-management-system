@extends('auth.layouts.main')

@section('content')
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed"
        data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100">
            <div class="position-relative z-index-5">
                <div class="row">
                    <div class="col-xl-5 col-xxl-5">
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
                    <div class="col-xl-7 col-xxl-7">
                        <div
                            class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
                            <div class="col-sm-8 col-md-8 col-xl-12">
                                <div class="card-body">
                                    <h2 class="mb-3 fs-7 fw-bolder">Pendaftaran Karyawan</h2>
                                    <form action="{{ route('register.employee') }}" class="mt-5" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <section>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="name"> Nama : <span class="danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" name="name" placeholder="Nama Karyawan"
                                                            value="{{ old('name') }}" />
                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email"> Email : <span class="danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="email" name="email" placeholder="Masukkan Email"
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
                                                        <label for="photo">Foto : <span class="danger">*</span></label>
                                                        <input type="file"
                                                            class="form-control @error('photo') is-invalid @enderror"
                                                            id="photo" name="photo" placeholder="Nomor Telepon"
                                                            value="{{ old('photo') }}" min="0" />

                                                        <p class="text-danger">*Foto Harus Berformat .jpg, .jpeg, atau .png
                                                        </p>
                                                        @error('photo')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone"> Nomor Telepon : <span
                                                            class="danger">*</span></label>
                                                    <input type="number"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone" placeholder="Nomor Telepon"
                                                        value="{{ old('phone') }}" min="0" />
                                                    @error('phone')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="gender">Jenis Kelamin : <span
                                                                class="danger">*</span></label>
                                                        <div class="form-check">
                                                            <input type="radio" id="male" name="gender"
                                                                value="male"
                                                                class="form-check-input @error('gender') is-invalid @enderror"
                                                                {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                            <label for="male" class="form-check-label">Laki-laki</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" id="female" name="gender"
                                                                value="female"
                                                                class="form-check-input @error('gender') is-invalid @enderror"
                                                                {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                            <label for="female" class="form-check-label">Perempuan</label>
                                                        </div>
                                                        @error('gender')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="address">Alamat : <span
                                                                class="danger">*</span></label>
                                                        <textarea type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                                            placeholder="Alamat">{{ old('address') }}</textarea>
                                                        @error('address')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                                </section>
                                <div class="d-flex justify-content-between">
                                    <a class="text-black" href="/login">Sudah punya akun? <span
                                            class="text-primary">Masuk</span></a>
                                    <button type="submit" class="btn btn-primary">Daftar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
