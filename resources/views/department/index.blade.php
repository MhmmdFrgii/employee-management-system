<x-app-layout>
    <a href="{{ route('department.create') }}">Create</a>
    <table>
        <tr>
            <td>Department</td>
            <th>Description</th>
            <th>Action</th>
        </tr>
        @foreach ($department as $data)
        <tr>
            <td>{{ $data->name }}</td>
            <td>{{ $data->description }}</td>
            <td>
                <a href="{{ route('department.edit', $data->id) }}">Edit</a>

                <form action="{{ route('department.destroy', $data->id) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</x-app-layout>
