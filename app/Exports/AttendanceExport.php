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
        foreach ($employees as $employee) {
            $row = [$employee->name]; // Baris pertama dimulai dengan nama karyawan

            for ($date = 1; $date <= Carbon::create($this->year, $this->month, 1)->daysInMonth; $date++) {
                $currentDate = Carbon::create($this->year, $this->month, $date);
                $formattedDate = $currentDate->format('Y-m-d');

                if (!$currentDate->isWeekend()) {
                    $employeeAttendances = $attendances->get($employee->id, collect());

                    if ($employeeAttendances->where('date', $formattedDate)->isNotEmpty()) {
                        $attendance = $employeeAttendances->where('date', $formattedDate)->first();
                        $status = $this->mapStatus($attendance->status);
                        $time = $attendance->created_at->format('H:i');
                    } else {
                        $status = 'Alpha'; // Jika tidak ada data absensi
                        $time = '';
                    }

                    $row[] = $status;
                    $row[] = $time;
                }
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $daysInMonth = Carbon::create($this->year, $this->month, 1)->daysInMonth;

        // Baris pertama untuk tanggal
        $heading1 = ['Nama'];
        for ($date = 1; $date <= $daysInMonth; $date++) {
            $currentDate = Carbon::create($this->year, $this->month, $date);
            if (!$currentDate->isWeekend()) {
                $heading1[] = $currentDate->format('d/m/Y');
                $heading1[] = ''; // Kolom kosong untuk "Masuk"
            }
        }

        // Baris kedua untuk sub-header 'Detail' dan 'Masuk'
        $heading2 = [''];
        for ($date = 1; $date <= $daysInMonth; $date++) {
            $currentDate = Carbon::create($this->year, $this->month, $date);
            if (!$currentDate->isWeekend()) {
                $heading2[] = 'Detail';
                $heading2[] = 'Masuk';
            }
        }

        return [$heading1, $heading2];
    }

    public function map($row): array
    {
        return $row; // Mengembalikan data apa adanya karena sudah disusun di `collection()`
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
    // Mendapatkan batas kolom tertinggi dan baris tertinggi
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    // Gabungkan cell pada header pertama (tanggal)
    for ($col = 'B'; $col <= $highestColumn; $col++) {
        if (($col - 1) % 2 == 0) {
            $startCol = $col;
            $endCol = ++$col;
            $sheet->mergeCells("{$startCol}1:{$endCol}1"); // Menggabungkan dua kolom untuk setiap tanggal
        }
    }

    // Style untuk header (Name dan Tanggal)
    $sheet->getStyle('A1:' . $highestColumn . '2')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 12
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'FFCCCCCC' // Warna abu-abu untuk header
            ]
        ]
    ]);

    // Format khusus untuk memisahkan setiap tanggal dengan border tebal
    for ($col = 'B'; $col <= $highestColumn; $col++) {
        if (($col - 1) % 2 == 0) {
            $startCol = $col;
            $endCol = ++$col;

            // Border kanan tebal untuk setiap tanggal
            $sheet->getStyle("{$endCol}1:{$endCol}{$highestRow}")->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);

            // Border bawah tebal untuk baris header tanggal
            $sheet->getStyle("B1:{$highestColumn}2")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
        }
    }

    // Format borders untuk seluruh tabel
    $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Mengubah warna berdasarkan status kehadiran
    for ($row = 3; $row <= $highestRow; $row++) { // Mulai dari baris 3 karena 1 dan 2 adalah header
        for ($col = 'B'; $col <= $highestColumn; $col++) {
            $statusCell = $col . $row; // Mengambil nilai dari cell
            $statusValue = $sheet->getCell($statusCell)->getValue();

            // Sesuaikan warna berdasarkan nilai status
            switch (strtolower($statusValue)) {
                case 'masuk':
                    $sheet->getStyle($statusCell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => 'FF99FF99' // Warna hijau untuk 'Masuk'
                            ]
                        ]
                    ]);
                    break;
                case 'telat':
                    $sheet->getStyle($statusCell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => 'FFFFCC00' // Warna kuning untuk 'Telat'
                            ]
                        ]
                    ]);
                    break;
                case 'izin':
                    $sheet->getStyle($statusCell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => 'FF00CCFF' // Warna biru untuk 'Izin'
                            ]
                        ]
                    ]);
                    break;
                case 'alpha':
                    $sheet->getStyle($statusCell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => 'FFFF9999' // Warna merah muda untuk 'Alpha'
                            ]
                        ]
                    ]);
                    break;
                default:
                    // Jika nilai status tidak cocok dengan kategori di atas, biarkan warna default
                    break;
            }
        }
    }
}

}
