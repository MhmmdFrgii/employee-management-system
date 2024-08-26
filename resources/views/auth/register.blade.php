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
                                    <p class="card-subtitle mb-3"> You can us the validation like what we did </p>
                                    <form action="{{ route('register') }}" class="validation-wizard wizard-circle mt-5"
                                        id="registerForm">
                                        <!-- Step 1 -->
                                        <h6>Step 1</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="fullname"> Nama Lengkap : <span class="danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control " id="fullname"
                                                            name="fullname" placeholder="Nama Lengkap" required />
                                                        @error('fullname')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="nik"> NIK <span class="danger">*</span>
                                                        </label>
                                                        <input type="number" class="form-control " id="nik"
                                                            name="nik" placeholder="NIK" />
                                                        @error('nik')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="photo"> Foto : <span class="danger">*</span>
                                                        </label>
                                                        <input type="file" class="form-control" id="photo"
                                                            name="photo" />
                                                        @error('photo')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="cv"> CV : <span class="danger">*</span>
                                                        </label>
                                                        <input type="file" class="form-control" id="cv"
                                                            name="cv" />
                                                        @error('cv')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="department"> Pilih Departemen : <span
                                                                class="danger">*</span>
                                                        </label>
                                                        <select class="form-select " name="department_id" id="department">
                                                            <option disabled selected>Pilih Departemen</option>
                                                            <option value="1">Tes Departemen</option>
                                                            @foreach ($departments as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
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
                                                        <label for="phone">Nomor Telepon : <span
                                                                class="danger">*</span></label>
                                                        <input type="tel" class="form-control" name="phone"
                                                            id="phone" placeholder="Nomor Telepon" />
                                                        @error('phone')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="gender">Jenis Kelamin : <span
                                                            class="danger">*</span></label>
                                                    <div class="form-check">
                                                        <input type="radio" id="male" name="gender" value="male"
                                                            class="form-check-input"
                                                            {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                        <label for="male" class="form-check-label">Laki-laki</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="radio" id="female" name="gender"
                                                            value="female" class="form-check-input"
                                                            {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                        <label for="female" class="form-check-label">Perempuan</label>
                                                    </div>
                                                    @error('gender')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="address">Alamat : <span
                                                                class="danger">*</span></label>
                                                        <textarea type="text" class="form-control" id="address" name="address" placeholder="Alamat"></textarea>
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
                                                        <input type="text" class="form-control required"
                                                            id="name" name="name" placeholder="Username"
                                                            value="{{ old('name') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control required"
                                                            id="email" name="email" placeholder="Email"
                                                            value="{{ old('email') }}" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password">Password </label>
                                                        <input type="password" class="form-control required"
                                                            id="password" name="password" placeholder="Password" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password_confirmation">Password Confirmation</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control" id="password_confirmation"
                                                            placeholder="Password Confirmation" />
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
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
