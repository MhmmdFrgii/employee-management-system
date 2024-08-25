<thead>
    <tr>
        <th>No</th>
        <th>
            <a href="#" class="sort-link" data-sort="name"
                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                Name
                @if (request('sortBy') === 'name')
                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="#" class="sort-link" data-sort="phone"
                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                Phone
                @if (request('sortBy') === 'phone')
                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="#" class="sort-link" data-sort="address"
                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                Address
                @if (request('sortBy') === 'address')
                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="#" class="sort-link" data-sort="department"
                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                Department
                @if (request('sortBy') === 'department')
                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="#" class="sort-link" data-sort="hire_date"
                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                Hire Date
                @if (request('sortBy') === 'hire_date')
                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                @endif
            </a>
        </th>
        <th>
            <a href="#" class="sort-link" data-sort="position"
                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                Position
                @if (request('sortBy') === 'position')
                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                @endif
            </a>
        </th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @foreach ($employees as $employee)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $employee->user->name }}</td>
            <td>{{ $employee->phone }}</td>
            <td>{{ $employee->address }}</td>
            <td>{{ $employee->department->name }}</td>
            <td>{{ $employee->hire_date }}</td>
            <td>{{ $employee->position->name }}</td>
            <td>Action buttons here</td>
        </tr>
    @endforeach
</tbody>
