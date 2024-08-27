<?php

namespace App\Http\Controllers;

use App\Models\Salarie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SalarieRequest;

class SalarieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $salarie = Salarie::all();
    //     return view('salaries.index', compact('salarie'));
    // }



    public function index(Request $request)
    {
        $query = Salarie::query();

        // Menambahkan filter pencarian jika ada
        if ($request->has('search')) {
            $query->where('employee', 'like', '%' . $request->search . '%')
            ->orWhere('amount', 'like', '%' . $request->search . '%')
            ->orWhere('payment_date', 'like', '%' . $request->search . '%');
        }

        // Menambahkan sorting jika ada
        if ($request->has('sortBy') && $request->has('sortDirection')) {
            $query->orderBy($request->sortBy, $request->sortDirection);
        }

        // Paginasi
        $salarie = $query->paginate(10); // Menentukan jumlah item per halaman

        return view('salaries.index', compact('salarie'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('salaries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalarieRequest $request)
    {
        Salarie::create($request->validated());

        return redirect()->route('salaries.index')->with('success', 'Salarie berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salarie $salarie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $salarie = Salarie::findOrFail($id);
        return view('salaries.edit', compact('salarie'));
    }




    /**
     * Update the specified resource in storage.
     */
    

    public function update(SalarieRequest $request, $id)
    {
        $salarie = Salarie::findOrFail($id);
        $salarie->update($request->validated()); 

        return redirect()->route('salaries.index')->with('success', 'Salaries berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salarie $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('danger', 'Data berhasil dihapus');
    }
}
