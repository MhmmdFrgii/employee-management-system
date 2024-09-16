<div class="modal fade" id="detaildepartmentsModal{{ $department->id }}" tabindex="-1"
    aria-labelledby="detaildepartmentsModal{{ $department->id }}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detaildepartmentsModal{{ $department->id }}">Detail Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Jabatan</label>
                    <p class="form-control-plaintext">{{ $department->name }}</p>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <p class="form-control-plaintext">{{ $department->description ?? 'deskripsi kosong' }}</p>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
