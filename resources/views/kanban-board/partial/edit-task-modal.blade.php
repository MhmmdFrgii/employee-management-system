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

                    <!-- Hidden Input for Kanban Board ID -->
                    <input type="hidden" name="kanban_boards_id" value="{{ $kanbanboard->id ?? '' }}">

                    <!-- Title Input -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            id="title" value="{{ $task->title ?? old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Input -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $task->description ?? old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date Input -->
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            id="date" value="{{ $task->date ?? old('date') }}">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Color Select -->
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <select name="color" class="form-select @error('color') is-invalid @enderror" id="color">
                            @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info'] as $colorOption)
                                <option value="{{ $colorOption }}"
                                    {{ (isset($task) && $task->color === $colorOption) || old('color') == $colorOption ? 'selected' : '' }}>
                                    {{ ucfirst($colorOption) }}
                                </option>
                            @endforeach
                        </select>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- User Select -->
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Assign to User</label>
                        <select name="employee_id"
                            class="form-select select2 @error('employee_id') is-invalid @enderror" id="employee_id">
                            <option value="" selected>Select a user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ (isset($task) && $task->employee_id == $user->id) || old('employee_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a user",
            allowClear: true
        });
    });
</script>
