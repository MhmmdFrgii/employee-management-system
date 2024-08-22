<x-app-layout>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    @endif
    <form action="{{ route('attendance.store') }}" method="post">
        @csrf
        <button>Absen</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>employee</th>
                <th>date</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td> {{ $attendance->employee_id }}</td>
                    <td> {{ $attendance->date }}</td>
                    <td> {{ $attendance->status }}</td>
                </tr>
            @empty
                <td>tidak ada data.</td>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
