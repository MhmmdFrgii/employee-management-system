<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Position</title>
</head>

<body>
    <form action="{{ route('positions.update', $position->id) }}" method="post">
        @method('PUT')
        @csrf

        <label for="name">Position</label>
        <input type="text" name="name" value="{{ $position->name }}">

        @error('name')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10">{{ $position->description }}</textarea>

        @error('description')
            <p>{{ $message }}</p>
        @enderror
        <br>


        <button type="submit">Submit</button>
    </form>
</body>

</html>
