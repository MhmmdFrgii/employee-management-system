{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

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
                                    <form action="#" class="validation-wizard wizard-circle mt-5">
                                        <!-- Step 1 -->
                                        <h6>Step 1</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="fullname"> Nama Lengkap : <span class="danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control " id="fullname"
                                                            name="fullname" placeholder="Nama Lengkap" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="nik"> NIK <span class="danger">*</span>
                                                        </label>
                                                        <input type="number" class="form-control " id="nik"
                                                            name="nik" placeholder="NIK" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="image"> Foto : <span class="danger">*</span>
                                                        </label>
                                                        <input type="file" class="form-control" id="image"
                                                            name="image" />
                                                        <p class="text-danger">*Foto Harus Berformat .jpg, .jpeg, atau .png
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="cv"> CV : <span class="danger">*</span>
                                                        </label>
                                                        <input type="file" class="form-control" id="cv"
                                                            name="cv" />
                                                        <p class="text-danger">*CV Harus Berformat .jpg, .jpeg, atau .png
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="department"> Pilih Departemen : <span class="danger">*</span>
                                                        </label>
                                                        <select class="form-select " name="department_id" id="department"
                                                            name="location">
                                                            <option disabled selected>Pilih Departemen</option>
                                                            <option value="1" >Tes Departemen</option>
                                                            @foreach ($departments as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="phone">Nomor Telepon :  <span class="danger">*</span></label>
                                                        <input type="tel" class="form-control" name="phone"
                                                            id="phone" placeholder="Nomor Telepon"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="address1">Alamat : <span class="danger">*</span></label>
                                                        <input type="text" class="form-control" id="address1"
                                                            name="address" placeholder="Alamat" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="additionalInfo1">Additional Info :</label>
                                                        <textarea name="additionalInfo" id="additionalInfo1" rows="4" class="form-control"></textarea>
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
                                                        <label for="username">Username</label>
                                                        <input type="text" class="form-control required"
                                                            id="username" name="name" placeholder="Username" value="{{ old('name') }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control required"
                                                            id="email" name="email" placeholder="Email" value="{{ old('email') }}"/>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password">Password </label>
                                                        <input type="password" class="form-control required"
                                                            id="password" name="password" placeholder="Password"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password_confirmation">Password Confirmation</label>
                                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Password Confirmation" />
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
