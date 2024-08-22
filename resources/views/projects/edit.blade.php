<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3">Project</h1>

                    </div>
                    <div class="row">
                        <form action="{{ route('projects.update', $project->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Project</label>
                                <input type="text" name="name"
                                    class="form-control  @error('name') is-invalid @enderror" id="name"
                                    value="{{ old('name', $project->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <input type="text" name="description"
                                    class="form-control  @error('description') is-invalid @enderror" id="description"
                                    value="{{ old('name', $project->description) }}">
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date"
                                    class="form-control  @error('start_date') is-invalid @enderror" id="start_date"
                                    value="{{ old('name', $project->start_date) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date"
                                    class="form-control  @error('end_date') is-invalid @enderror" id="end_date"
                                    value="{{ old('name', $project->end_date) }}">
                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
</x-app-layout>
