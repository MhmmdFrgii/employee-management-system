<x-app-layout>
    <form id="searchForm" action="{{ route('employee.search') }}">
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}">
        </div>
        <button type="submit">Cari</button>
    </form>
    <a href="{{ route('employee.create') }}">Create</a>
    <table id="employeeTable">
        @include('employee.employee_table', ['employees' => $employees])
    </table>

    <div class="pagination">
        @include('employee.pagination_links', ['employees' => $employees])
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).on('submit', '#searchForm', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#employeeTable').html(response.html);
                    $('.pagination').html(response.pagination);

                    const url = new URL(window.location.href);
                    const params = new URLSearchParams(url.search);
                    params.set('search', $('#search').val());
                    window.history.pushState({}, '', `${url.pathname}?${params}`);
                }
            });
        });

        $(document).on('click', '.sort-link', function(e) {
            e.preventDefault();

            const sortBy = $(this).data('sort');
            const sortDirection = $(this).data('direction');

            $.ajax({
                url: "{{ route('employee.search') }}",
                type: 'GET',
                data: {
                    search: $('#search').val(),
                    sortBy: sortBy,
                    sortDirection: sortDirection
                },
                success: function(response) {
                    $('#employeeTable').html(response.html);
                    $('.pagination').html(response.pagination);

                    const url = new URL(window.location.href);
                    const params = new URLSearchParams(url.search);
                    params.set('sortBy', sortBy);
                    params.set('sortDirection', sortDirection);
                    window.history.pushState({}, '', `${url.pathname}?${params}`);
                }
            });
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $.get(url, function(response) {
                $('#employeeTable').html(response.html);
                $('.pagination').html(response.pagination);
            });
        });
    </script>
</x-app-layout>
