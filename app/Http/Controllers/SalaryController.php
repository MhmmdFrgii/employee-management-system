<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SalaryRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk Salary dengan relasi employee_detail
        $query = Salary::with('employee_detail')
            ->join('transactions', 'salaries.id', '=', 'transactions.salary_id')
            ->where('salaries.company_id', Auth::user()->company_id);

        // Pencarian berdasarkan karyawan atau jumlah gaji
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee_detail', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhere('salaries.amount', 'like', '%' . $search . '%')
                    ->orWhere('transactions.amount', 'like', '%' . $search . '%')
                    ->orWhereDate('transactions.transaction_date', $search);
            });
        }

        // Filter berdasarkan tanggal transaksi
        $date = $request->input('date');
        if ($date) {
            $query->whereDate('transactions.transaction_date', $date);
        }

        // Sorting
        $sortBy = $request->get('sortBy', 'salaries.created_at'); // Pastikan tabel yang tepat
        $sortDirection = $request->get('sortDirection', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Ambil data employee dari company tertentu
        $employees = EmployeeDetail::where('company_id', Auth::user()->company->id)->get();

        // Pagination
        $salaries = $query->paginate(10);
        $salaries->appends($request->all());

        // Mengambil data untuk chart (jika ada)
        $monthlyData = $this->getMonthlyData();

        return view('salaries.index', compact('salaries', 'employees', 'sortBy', 'sortDirection', 'search', 'monthlyData'));
    }

    // Method untuk chart data
    protected function getMonthlyData()
    {
        $companyId = Auth::user()->company->id;
        $year = now()->year;

        // Simply sum the amount for each month
        return Salary::selectRaw('MONTH(created_at) as month, 
        SUM(amount) as total_amount')
            ->where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('month', 'asc')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalaryRequest $request)
    {
        $validatedData = $request->validated();

        // Tambahkan ID perusahaan
        $validatedData['company_id'] = Auth::user()->company->id;

        // Hitung total gaji
        $totalAmount = $validatedData['amount'] + ($validatedData['extra'] ?? 0);

        DB::beginTransaction();
        try {
            $salary = Salary::create(array_merge($validatedData, ['total_amount' => $totalAmount]));

            if (empty($validatedData['transaction_date'])) {
                $validatedData['transaction_date'] = Carbon::today()->toDateString(); // Menggunakan Carbon untuk mendapatkan tanggal hari ini
            }

            Transaction::create([
                'type' => 'expense',
                'amount' => $totalAmount,
                'description' => 'Gaji untuk karyawan ' . $salary->employee_detail->name,
                'transaction_date' => $request->transaction_date ?? date('Y-m-d'),
                'company_id' => Auth::user()->company_id,
                'salary_id' => $salary->id
            ]);

            DB::commit();

            return redirect()->route('salaries.index')->with('success', 'Gaji dan transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->route('salaries.index')->with('error', 'Terjadi kesalahan saat menyimpan gaji: ' . $e->getMessage());
        }

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil dibuat.');
    }

    public function getEmployeeSalary($employeeId)
    {
        $employee = EmployeeDetail::findOrFail($employeeId);
        return response()->json(['salary' => $employee->salary]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalaryRequest $request, $id)
    {
        $salarie = Salary::findOrFail($id);


        $validatedData = $request->validated();

        // Hitung total gaji baru
        $totalAmount = $validatedData['amount'] + ($validatedData['extra'] ?? 0);

        // Update data gaji
        $salarie->update(array_merge($validatedData, ['total_amount' => $totalAmount]));

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil diperbarui.');

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil dihapus');
    }

    // private function getMonthlyData()
    // {
    //     $year = date('Y'); // Tahun saat ini
    //     $data = DB::table('salaries')
    //         ->select(
    //             DB::raw('MONTH(transaction_date) as month'),
    //             DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
    //             DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
    //         )
    //         ->where('company_id', Auth::user()->company->id)
    //         ->whereYear('transaction_date', $year)
    //         ->groupBy(DB::raw('MONTH(transaction_date)'))
    //         ->orderBy('month')
    //         ->get();

    //     $months = [];
    //     $income = [];
    //     $expense = [];

    //     // Daftar nama bulan dalam bahasa Indonesia
    //     $monthNames = [
    //         1 => 'Januari',
    //         2 => 'Februari',
    //         3 => 'Maret',
    //         4 => 'April',
    //         5 => 'Mei',
    //         6 => 'Juni',
    //         7 => 'Juli',
    //         8 => 'Agustus',
    //         9 => 'September',
    //         10 => 'Oktober',
    //         11 => 'November',
    //         12 => 'Desember'
    //     ];

    //     foreach ($data as $item) {
    //         $months[] = $monthNames[$item->month]; // Menggunakan nama bulan dalam bahasa Indonesia
    //         $income[] = (float) $item->income; // Pastikan data adalah angka
    //         $expense[] = (float) $item->expense; // Pastikan data adalah angka
    //     }

    //     return [
    //         'months' => $months,
    //         'income' => $income,
    //         'expense' => $expense
    //     ];
    // }
}
