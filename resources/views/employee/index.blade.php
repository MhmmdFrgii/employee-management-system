<x-app-layout>
    <a href="{{ route('employee.create') }}">Create</a>
    <table>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Department</th>
            <th>Hire Date</th>
            <th>Position</th>
            <th>Action</th>
        </tr>
        @forelse ($employees as $data)
            <tr>
                <td>{{ $data->user->name }}</td>
                <td>{{ $data->phone }}</td>
                <td>{{ $data->address }}</td>
                <td>{{ $data->department->name }}</td>
                <td>{{ $data->hire_date }}</td>
                <td>{{ $data->position->name }}</td>
                <td>{{ $data->description }}</td>
                <td>
                    <a href="{{ route('employee.edit', $data->id) }}">Edit</a>

                    <form action="{{ route('employee.destroy', $data) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <td>tidak ada data</td>
        @endforelse
    </table>
</x-app-layout>
