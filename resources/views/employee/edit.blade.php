<x-app-layout>
    <form action="{{ route('employee.update', $employee) }}" method="post">
        @csrf
        @method('PUT')
        <label for="user_id">User</label>
        <input disabled type="text" id="user" value="{{ $employee->user->name }}" />
        <input type="hidden" name="user_id" value="{{ $employee->user->id }}" />
        @error('user_id')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="department_id">Department</label>
        <select name="department_id">
            <option selected disabled>Pilih Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', $employee->department_id) == $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="position_id">Position</label>
        <select name="position_id">
            <option selected disabled>Pilih Position</option>
            @foreach ($positions as $position)
                <option value="{{ $position->id }}" @selected(old('position_id', $employee->position_id) == $position->id)>
                    {{ $position->name }}
                </option>
            @endforeach
        </select>
        @error('position_id')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="phone">Nomor Telepon</label>
        <input type="number" name="phone" value="{{ old('phone') ?? $employee->phone }}"></input>
        @error('phone')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="address">Alamat</label>
        <textarea type="text" name="address">{{ old('address') ?? $employee->address }}</textarea>
        @error('address')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="hire_date">Tanggal Penerimaan</label>
        <input type="date" name="hire_date" value="{{ old('hire_date') ?? $employee->hire_date }}" />
        @error('hire_date')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <button type="submit">Submit</button>
    </form>
</x-app-layout>
