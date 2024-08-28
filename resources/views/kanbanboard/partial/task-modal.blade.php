<!-- resources/views/components/task-modal.blade.php -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $actionUrl }}" method="POST">
                    @csrf
                    @method($method)
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="hidden" name="color" value="primary">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="kanban_boards_id" value="{{ $kanbanboard->id ?? '' }}">
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            id="title" value="{{ $task->title ?? old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Tambahkan input lainnya sesuai kebutuhan -->
                    <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
