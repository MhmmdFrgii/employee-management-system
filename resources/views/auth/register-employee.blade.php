@extends('auth.layouts.main')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('register.employee') }}" method="post">
        @csrf
        @method('post')
        <input type="hidden" name="company" value="{{ request('company') }}">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="password">Password :</label>
            <input type="text" id="password" name="password">
        </div>
        
        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>
@endsection
