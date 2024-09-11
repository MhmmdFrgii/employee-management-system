<div class="modal fade" id="deletepositionsModal{{ $data->id }}" tabindex="-1"
    aria-labelledby="deletepositionsModalLabel{{ $data->id }}" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="deletepositionsModalLabel{{ $data->id }}">Konfirmasi
                    Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jabatan ini? Tindakan ini tidak
                    dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                <form action="{{ route('positions.destroy', $data->id) }}"
                    method="POST" style="display: inline;">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
