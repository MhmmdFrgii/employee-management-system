<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $year;
    protected $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Attendance::query()
            ->join('employee_details', 'attendances.employee_id', '=', 'employee_details.id')
            ->select('attendances.*', 'employee_details.name as employee_name', 'employee_details.department_id');

        if ($this->month) {
            $query->whereYear('date', $this->year)
                ->whereMonth('date', $this->month);
        } else {
            $query->whereYear('date', $this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Karyawan',
            'Departemen',
            'Tanggal',
            'Keterangan',
            'Masuk',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->employee_name,
            $attendance->employee->department->name ?? 'N/A',
            $attendance->date,
            $this->mapStatus($attendance->status),
            $attendance->status === 'present' ? $attendance->created_at->format('H:i') : 'Tidak Ada Waktu',
        ];
    }

    private function mapStatus($status)
    {
        switch ($status) {
            case 'present':
                return 'Masuk';
            case 'late':
                return 'Telat';
            case 'absent':
                return 'Izin';
            default:
                return 'Alpha';
        }
    }
}
