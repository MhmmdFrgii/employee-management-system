<x-app-layout>
    <form action="{{ route('employee.store') }}" method="post">
        @csrf
        <label for="user_id">User</label>
        <select name="user_id">
            <option selected disabled>Pilih User</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        @error('user_id')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="department_id">Department</label>
        <select name="department_id">
            <option selected disabled>Pilih Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                    {{ $position->name }}
                </option>
            @endforeach
        </select>
        @error('position_id')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="phone">Nomor Telepon</label>
        <input type="number" name="phone" value="{{ old('phone') }}"></input>
        @error('phone')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="address">Alamat</label>
        <textarea type="text" name="address">{{ old('address') }}</textarea>
        @error('address')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="hire_date">Tanggal Penerimaan</label>
        <input type="date" name="hire_date" value="{{ old('hire_date') }}"></input>
        @error('hire_date')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <button type="submit">Submit</button>
    </form>
</x-app-layout>
