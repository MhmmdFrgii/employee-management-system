<div class="modal fade" id="ModalApproved" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-white text-white rounded">
                <h5 class="modal-title" id="detailModalLabel">Penempatan Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('applicants.update', $applicant->id) }}" method="POST">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <!-- Kolom Detail Karyawan -->
                            <div class="col-md-12">
                                <!-- Pilihan Departemen -->
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Pilih Departemen</label>
                                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" id="department_id">
                                        <option value="">Pilih Departemen</option>
                                        @foreach ($department as $d)
                                            <option value="{{ $d->id }}" {{ old('department_id', $applicant->department_id) == $d->id ? 'selected' : '' }}>
                                                {{ $d->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Pilihan Posisi -->
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Pilih Posisi</label>
                                    <select name="position_id" class="form-select @error('position_id') is-invalid @enderror" id="position_id">
                                        <option value="">Pilih Posisi</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}" {{ old('position_id', $applicant->position_id) == $position->id ? 'selected' : '' }}>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Terima</button>
                </div>
            </form>
        </div>
    </div>
</div>
