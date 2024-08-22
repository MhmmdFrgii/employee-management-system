<x-app-layout>

    <form action="{{ route('department.update', $department->id) }}" method="POST" class="mb-5">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama Departemen</label>
            <input type="text" name="name" class="form-control" id="name"
            value="{{ old('name', $department->name) }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <input type="text" name="description" class="form-control" id="description"
            value="{{ old('description', $department->description) }}">
        </div>
        <button type="submit" class="btn btn-primary">Edit</button>
    </form>

</x-app-layout>