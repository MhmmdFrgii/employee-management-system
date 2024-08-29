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
                                <div class="card-body wizard-content">
                                    <h4 class="card-title">Step wizard with validation</h4>
                                    <form action="{{ route('register') }}" class="validation-wizard wizard-circle mt-5" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Step 1 -->
                                        <h6>Step 1</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="fullname"> Nama Lengkap : <span class="danger">*</span></label>
                                                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname"
                                                            name="fullname" placeholder="Nama Lengkap" value="{{ old('fullname') }}" />
                                                        @error('fullname')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="nik"> NIK <span class="danger">*</span></label>
                                                        <input type="number" class="form-control @error('nik') is-invalid @enderror" id="nik"
                                                            name="nik" placeholder="NIK" value="{{ old('nik') }}" />
                                                        @error('nik')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="photo"> Foto : <span class="danger">*</span></label>
                                                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" />
                                                        <p class="text-danger">*Foto Harus Berformat .jpg, .jpeg, atau .png</p>
                                                        @error('photo')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="cv"> CV : <span class="danger">*</span></label>
                                                        <input type="file" class="form-control @error('cv') is-invalid @enderror" id="cv" name="cv" />
                                                        <p class="text-danger">*CV Harus Berformat .jpg, .jpeg, atau .png</p>
                                                        @error('cv')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="department"> Pilih Departemen : <span class="danger">*</span></label>
                                                        <select class="form-select @error('department_id') is-invalid @enderror" name="department_id" id="department">
                                                            <option disabled selected>Pilih Departemen</option>
                                                            @foreach ($departments as $item)
                                                                <option value="{{ $item->id }}" {{ old('department_id') == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('department_id')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="phone">Nomor Telepon : <span class="danger">*</span></label>
                                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                            id="phone" placeholder="Nomor Telepon" value="{{ old('phone') }}" />
                                                        @error('phone')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="gender">Jenis Kelamin : <span class="danger">*</span></label>
                                                    <div class="form-check">
                                                        <input type="radio" id="male" name="gender" value="male" class="form-check-input"
                                                            {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                        <label for="male" class="form-check-label">Laki-laki</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="radio" id="female" name="gender" value="female" class="form-check-input"
                                                            {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                        <label for="female" class="form-check-label">Perempuan</label>
                                                    </div>
                                                    @error('gender')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="address">Alamat : <span class="danger">*</span></label>
                                                        <textarea type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Alamat">{{ old('address') }}</textarea>
                                                        @error('address')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- Step 2 -->
                                        <h6>Step 2</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="name">Username</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                                            name="name" placeholder="Username" value="{{ old('name') }}" />
                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                                            name="email" placeholder="Email" value="{{ old('email') }}" />
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
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password" placeholder="Password" />
                                                        @error('password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password_confirmation">Password Confirmation</label>
                                                        <input type="password" name="password_confirmation" class="form-control"
                                                            id="password_confirmation" placeholder="Password Confirmation" />
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </form>
                                    <a class="text-black" href="/login">Sudah punya akun? <span class="text-primary">Masuk</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
