<div class="modal fade" id="editpositionsModal{{ $data->id }}" tabindex="-1"
    aria-labelledby="editpositionsModalLabel{{ $data->id }}" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="editpositionsModalLabel{{ $data->id }}">Edit Jabatan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('positions.update', $data->id) }}"
                    method="POST">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama
                            Jabatan</label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name" value="{{ old('name', $data->name) }}">
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
                            value="{{ old('description', $data->description) }}">
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
