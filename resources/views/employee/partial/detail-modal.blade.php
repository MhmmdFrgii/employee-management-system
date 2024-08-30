<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-white text-white rounded">
                <h5 class="modal-title" id="detailModalLabel">Detail Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <!-- Kolom Foto -->
                        <div class="col-md-4 text-center">
                            <img id="modal-photo" src="{{ asset('storage/' . $employee->photo) }}" alt="avatar" class="rounded-circle img-fluid border border-3 border-primary shadow-sm mb-3" width="150" height="150">
                        </div>
                        <!-- Kolom Detail Karyawan -->
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Nama:</h6>
                                    <p id="modal-fullname" class="fw-semibold">{{ $employee->fullname }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Departemen:</h6>
                                    <p id="modal-department" class="fw-semibold">{{ $employee->department->name }}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Jabatan:</h6>
                                    <p id="modal-position" class="fw-semibold">{{ $employee->position->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Telepon:</h6>
                                    <p id="modal-phone" class="fw-semibold">{{ $employee->phone }}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Alamat:</h6>
                                    <p id="modal-address" class="fw-semibold">{{ $employee->address }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Jenis Kelamin:</h6>
                                    <p id="modal-gender" class="fw-semibold">{{ $employee->gender }}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Tanggal Bergabung:</h6>
                                    <p id="modal-hire-date" class="fw-semibold">{{ $employee->hire_date }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
