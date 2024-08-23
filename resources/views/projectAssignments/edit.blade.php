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
                        <h1 class="h3">Edit Assigned</h1>

                    </div>
                    <div class="row">
                        <form action="{{ route('projectAssignments.update', $projectAssignment->id) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="mb-3">
                                <label for="project_id" class="form-label">Project</label>
                                <select name="project_id" id="project_id"
                                    class="form-control @error('project_id') is-invalid @enderror">
                                    <option value="">
                                        --Pilih Project_id--
                                    </option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('project_id', $projectAssignment->project_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">employee_id</label>
                                <input type="text" value="{{ old('employee_id', $projectAssignment->employee_id) }}"
                                    name="employee_id" class="form-control @error('employee_id') is-invalid @enderror"
                                    id="employee_id">
                                @error('employee_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" name="role" value="{{ old('role', $projectAssignment->role) }}"
                                    class="form-control @error('role') is-invalid @enderror" id="role">
                                @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="assigned_at" class="form-label">Ditugaskan pada</label>
                                <input type="date" name="assigned_at"
                                    value="{{ old('assigned_at', $projectAssignment->assigned_at) }}"
                                    class="form-control @error('assigned_at') is-invalid @enderror" id="assigned_at">
                                @error('assigned_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
</x-app-layout>
