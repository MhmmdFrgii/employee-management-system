@extends('layouts.app')

@section('title', 'Landing Page ')
@section('content')
    <div class="main">
        <!-- ***** Header Start ***** -->
        <header class="navbar navbar-sticky navbar-expand-lg navbar-dark">
            <div class="container position-relative">
                <a class="navbar-brand" href="/">
                    <img class="navbar-brand-regular" 
                        src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/light-logo.svg"
                        alt="brand-logo">
                    <img class="navbar-brand-sticky"
                        src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg"
                        alt="sticky brand-logo">
                </a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-inner">
                    <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <nav>
                        <ul class="navbar-nav" id="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#features">Fitur</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#workflow">Alur Kerja</a>
                            </li>

                            @auth

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        EMS
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        @if (Auth::user()->hasRole('manager'))
                                            <li><a class="dropdown-item" href="{{ route('manager.dashboard') }}">Dashboard</a>
                                            </li>
                                        @else
                                            <li><a class="dropdown-item" href="{{ route('employee.dashboard') }}">Dashboard</a>
                                            </li>
                                        @endif
                                        <form id="logout" class="d-none" action="{{ route('logout') }}" method="post">
                                            @csrf
                                        </form>

                                        <li><button class="dropdown-item border-0"
                                                onclick="document.getElementById('logout').submit();">Logout</button></li>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                                </li>
                            @endauth
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- ***** Header End ***** -->

        <!-- ***** Welcome Area Start ***** -->
        <section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Welcome Intro Start -->
                    <div class="col-12 col-md-7 col-lg-6">
                        <div class="welcome-intro">
                            <h1 class="text-white">Manajemen Karyawan Anda dengan EMS</h1>
                            <p class="text-white my-4">EMS (Employee Management System) adalah aplikasi inovatif untuk
                                mengelola karyawan secara efisien. Dapatkan wawasan mendalam tentang kinerja, absensi,
                                dan pengembangan karyawan Anda.</p>
                        </div>
                        <!-- Invitation Code Form -->
                        <div class="mt-4">
                            <form action="{{ route('apply_or_invite') }}" method="GET" class="d-flex">
                                @csrf
                                <input type="text" name="code" class="form-control me-2 mr-3"
                                    placeholder="Masukkan Kode Undangan atau Rekrut">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                        @error('code')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-5 col-lg-6">
                        <!-- Welcome Thumb -->
                        <div class="welcome-thumb mx-auto" data-aos="fade-left" data-aos-delay="500"
                            data-aos-duration="1000">
                            <img class="img-fluid"
                                src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/landingpage/dist/images/backgrounds/business-woman-checking-her-mail.png"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shape Bottom -->
            <div class="shape-bottom">
                <svg viewBox="0 0 1920 310" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" class="svg replaced-svg">
                    <title>sApp Shape</title>
                    <desc>Created with Sketch</desc>
                    <defs></defs>
                    <g id="sApp-Landing-Page" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="sApp-v1.0" transform="translate(0.000000, -554.000000)" fill="#FFFFFF">
                            <path
                                d="M-3,551 C186.257589,757.321118 319.044414,856.322454 395.360475,848.004007 C509.834566,835.526337 561.525143,796.329212 637.731734,765.961549 C713.938325,735.593886 816.980646,681.910577 1035.72208,733.065469 C1254.46351,784.220361 1511.54925,678.92359 1539.40808,662.398665 C1567.2669,645.87374 1660.9143,591.478574 1773.19378,597.641868 C1848.04677,601.75073 1901.75645,588.357675 1934.32284,557.462704 L1934.32284,863.183395 L-3,863.183395"
                                id="sApp-v1.0"></path>
                        </g>
                    </g>
                </svg>
            </div>
        </section>
        <!-- ***** Welcome Area End ***** -->


        <!-- ***** Counter Area Start ***** -->
        <section class="section counter-area ptb_50">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-5 col-sm-3 single-counter text-center">
                        <div class="counter-inner p-3 p-md-0">
                            <!-- Counter Item -->
                            <div class="counter-item d-inline-block mb-3">
                                <span class="counter fw-7">{{ $company }}</span>
                            </div>
                            <h5>Perusahaan</h5>
                        </div>
                    </div>
                    <div class="col-5 col-sm-3 single-counter text-center">
                        <div class="counter-inner p-3 p-md-0">
                            <!-- Counter Item -->
                            <div class="counter-item d-inline-block mb-3">
                                <span class="counter fw-7">{{ $activeProjects }}</span>
                            </div>
                            <h5>Proyek Aktif</h5>
                        </div>
                    </div>
                    <div class="col-5 col-sm-3 single-counter text-center">
                        <div class="counter-inner p-3 p-md-0">
                            <!-- Counter Item -->
                            <div class="counter-item d-inline-block mb-3">
                                <span class="counter fw-7">{{ $projects }}</span>
                            </div>
                            <h5>Proyek Selesai</h5>
                        </div>
                    </div>
                    <div class="col-5 col-sm-3 single-counter text-center">
                        <div class="counter-inner p-3 p-md-0">
                            <!-- Counter Item -->
                            <div class="counter-item d-inline-block mb-3">
                                <span class="counter fw-7">{{ $employees }}</span>
                            </div>
                            <h5>Karyawan</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Counter Area End ***** -->

        <!-- ***** Features Area Start ***** -->
        <section id="features" class="section features-area style-two overflow-hidden ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <!-- Section Heading -->
                        <div class="section-heading text-center">
                            <span class="d-inline-block rounded-pill shadow-sm fw-5 px-4 py-2 mb-3">
                                <i class="far fa-lightbulb text-primary mr-1"></i>
                                <span class="text-primary">Fitur</span>
                                Unggulan
                            </span>
                            <h2>Apa saja fitur yang ditawarkan?</h2>
                            <p class="d-none d-sm-block mt-4">Kelola karyawan, pantau kinerja, dan tinjau perkembangan
                                melalui berbagai fitur canggih yang dirancang untuk meningkatkan efisiensi dan
                                produktivitas perusahaan Anda.</p>
                            <p class="d-block d-sm-none mt-4">Kelola karyawan, pantau kinerja, dan tinjau perkembangan
                                melalui berbagai fitur canggih yang dirancang untuk meningkatkan efisiensi dan
                                produktivitas perusahaan Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 res-margin">
                        <!-- Image Box -->
                        <div class="image-box text-center icon-1 p-5">
                            <!-- Featured Image -->
                            <div class="featured-img mb-3">
                                <img class="avatar-sm"
                                    src="{{ asset('assets/landing-page/img/icon/featured-img/layers.png') }}"
                                    alt="">
                            </div>
                            <!-- Icon Text -->
                            <div class="icon-text">
                                <h3 class="mb-2">Manajemen Karyawan</h3>
                                <p>Kelola data karyawan dari informasi pribadi hingga riwayat pekerjaan di satu platform.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 res-margin">
                        <!-- Image Box -->
                        <div class="image-box text-center icon-1 p-5">
                            <!-- Featured Image -->
                            <div class="featured-img mb-3">
                                <img class="avatar-sm"
                                    src="{{ asset('assets/landing-page/img/icon/featured-img/speak.png') }}"
                                    alt="">
                            </div>
                            <!-- Icon Text -->
                            <div class="icon-text">
                                <h3 class="mb-2">Penilaian Kinerja</h3>
                                <p>Pantau dan evaluasi kinerja karyawan secara berkala untuk pengembangan dan insentif.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <!-- Image Box -->
                        <div class="image-box text-center icon-1 p-5">
                            <!-- Featured Image -->
                            <div class="featured-img mb-3">
                                <img class="avatar-sm"
                                    src="{{ asset('assets/landing-page/img/icon/featured-img/lock.png') }}"
                                    alt="">
                            </div>
                            <!-- Icon Text -->
                            <div class="icon-text">
                                <h3 class="mb-2">Pengalaman Pengguna</h3>
                                <p>Antarmuka yang mudah digunakan memastikan pengelolaan data yang cepat dan efisien.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Features Area End ***** -->

        <!-- ***** Work Area Start ***** -->
        <section id="workflow" class="section work-area bg-overlay overflow-hidden ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <!-- Work Content -->
                        <div class="work-content text-center">
                            <h2 class="text-white">Bagaimana Employee Management System Bekerja?</h2>
                            <p class="text-white my-3">Sistem ini memudahkan Anda dalam mengelola karyawan, melacak proyek,
                                dan mengatur alur kerja tim secara efisien.</p>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-md-4">
                        <!-- Single Work -->
                        <div class="text-center p-3">
                            <!-- Work Icon -->
                            <div class="work-icon">
                                <img class="avatar-md"
                                    src="{{ asset('assets/landing-page/img/icon/work/download.png') }}"
                                    alt="Download Icon">
                            </div>
                            <h3 class="text-white py-3">Buat Akun Karyawan</h3>
                            <p class="text-white">Daftarkan karyawan dan berikan mereka akses ke sistem untuk melihat
                                informasi mereka dan mengikuti proyek.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <!-- Single Work -->
                        <div class="text-center p-3">
                            <!-- Work Icon -->
                            <div class="work-icon">
                                <img class="avatar-md"
                                    src="{{ asset('assets/landing-page/img/icon/work/settings.png') }}"
                                    alt="Settings Icon">
                            </div>
                            <h3 class="text-white py-3">Kelola Proyek</h3>
                            <p class="text-white">Atur tugas untuk tim dan pantau kemajuan proyek dengan mudah.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <!-- Single Work -->
                        <div class="text-center p-3">
                            <!-- Work Icon -->
                            <div class="work-icon">
                                <img class="avatar-md" src="{{ asset('assets/landing-page/img/icon/work/app.png') }}"
                                    alt="App Icon">
                            </div>
                            <h3 class="text-white py-3">Pantau Performa</h3>
                            <p class="text-white">Lihat performa karyawan dan ambil keputusan untuk pengembangan tim.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ***** Work Area End ***** -->

        <!--====== Footer Area Start ======-->
        <footer class="footer-area">
            <!-- Footer Top -->
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Logo -->
                                <a class="navbar-brand" href="#">
                                    <img class="logo"
                                        src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg"
                                        alt="logo-brand">
                                </a>
                                <p class="mt-2 mb-3">Employee Management System membantu Anda mengelola karyawan dan proyek
                                    dengan mudah. Hubungi kami untuk mulai menggunakan sistem kami!</p>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Footer Title -->
                                <h3 class="footer-title mb-2">Navigasi</h3>
                                <ul>
                                    <li class="py-2"><a class="scroll" href="#home">Home</a></li>
                                    <li class="py-2"><a class="scroll" href="#features">Fitur</a></li>
                                    <li class="py-2"><a class="scroll" href="#workflow">Alur Kerja</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Footer Title -->
                                <h3 class="footer-title mb-2">Butuh Bantuan?</h3>
                                <ul>
                                    <li class="py-2"><a href="https://wa.me/62859189566453?text=Proposal"
                                            target="_blank">Proposal</a></li>
                                    <li class="py-2"><a href="https://wa.me/62859189566453?text=Kerjasama%20Industri"
                                            target="_blank">Kerjasama Industri</a></li>
                                    <li class="py-2"><a href="https://wa.me/62859189566453?text=Hubungi%20Kami"
                                            target="_blank">Hubungi Kami</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Footer Title -->
                                <h3 class="footer-title mb-2">Hubungi Kami</h3>
                                <!-- Contact Info -->
                                <ul>
                                    <li class="py-2">
                                        <i class="fas fa-envelope"></i>
                                        Email: <a href="mailto:support@emsystem.com">support@emsystem.com</a>
                                    </li>
                                    <li class="py-2">
                                        <i class="fas fa-phone"></i>
                                        Telepon: +62 812-3456-7890
                                    </li>
                                    <li class="py-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Alamat: Jl. Mawar No. 123, Jakarta
                                    </li>
                                    <li class="py-2">
                                        <i class="fas fa-clock"></i>
                                        Jam Kerja: Senin-Jumat, 09:00 - 17:00
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <!-- Copyright Area -->
                            <div
                                class="copyright-area d-flex flex-wrap justify-content-center justify-content-sm-between text-center py-4">
                                <!-- Copyright Left -->
                                <div class="copyright-left">&copy; Copyrights 2024 Employee Management System. All
                                    rights reserved.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!--====== Footer Area End ======-->
    </div>
@endsection
