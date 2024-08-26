{{-- <x-app-layout>
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
</x-app-layout> --}}

@extends('dashboard.layouts.main')

@section('content')
    <form action="{{ route('employee.store') }}" method="post" class="p-4 bg-light rounded">
        @csrf

        <div class="mb-3">
            <label for="user_id" class="form-label">Name</label>
            <input class="form-control" disabled type="text" id="user" value="{{ $employee->user->name }}" />
            <input type="hidden" name="user_id" value="{{ $employee->user->id }}" />
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-select">
                <option selected disabled>Pilih Position</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}" @selected(old('position_id', $employee->position_id) == $position->id)>
                        {{ $position->name }}
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
                    <option value="{{ $position->id }}" @selected(old('position_id', $employee->position_id) == $position->id)>
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
            <input class="form-control" type="number" name="phone"
                value="{{ old('phone') ?? $employee->phone }}"></input>
            @error('phone')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control" type="text" name="address">{{ old('address') ?? $employee->address }}</textarea>
            @error('address')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="hire_date" class="form-label">Tanggal Penerimaan</label>
            <input class="form-control" type="date" name="hire_date"
                value="{{ old('hire_date') ?? $employee->hire_date }}" />
            @error('hire_date')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
