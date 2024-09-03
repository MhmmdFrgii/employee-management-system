<!-- Modal Delete -->
<div class="modal fade" id="ModalDelete{{ $applicant->id }}" tabindex="-1" aria-labelledby="vertical-center-modal"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="myLargeModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menolak?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                    data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('applicants.destroy', $applicant->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-md">Tolak</button>
                    </form>
            </div>
        </div>
    </div>
</div>
