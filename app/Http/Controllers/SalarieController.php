<?php

namespace App\Http\Controllers;

use App\Models\Salarie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SalarieRequest;
use App\Models\EmployeeDetail;

class SalarieController extends Controller
{
    public function index(Request $request)
    {
        $query = Salarie::with('employeeDetails');
        $employees = EmployeeDetail::all();


        if ($request->has('search')) {
            $query->whereHas('employeeDetails', function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->search . '%');
            })
                ->orWhere('amount', 'like', '%' . $request->search . '%')
                ->orWhere('payment_date', 'like', '%' . $request->search . '%');
        }


        if ($request->has('sortBy') && $request->has('sortDirection')) {
            $query->orderBy($request->sortBy, $request->sortDirection);
        }

        $salaries = $query->paginate(10);

        $salaries->appends($request->all());

        return view('salaries.index', compact('salaries', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalarieRequest $request)
    {
        Salarie::create($request->validated());
        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil dibuat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalarieRequest $request, $id)
    {
        $salarie = Salarie::findOrFail($id);
        $salarie->update($request->validated());

        return redirect()->route('salaries.index')->with('success', 'Gaji berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salarie $salarie)
    {
        $salarie->delete();
        return redirect()->route('salaries.index')->with('danger', 'Data gaji berhasil dihapus');
    }
}
