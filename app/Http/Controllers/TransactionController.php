<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Menggunakan query builder alih-alih mengambil semua data terlebih dahulu
        $query = Transaction::query();

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
            // Memastikan filter yang diterima sesuai dengan tipe transaksi
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
        $query->orderBy($sortBy, $sortDirection);

        // Mendapatkan data dengan paginasi
        $finance = $query->paginate(10);

        return view('finance.index', compact('finance', 'sortBy', 'sortDirection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salary_id' => 'nullable|exists:salaries,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create([
            'company_id' => Auth::user()->company_id,
            'salary_id' => $request->salary_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully');
    }
}
