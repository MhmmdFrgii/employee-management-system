<x-app-layout>
    <a href="{{ route('salaries.create') }}">Create</a>
    <table>
        <thead>
            <tr>
                <th>Karyawan</th>
                <th>Gaji</th>
                <th>Tanggal Pembayaran</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salarie as $data)
            <tr>
                <td>{{ $data->employee}}</td>
                <td>Rp {{ number_format($data->amount, 2, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($data->payment_date)->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('salaries.edit', $data->id) }}">Edit</a>
                    <form action="{{ route('salaries.destroy', $data->id) }}" method="post" style="display:inline;">
                        @method('DELETE')
                        @csrf
                        <button type="submit">Hapus</button>
                    </form>                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
