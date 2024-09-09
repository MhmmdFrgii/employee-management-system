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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
            // Periksa apakah karyawan memiliki absensi
            $employeeAttendances = $attendances->get($employee->id, collect());

            // Untuk setiap hari dalam bulan
            for ($date = 1; $date <= $daysInMonth; $date++) {
                $currentDate = Carbon::create($this->year, $this->month, $date);
                $formattedDate = $currentDate->format('Y-m-d');

                if (!$currentDate->isWeekend()) { // Hanya tambahkan hari kerja
                    if ($employeeAttendances->where('date', $formattedDate)->isNotEmpty()) {
                        $attendance = $employeeAttendances->where('date', $formattedDate)->first();
                        $status = $this->mapStatus($attendance->status);
                        $time = $attendance->status === 'present' || $attendance->status === 'late'
                            ? $attendance->created_at->format('H:i')
                            : '';
                    } else {
                        $status = 'Alpha';
                        $time = '';
                    }

                    $data[] = [
                        'employee_name' => $employee->name,
                        'department_name' => $employee->department->name ?? 'N/A',
                        'date' => $formattedDate,
                        'status' => $status,
                        'time' => $time,
                    ];
                }
            }
        }

        // Mengurutkan data berdasarkan tanggal
        usort($data, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

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
    // Format header
    $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Header color: yellow

    // Format columns
    foreach (range('A', 'F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Format borders
    $sheet->getStyle('A1:F100')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    $highestRow = $sheet->getHighestRow();
    for ($row = 2; $row <= $highestRow; $row++) {
        $status = $sheet->getCell('E' . $row)->getValue();
        switch ($status) {
            case 'Alpha':
                $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('FF0000'); // Red
                break;
            case 'Masuk':
                $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('008000'); // Green
                break;
            case 'Telat':
                $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('FFFF00'); // Yellow
                break;
            case 'Izin':
                $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('0000FF'); // Blue
                break;
        }
    }
}

}
