<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Leave Requst</title>
</head>
<body>
    <form action="{{ route('leave.update', $leaveRequest->id) }}" method="post">
        @csrf
        @method('put')

        <label for="employee">Employee ID</label>
        <input type="text" name="employee_id" value="{{ $leaveRequest->employee_id }}">

        @error('employee_id')
            <p>{{ $message }}</p>
        @enderror
        <br>


        <label for="start-date">Start Date</label>
        <input type="date" name="start_date" id="start-date" value="{{ $leaveRequest->start_date }}">
        @error('start_date')
            <p>{{ $message }}</p>
        @enderror

        <br>
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" id="end_date" value="{{ $leaveRequest->end_date }}">

        @error('end_date')
            <p>{{ $message }}</p>
        @enderror

        <br>

        <label for="type">Type</label>
        <input type="text" name="type" value="{{ $leaveRequest->type }}">

        @error('type')
            <p>{{ $message }}</p>
        @enderror
        <br>
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="pending" selected>Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>

        <br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
