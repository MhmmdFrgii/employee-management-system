@extends('auth.layouts.main')
@section('content')   
    <style>
        .form-control-custom {
            width: 100%; /* Lebar penuh */
            padding: 0.75rem 1rem; /* Padding yang nyaman */
            border-radius: 0.375rem; /* Border yang konsisten dengan tombol */
            border: 1px solid #ced4da; /* Warna border default */
            transition: border-color 0.3s, box-shadow 0.3s; /* Transisi halus saat fokus */
        }
        .form-control-custom:focus {
            border-color: #5c6bc0; /* Border saat input fokus */
            box-shadow: 0 0 0 0.2rem rgba(92, 107, 192, 0.25); /* Highlight saat fokus */
        }
    </style>
    
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-6 col-lg-5 col-xxl-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="index-2.html" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                                    <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg" width="180" alt="">
                                </a>
    
                                <div class="position-relative text-center my-4">
                                    <p class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">
                                        Lupa kata sandi? <br> Cukup beri tahu kami alamat email anda
                                    </p>
                                </div>
    
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-3 w-100">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input type="email" 
                                            class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email') }}" 
                                            aria-describedby="emailHelp" required autofocus>
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
    
                                    <div class="flex items-center justify-end mt-2">
                                        <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
                                            {{ __('Email Password Reset Link') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection