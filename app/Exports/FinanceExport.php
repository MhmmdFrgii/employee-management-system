<?php
namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FinanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        // Ambil data finance berdasarkan bulan dan tahun yang dipilih
        $finances = Transaction::whereYear('transaction_date', $this->year)
            ->whereMonth('transaction_date', $this->month)
            ->get();

        return $finances;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Amount',
            'Type',
            'Description',
            'Transaction Date',
        ];
    }

    public function map($finance): array
    {
        static $index = 1;
        $types = [
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
        ];

        return [
            $index++, // No.
            'Rp ' . number_format($finance->amount, 2, ',', '.'), // Amount
            $types[$finance->type], // Type (Pemasukan / Pengeluaran)
            $finance->description ?? 'N/A', // Description
            Carbon::parse($finance->transaction_date)->format('d M Y'), // Transaction Date
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Format header
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Header color: yellow

        // Format columns
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Format borders
        $sheet->getStyle('A1:E100')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $type = $sheet->getCell('C' . $row)->getValue();
            switch ($type) {
                case 'Pengeluaran':
                    $sheet->getStyle('C' . $row)->getFont()->getColor()->setARGB('FF0000'); // Red for expenses
                    break;
                case 'Pemasukan':
                    $sheet->getStyle('C' . $row)->getFont()->getColor()->setARGB('008000'); // Green for income
                    break;
            }
        }
    }
}
