@extends('dashboard.layouts.main')

@section('content')
    <div class="card">
        <div class="row gx-0">
            <div class="col-lg-12">
                <div class="p-4 calender-sidebar app-calendar">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @forelse ($leave_datas as $leave_data)
                        {
                            title: '{{ $leave_data->type === 'izin' ? 'Cuti' : 'Cuti Sakit' }} - {{ $leave_data->employee_detail->name ?? 'Tanpa Nama' }}',
                            start: '{{ \Carbon\Carbon::parse($leave_data->start_date)->format('Y-m-d') }}',
                            end: '{{ \Carbon\Carbon::parse($leave_data->end_date)->addDay()->format('Y-m-d') }}',
                            backgroundColor: '{{ $leave_data->type === 'izin' ? '#28a745' : '#dc3545' }}',
                            textColor: '#ffffff', // Putih untuk teks
                            borderColor: '#ffffff', // Warna border
                        },
                    @empty
                    @endforelse
                ],
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },
                editable: true,
                droppable: true,
            });
            calendar.render();
        });
    </script>
@endpush

@push('style')
    <style>
        .fc-event {
            border-radius: 10px !important;
            font-size: 14px;
            padding: 5px;
        }

        .fc-event .fc-event-title {
            color: #fff !important;
        }

        .fc-daygrid-event {
            font-size: 0.9em;
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .fc-event:hover {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }

        .fc-toolbar-title {
            font-size: 24px;
        }
    </style>
@endpush
