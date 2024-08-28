@extends('dashboard.layouts.main')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">My Projects</h1>

        <div class="bg-white shadow-sm rounded-lg">
            @foreach ($project as $data)
                <div class="p-4 border-b last:border-b-0">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold">{{ $data->name }}</h2>
                    </div>
                    {{-- <p class="text-sm text-gray-600 mb-2">{{ $data->description }}</p> --}}
                    <p class="text-sm text-gray-600 mb-4">Status: {{ $data->status }}</p>

                    @foreach ($data->project_assignments as $assignment)
                        <div class="flex items-center py-2 border-t first:border-t-0">
                            <span class="mr-4">{{ $assignment->role }}</span>
                            <span class="ml-auto text-sm text-gray-500">
                                {{ $assignment->assigned_at ?? 'No due date' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
