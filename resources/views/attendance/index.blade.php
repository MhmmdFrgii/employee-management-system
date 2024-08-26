<x-app-layout>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    @endif
    <form action="{{ route('attendance.store') }}" method="post">
        @csrf
        <button>Absen</button>
    </form>
    <form method="GET" action="{{ route('attendance.index') }}">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control mr-2 rounded shadow"
                placeholder="Cari Data..." value="{{ request('search') }}">

            <!-- Dropdown filter status -->
            <select name="status" class="form-control mr-2 rounded shadow">
                <option value="">-- Pilih Status --</option>
                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
            </select>

            <button class="btn btn-outline-secondary rounded shadow" type="submit">Cari</button>
        </div>
    </form>
    <table>
        <thead>
            <tr>
                <th class="mr-5">
                    <a href="#" class="sort-link" data-sort="employee_id" 
                    data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                        Employee
                        @if (request('sortBy') === 'employee_id')
                            <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="#" class="sort-link" data-sort="date" 
                    data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                        Date
                        @if (request('sortBy') === 'date')
                            <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th> Status </th>
                
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td> {{ $attendance->employee_id }}</td>
                    <td> {{ $attendance->date }}</td>
                    <td> {{ $attendance->status }}</td>
                </tr>
            @empty
                <td>tidak ada data.</td>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $attendances->appends(request()->query())->links() }}
    </div>


    <script>
        document.querySelectorAll('.sort-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const sort = this.getAttribute('data-sort');
                const direction = this.getAttribute('data-direction');
                const url = new URL(window.location.href);
                url.searchParams.set('sortBy', sort);
                url.searchParams.set('sortDirection', direction);
                window.location.href = url.toString();
            });
        });
    </script>
</x-app-layout>