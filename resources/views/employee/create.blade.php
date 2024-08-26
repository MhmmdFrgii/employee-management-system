@extends('dashboard.layouts.main')

@section('content')
    <form action="{{ route('employee.store') }}" method="post" class="p-4 bg-light rounded" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select">
                <option selected disabled>Pilih User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-select">
                <option selected disabled>Pilih Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="position_id" class="form-label">Position</label>
            <select name="position_id" id="position_id" class="form-select">
                <option selected disabled>Pilih Position</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>
            @error('position_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Nomor Telepon</label>
            <input type="number" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="hire_date" class="form-label">Tanggal Penerimaan</label>
            <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ old('hire_date') }}">
            @error('hire_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="nik" class="form-label">Nik</label>
            <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik') }}">
            @error('nik')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="fullname" class="form-label">fullname</label>
            <input type="text" name="fullname" id="fullname" class="form-control" value="{{ old('fullname') }}">
            @error('fullname')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        photo
        <input type="file" name="photo">
        cv
        <input type="file" name="cv">
        gender
        <div class="form-check">
            <input type="radio" id="male" name="gender" value="male"
                {{ old('gender') == 'male' ? 'checked' : '' }} class="form-check-input">
            <label for="male" class="form-check-label">Male</label>
        </div>
        <div class="form-check">
            <input type="radio" id="female" name="gender" value="female"
                {{ old('gender') == 'female' ? 'checked' : '' }} class="form-check-input">
            <label for="female" class="form-check-label">Female</label>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
