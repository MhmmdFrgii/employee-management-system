<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\EmployeeDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
        $user = Auth::user();

        // Ambil karyawan berdasarkan perusahaan user
        $employees = EmployeeDetail::where('company_id', $user->company_id)->get();

        // Ambil absensi untuk bulan dan tahun yang dipilih
        $attendances = Attendance::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get()
            ->groupBy('employee_id');

        $data = [];

        // Menyusun data berdasarkan karyawan dan tanggal
        $no = 1;
        foreach ($employees as $employee) {
            $row = [
                $no++, // Tambahkan nomor urut
                strtoupper($employee->name), // Ubah nama karyawan menjadi huruf kapital
                strtoupper($employee->department->name ?? '-') // Nama departemen
            ];

            for ($date = 1; $date <= Carbon::create($this->year, $this->month, 1)->daysInMonth; $date++) {
                $currentDate = Carbon::create($this->year, $this->month, $date);
                $formattedDate = $currentDate->format('Y-m-d');

                if (!$currentDate->isWeekend()) {
                    $employeeAttendances = $attendances->get($employee->id, collect());

                    if ($employeeAttendances->where('date', $formattedDate)->isNotEmpty()) {
                        $attendance = $employeeAttendances->where('date', $formattedDate)->first();
                        $status = $this->mapStatus($attendance->status);
                    } else {
                        $status = 'Alpha'; // Jika tidak ada data absensi
                    }

                    $row[] = $status;
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $daysInMonth = Carbon::create($this->year, $this->month, 1)->daysInMonth;

        // Baris pertama untuk tanggal (hanya menampilkan tanggal saja)
        $heading1 = ['No.', 'Karyawan', 'Departemen'];
        for ($date = 1; $date <= $daysInMonth; $date++) {
            $currentDate = Carbon::create($this->year, $this->month, $date);
            if (!$currentDate->isWeekend()) {
                $heading1[] = $currentDate->format('d'); // Menampilkan hanya tanggal
            }
        }

        return [$heading1];
    }

    public function map($row): array
    {
        return $row; // Mengembalikan data apa adanya karena sudah disusun di `collection()`
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Menerapkan style untuk header
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12, // Menjadikan heading lebih besar
                'color' => ['argb' => 'FF1E90FF'], // Teks berwarna biru
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THICK, // Border bawah tebal
                    'color' => ['argb' => 'FF1E90FF'], // Border berwarna biru
                ],
            ],
        ]);

        // Auto-fit untuk semua kolom
        foreach (range('A', $highestColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Menerapkan warna pastel berdasarkan status dan memastikan style diterapkan untuk semua tanggal
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 'B'; $col <= $highestColumn; $col++) {
                $status = $sheet->getCell($col . $row)->getValue();
                $styleArray = $this->getStatusStyle($status);

                if ($styleArray !== null) {
                    $sheet->getStyle($col . $row)->applyFromArray($styleArray);
                }
            }
        }
    }

    private function getStatusStyle($status)
    {
        switch ($status) {
            case 'Masuk':
                return $this->getPastelStyle('d2f0e3'); // Pastel Green
            case 'Izin':
                return $this->getPastelStyle('fcedd4'); // Light Blue
            case 'Alpha':
                return $this->getPastelStyle('fedbda'); // Light Pink
            case 'Telat':
                return $this->getPastelStyle('fedbda'); // Light Yellow
            default:
                return null;
        }
    }

    private function getPastelStyle($hexColor)
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => $hexColor,
                ],
            ],
            'font' => [
                'color' => ['argb' => 'FF000000'], // Teks berwarna hitam
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Border hitam
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
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
