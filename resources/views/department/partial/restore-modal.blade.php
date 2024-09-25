<!-- Modal Restore -->
<div class="modal fade" id="modal-restore-{{ $department->id }}" tabindex="-1" aria-labelledby="modal-restore-label"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="modal-restore-label">Konfirmasi Pulihkan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin memulihkan data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-light-secondary text-secondary font-medium waves-effect text-start"
                    data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('departments.restore', $department->id) }}" method="post" class="d-inline">
                    @csrf
                    @method('POST')
                    <button class="btn btn-secondary" type="submit">Pulihkan</button>
                </form>
            </div>
        </div>
    </div>
</div>
