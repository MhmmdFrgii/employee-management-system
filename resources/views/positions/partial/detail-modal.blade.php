<div class="modal fade" id="detailpositionsModal{{ $data->id }}" tabindex="-1"
    aria-labelledby="detailpositionsModalLabel{{ $data->id }}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailpositionsModalLabel{{ $data->id }}">Detail Jabatan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Jabatan</label>
                    <p class="form-control-plaintext">{{ $data->name }}</p>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <p class="form-control-plaintext">{{ $data->description ?? 'deskripsi kosong' }}</p>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
