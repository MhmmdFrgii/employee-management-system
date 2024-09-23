<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\EmployeeDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
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
        $employees = EmployeeDetail::where('company_id', $user->company_id)->get();
        $attendances = Attendance::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get()
            ->groupBy('employee_id');

        $data = [];
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

                $employeeAttendances = $attendances->get($employee->id, collect());

                if ($employeeAttendances->where('date', $formattedDate)->isNotEmpty()) {
                    $attendance = $employeeAttendances->where('date', $formattedDate)->first();
                    $status = $this->mapStatus($attendance->status);
                } else {
                    $status = 'Alpha'; // Jika tidak ada data absensi
                }

                $row[] = $status;
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
            $heading1[] = $currentDate->format('d'); // Menampilkan hanya tanggal
        }

        return [$heading1];
    }

    public function map($row): array
    {
        return $row; // Mengembalikan data apa adanya karena sudah disusun di collection()
    }

    public function styles(Worksheet $sheet)
    {
        // Mendapatkan kolom dan baris tertinggi
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Konversi kolom tertinggi dari string ke indeks numerik
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        // Menerapkan style untuk header (baris pertama)
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
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

        // Auto-fit untuk semua kolom dari 'A' hingga kolom tertinggi
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $columnID = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Menerapkan warna pastel berdasarkan status pada setiap cell
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 4; $col <= $highestColumnIndex; $col++) { // Kolom ke-4 ke atas adalah data absensi
                // Mendapatkan nama kolom dari indeks
                $colString = Coordinate::stringFromColumnIndex($col);

                // Hitung tanggal berdasarkan kolom
                $currentDate = Carbon::create($this->year, $this->month, $col - 3); // Kolom ke-4 adalah tanggal 1

                // Cek apakah hari Sabtu atau Minggu
                if ($currentDate->isWeekend()) {
                    // Set teks "Libur" untuk akhir pekan
                    $sheet->setCellValue($colString . $row, 'Libur');

                    // Terapkan warna putih untuk akhir pekan
                    $sheet->getStyle($colString . $row)->applyFromArray($this->getWeekendStyle());
                    continue; // Lanjutkan ke cell berikutnya
                }

                // Mendapatkan nilai dari cell
                $status = $sheet->getCell($colString . $row)->getValue();

                // Mendapatkan style berdasarkan status
                $styleArray = $this->getStatusStyle($status);

                // Jika style ditemukan, terapkan style ke cell tersebut
                if ($styleArray !== null) {
                    $sheet->getStyle($colString . $row)->applyFromArray($styleArray);
                }
            }
        }
    }

    private function getWeekendStyle()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFFFFF', // Warna putih untuk akhir pekan
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
                return 'Masuk';
            case 'absent':
                return 'Izin';
            default:
                return 'Alpha';
        }
    }
}
