@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Curriculum Vitae (CV)</h3>

        @if (isset($cvUrl))
            <iframe src="{{ $cvUrl }}" width="100%" height="600px">
                Your browser does not support displaying PDFs.
                <a href="{{ $cvUrl }}">Download CV</a>
            </iframe>
        @else
            <p>No CV available for this candidate.</p>
        @endif
    </div>
@endsection
