@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <h1 class="h3">Komentar</h1>

                <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createcommentModal">
                        Tambah
                    </button>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Project</th>
                                <th>Nama Karyawan</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comment as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->employee_detail->name }}</td>
                                    <td>{{ $data->comment }}</td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MODAL CREATE --}}
                <div class="modal fade" id="createcommentModal" tabindex="-1" aria-labelledby="createcommentModalLabel"
                    aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createcommentModalLabel">Tambah Komentar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('comment.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="create_employee_id" class="form-label">Nama Karyawan</label>
                                        <select name="employee_id" id="create_employee_id"
                                            class="form-control @error('employee_id') is-invalid @enderror">
                                            <option value="">Pilih Karyawan</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="project_id" class="form-label">Project</label>
                                        <select name="project_id" id="project_id"
                                            class="form-control @error('project_id') is-invalid @enderror">
                                            <option value="">--Pilih Project--</option>
                                            @foreach ($project as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('project_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="create_comment" class="form-label">Komentar</label>
                                        <textarea name="comment" id="create_comment" rows="4" class="form-control @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
