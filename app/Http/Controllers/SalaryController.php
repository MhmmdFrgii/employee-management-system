<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SalaryRequest;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('employee_detail');

        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('employee_detail', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orWhere('amount', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('transaction_date', 'like', '%' . $search . '%');
        }

        // Filter jenis transaksi
        $types = $request->input('type');
        if ($types) {
            $query->whereIn('type', $types);
        }

        // Sorting
        $sortBy = $request->get('sortBy', 'transaction_date'); // Kolom default yang valid
        $sortDirection = $request->get('sortDirection', 'asc'); // Arah default
        $query->orderBy($sortBy, $sortDirection);

        // Ambil data yang telah disortir dan difilter
        $employees = EmployeeDetail::where('company_id', Auth::user()->company->id)->get();
        $salaries = $query->paginate(10);
        $salaries->appends($request->all());

        // Mengambil data untuk chart
        $monthlyData = $this->getMonthlyData();



        return view('salaries.index', compact('salaries', 'employees', 'sortBy', 'sortDirection', 'search', 'types', 'monthlyData'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SalaryRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['company_id'] = Auth::user()->company->id;

        Salary::create($validatedData);

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil dibuat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalaryRequest $request, $id)
    {
        $salarie = Salary::findOrFail($id);
        $salarie->update($request->validated());

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


    private function getMonthlyData()
    {
        $year = date('Y'); // Tahun saat ini
        $data = DB::table('salaries')
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->where('company_id', Auth::user()->company->id)
            ->whereYear('transaction_date', $year)
            ->groupBy(DB::raw('MONTH(transaction_date)'))
            ->orderBy('month')
            ->get();

        $months = [];
        $income = [];
        $expense = [];

        // Daftar nama bulan dalam bahasa Indonesia
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        foreach ($data as $item) {
            $months[] = $monthNames[$item->month]; // Menggunakan nama bulan dalam bahasa Indonesia
            $income[] = (float) $item->income; // Pastikan data adalah angka
            $expense[] = (float) $item->expense; // Pastikan data adalah angka
        }

        return [
            'months' => $months,
            'income' => $income,
            'expense' => $expense
        ];
    }
}