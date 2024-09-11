@extends('dashboard.layouts.main')

@section('content')
<div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
    <div class="row g-2 mt-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="row g-2">
                <h3 class="mx-1">Jabatan</h3>
            </div>
        </div>
        @include('positions.partial.search')
    </div>
</div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-2">
                    <div class="table-responsive">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jabatan</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($positions as $data)
                                    <tr>
                                        <td class="col-lg-1">
                                            {{ $loop->iteration + ($positions->currentPage() - 1) * $positions->perPage() }}
                                        </td>
                                        <td class="col-lg-2">{{ $data->name }}</td>
                                        <td class="col-lg-3">{{ $data->description }}</td>
                                        <td class="col-lg-1 text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editpositionsModal{{ $data->id }}">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deletepositionsModal{{ $data->id }}">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    @include('positions.partial.add-modal')
                                    @include('positions.partial.edit-modal')
                                    @include('positions.partial.delete-modal')
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                                class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                            <p class="mt-3">No data available.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 justify-content-end">
                            {{ $positions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
