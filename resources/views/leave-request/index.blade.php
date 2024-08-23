<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave Request</title>
</head>
<body>
    <a href="{{ route('leave.create') }}">Tambah</a>
    <table>
        <tr>
            <th>Employee</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach ($leaveRequest as $leave)
        <tr>
            <td>{{ $leave->employee_id }}</td>
            <td>{{ $leave->start_date }}</td>
            <td>{{ $leave->end_date }}</td>
            <td>{{ $leave->type }}</td>
            <td>{{ $leave->status }}</td>
            <td>
                <a href="{{ route('leave.edit', $leave->id) }}">Edit</a>

                <form action="{{ route('leave.destroy', $leave->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
