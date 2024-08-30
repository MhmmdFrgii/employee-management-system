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
                                    <form action="{{ route('register') }}" class="validation-wizard wizard-circle mt-5"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Step 1 -->
                                        <h6>Step 1</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="company_name"> Nama Perusahaan : <span
                                                                class="danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('company_name') is-invalid @enderror"
                                                            id="company_name" name="company_name"
                                                            placeholder="Nama Perusahaan"
                                                            value="{{ old('company_name') }}" />
                                                        @error('company_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="company_address">Alamat : <span
                                                                class="danger">*</span></label>
                                                        <textarea type="text" class="form-control @error('company_address') is-invalid @enderror" id="company_address"
                                                            name="company_address" placeholder="Alamat Kantor">{{ old('company_address') }}</textarea>
                                                        @error('company_address')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="contact_email">Contact Email : <span
                                                                class="danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('contact_email') is-invalid @enderror"
                                                            id="contact_email" name="contact_email"
                                                            placeholder="Email Perusahaan"
                                                            value="{{ old('contact_email') }}" />
                                                        @error('contact_email')
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
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" name="name" placeholder="Username"
                                                            value="{{ old('name') }}" />
                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
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
                                                        <label for="password_confirmation">Password Confirmation</label>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control" id="password_confirmation"
                                                            placeholder="Password Confirmation" />
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
