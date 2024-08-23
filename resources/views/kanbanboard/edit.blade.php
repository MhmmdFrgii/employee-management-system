<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Board') }}
        </h2>
    </x-slot>

    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <h4>Edit Board</h4>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('kanbanboard.update', $kanbanboard->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Board Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter board name" value="{{ old('name', $kanbanboard->name) }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="projects_id" class="form-label">Project</label>
                        <select name="projects_id" id="projects_id" class="form-select">
                            <option value="">-- Pilih Projek --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ old('projects_id', $kanbanboard->projects_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('projects_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="description" class="form-label mt-2">Description</label>
                    </div>
                    <textarea name="description" id="" cols="60" rows="5" class="form-textarea my-2" placeholder="Enter Description">{{ old('description', $kanbanboard->description) }}</textarea>
                    @error('description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary">Update Board</button>
                    <a href="{{ route('kanbanboard.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
