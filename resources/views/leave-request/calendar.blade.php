@extends('dashboard.layouts.main')

@section('content')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.js'></script>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">Kalender Pengajuan Cuti</h1>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id', // Menggunakan locale Indonesia
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                @forelse ($leave_datas as $leave_data)
                    {
                        title: '{{ $leave_data->type === 'izin' ? 'Cuti Tahunan' : 'Cuti Sakit' }} - {{ $leave_data->employee_detail->name ?? 'Tanpa Nama' }}',
                        start: '{{ $leave_data->start_date }}', // Properti start_date dari objek
                        end: '{{ \Carbon\Carbon::parse($leave_data->end_date)->addDay()->format('Y-m-d') }}', // Tambahkan 1 hari ke end_date
                        backgroundColor: '{{ $leave_data->type === 'izin' ? '#28a745' : '#dc3545' }}', // Hijau untuk izin, merah untuk sakit
                    },
                @empty
                    // Jika tidak ada data, kalender tetap kosong
                @endforelse

            ],
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            }
        });
        calendar.render();
    });
</script>
