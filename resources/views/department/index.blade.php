@extends('dashboard.layouts.main')

@section('content')
    <div class="table-responsive">
        <table id="zero_config" class="table border table-striped table-bordered">
            <thead>
                <!-- start row -->
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <!-- end row -->
            </thead>
            <tbody>
                @foreach ($department as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <a href=""></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
