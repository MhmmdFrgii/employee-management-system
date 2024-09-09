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

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $user = auth()->user();

        // Ambil karyawan berdasarkan perusahaan user
        $employees = EmployeeDetail::where('company_id', $user->company_id)
            ->get();

        // Ambil absensi untuk bulan dan tahun yang dipilih
        $attendances = Attendance::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get()
            ->groupBy('employee_id');

        $data = [];

        // Menghitung jumlah hari dalam bulan
        $daysInMonth = Carbon::create($this->year, $this->month, 1)->daysInMonth;

        foreach ($employees as $employee) {
            // Jika karyawan memiliki absensi
            if (isset($attendances[$employee->id])) {
                foreach ($attendances[$employee->id] as $attendance) {
                    $data[] = [
                        'employee_name' => $employee->name,
                        'department_name' => $employee->department->name ?? 'N/A',
                        'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                        'status' => $this->mapStatus($attendance->status),
                        'time' => $attendance->status === 'present' || $attendance->status === 'late'
                            ? $attendance->created_at->format('H:i')
                            : 'Tidak Ada Waktu',
                    ];
                }
            } else {
                // Karyawan tidak memiliki absensi di bulan ini
                for ($date = 1; $date <= $daysInMonth; $date++) {
                    $data[] = [
                        'employee_name' => $employee->name,
                        'department_name' => $employee->department->name ?? 'N/A',
                        'date' => Carbon::create($this->year, $this->month, $date)->format('Y-m-d'),
                        'status' => 'Alpha',
                        'time' => 'Tidak Ada Waktu',
                    ];
                }
            }
        }

        return collect($data);
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

    public function map($attendance): array
    {
        static $index = 1;

        return [
            $index++,
            $attendance['employee_name'],
            $attendance['department_name'],
            $attendance['date'],
            $attendance['status'],
            $attendance['time'],
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

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
