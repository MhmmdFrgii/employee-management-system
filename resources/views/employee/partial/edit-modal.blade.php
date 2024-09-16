<div class="modal fade" id="editModal{{ $loop->iteration }}" tabindex="-1"
    aria-labelledby="editModalLabel{{ $loop->iteration }}" aria-hidden="true">
    <div class="modal-dialog modal-m">
        <div class="modal-content">
            <div class="modal-header bg-white text-white rounded">
                <h5 class="modal-title" id="editModalLabel{{ $loop->iteration }}">Edit Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="department" class="form-label">Departemen</label>
                                    <select name="department_id"
                                        class="form-control @error('department_id') is-invalid @enderror"
                                        id="department">
                                        <option value="" disabled selected>Pilih Departemen</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="position" class="form-label">Jabatan</label>
                                    <select name="position_id"
                                        class="form-control @error('position_id') is-invalid @enderror" id="position">
                                        <option value="" disabled selected>Pilih Jabatan</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}"
                                                {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                                {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="position_id" class="form-label">Gaji Pokok</label>
                                    <input type="number" class="form-control @error('salary') is-invalid @enderror"
                                        name="salary" placeholder="Gaji Pokok"
                                        value="{{ old('salary', $employee->salary) }}" />
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
