<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\EmployeeDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $year;
    protected $month;
    protected $selectedDate;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
        $this->selectedDate = Carbon::createFromDate($year, $month, 1);
    }

    public function collection()
    {
        // Dapatkan user yang sedang lox`gin
        $user = auth()->user();

        // Ambil karyawan berdasarkan perusahaan user
        $employees = EmployeeDetail::where('company_id', $user->company_id)
            ->with(['attendances' => function ($query) {
                $query->whereYear('date', $this->year)
                      ->whereMonth('date', $this->month);
            }])
            ->get();

        // Dapatkan karyawan yang tidak memiliki data absensi pada bulan yang dipilih
        $employeesAlpha = EmployeeDetail::where('company_id', $user->company_id)
            ->whereDoesntHave('attendances', function ($query) {
                $query->whereYear('date', $this->year)
                      ->whereMonth('date', $this->month);
            })
            ->get();

        // Gabungkan karyawan dengan absensi dan yang alpha
        return $employees->merge($employeesAlpha);
    }

    public function headings(): array
    {
        return [
            'No.',
            'Karyawan',
            'Departemen',
            'Tanggal',
            'Keterangan',
            'Masuk',
        ];
    }

    public function map($employee): array
{
    static $index = 1;

    if ($employee->attendances->isNotEmpty()) {
        $attendance = $employee->attendances->first();

        return [
            $index++,
            $employee->name,
            $employee->department->name ?? 'N/A',
            Carbon::parse($attendance->date)->format('Y-m-d'),  // Menggunakan Carbon::parse()
            $this->mapStatus($attendance->status),
            $attendance->status === 'present' || 'late' || 'absent' ? $attendance->created_at->format('H:i') : 'Tidak Ada Waktu',
        ];
    } else {
        return [
            $index++,
            $employee->name,
            $employee->department->name ?? 'N/A',
            $this->selectedDate->format('Y-m-d'), // Tanggal default untuk Alpha
            'Alpha',
            'Tidak Ada Waktu',
        ];
    }
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

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
