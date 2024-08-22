<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Positions</title>
</head>
<body>
    <a href="{{ route('positions.create') }}">Create</a>
    <table>
        <tr>
            <td>Position</td>
            <th>Description</th>
            <th>Action</th>
        </tr>
        @foreach ($positions as $position)
        <tr>
            <td>{{ $position->name }}</td>
            <td>{{ $position->description }}</td>
            <td>
                <a href="{{ route('positions.edit', $position->id) }}">Edit</a>

                <form action="{{ route('positions.destroy', $position->id) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
