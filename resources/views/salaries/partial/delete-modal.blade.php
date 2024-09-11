  <!-- Modal Delete -->
  <div class="modal fade" id="deleteSalariesModal{{ $salary->id }}" tabindex="-1"
    aria-labelledby="deleteSalariesModalLabel{{ $salary->id }}" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSalariesModalLabel{{ $salary->id }}">
                    Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin untuk menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('salaries.destroy', $salary->id) }}"
                    method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
