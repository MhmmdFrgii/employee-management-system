<!-- Modal Complete -->
<div class="modal fade" id="completeModal{{ $project->id }}" tabindex="-1" aria-labelledby="completeModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="completeModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin proyek ini telah selesai?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                    data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('projects.complete', $project->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-success" type="submit">Ya, tandai proyek telah selesai</button>
                </form>
            </div>
        </div>
    </div>
</div>
