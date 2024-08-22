
<x-app-layout>
    <form action="{{ route('department.store') }}" method="post">
        @csrf

        <label for="name">Department</label>
        <input type="text" name="name">

        @error('name')
            <p>{{ $message }}</p>
        @enderror
        <br>

        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
        @error('description')
            <p>{{ $message }}</p>
        @enderror

        <br>

        <button type="submit">Submit</button>
    </form>
</x-app-layout>