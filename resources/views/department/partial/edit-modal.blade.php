<!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $department->id }}" tabindex="-1"
    aria-labelledby="editModalLabel{{ $department->id }}" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $department->id }}">Edit
                    Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('departments.update', $department->id) }}"
                    method="POST">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Departemen</label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name" value="{{ old('name') ?? $department->name }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <input type="text" name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            value="{{ $department->description ?? old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
            </div>
        </div>
    </div>
</div>
