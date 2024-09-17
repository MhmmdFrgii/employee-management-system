<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\FinanceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Menggunakan query builder alih-alih mengambil semua data terlebih dahulu
        $query = Transaction::query()->where('company_id', Auth::user()->company_id);

        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('amount', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('transaction_date', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $statuses = $request->input('status');
            $query->whereIn('type', $statuses);
        }

        // Filter berdasarkan tanggal
        $date = $request->input('date');
        if ($date) {
            $query->whereDate('transaction_date', $date);
        }

        // Sorting
        $sortBy = $request->get('sortBy', 'transaction_date');
        $sortDirection = $request->get('sortDirection', 'asc');

        // Jika sortBy 'total_amount', ganti dengan kolom yang valid seperti 'amount'
        if ($sortBy == 'total_amount') {
            $query->orderBy('amount', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Mendapatkan data dengan paginasi
        $finance = $query->paginate(10);

        return view('finance.index', compact('finance', 'sortBy', 'sortDirection'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'salary_id' => 'nullable|exists:salaries,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|not_regex:/-/',
            'description' => 'nullable|string',
        ], [
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.not_regex' => 'Jumlah tidak boleh negatif.',
        ]);

        Transaction::create([
            'company_id' => Auth::user()->company_id,
            'salary_id' => $request->salary_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date ?? date('Y-m-d'),
        ]);

        return redirect()->route('transactions.index')->with('success', 'Berhasil menambahkan transaksi.');
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|digits:4',
            'month' => 'required|integer|between:1,12',
        ]);

        $year = $validated['year'];
        $month = $validated['month'];

        // Proses export
        return Excel::download(new FinanceExport($year, $month), 'Data_Keuangan_' . $month . '_' . $year . '.xlsx');
    }
}
