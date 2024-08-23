<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Leave Requst</title>
</head>
<body>
    <form action="{{ route('leave.store') }}" method="post">
        @csrf

        <label for="employee">Employee ID</label>
        <input type="text" name="employee_id" value="1">

        @error('employee_id')
            <p>{{ $message }}</p>
        @enderror
        <br>


        <label for="start-date">Start Date</label>
        <input type="date" name="start_date" id="start-date">
        @error('start_date')
            <p>{{ $message }}</p>
        @enderror

        <br>
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" id="end_date">

        @error('end_date')
            <p>{{ $message }}</p>
        @enderror

        <br>

        <label for="type">Type</label>
        <input type="text" name="type" >

        @error('type')
            <p>{{ $message }}</p>
        @enderror
        <br>
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="pending" selected>Pending</option>
            <option value="approved" disabled>Approved</option>
            <option value="rejected" disabled>Rejected</option>
        </select>

        <br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
