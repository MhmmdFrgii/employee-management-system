@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">

                <h1 class="h3">Karyawan</h1>
                <div class="d-flex justify-content-end mb-3 mt-3">
                    <form id="searchForm" action="{{ route('employees.index') }}" method="GET"
                        class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="form-group mb-0 position-relative">
                            <label for="search" class="sr-only">Cari:</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="form-control shadow search-input" placeholder="Cari data..">

                            <a href="{{ route('employees.index') }}"
                                class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                X
                            </a>
                        </div>
                        <button type="submit" class="btn btn-secondary">Cari</button>
                    </form>
                </div>

                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="" alt="" class="img-fluid mb-n4">
                    </div>
                </div>

            <div class="row mt-5">
                @forelse ($employees as $employee)

                <div class="col-sm-6 col-lg-4">
                    <div class="card hover-img">
                      <div class="card-body p-4 text-center border-bottom">
                        <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : '../../dist/images/profile/user-1.jpg' }}" alt="" class="rounded-circle mb-3" width="80" height="80">
                        <h5 class="fw-semibold mb-0">{{ $employee->name }}</h5>
                        <span class="text-dark fs-2">{{ $employee->department->name }}</span>
                      </div>
                      <ul class="px-2 py-2 bg-light list-unstyled d-flex align-items-center justify-content-center mb-0">
                        <li class="position-relative">
                          <a class="text-primary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold" href="data-id="{{ $employee->id }} data-bs-toggle="modal" data-bs-target="#detailModal">
                            <i class='bx bx-info-circle'></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  @include('employee.partial.detail-modal');
                  @empty
                      <div class="col-12 text-center">
                          <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                              style="width: clamp(150px, 50vw, 300px);">
                          <p class="mt-3">Tidak ada data tersedia</p>
                      </div>
                  @endforelse
            </div>
            <div class="mt-3 justify-content-end">
                {{ $employees->links() }}
            </div>
 
@endsection
