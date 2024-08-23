<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salarie;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SalarieRequest;
use App\Http\Requests\StoreSalarieRequest;
use App\Http\Requests\UpdateSalarieRequest;

class SalarieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salarie = Salarie::all();
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
    // public function update(SalarieRequest $request, Salarie $salarie)
    // {
        
    //     $salarie->update($request->validated()); 

    //     return redirect()->route('salaries.index')->with('success', 'Salaries berhasil di edit');
    // }

    public function update(SalarieRequest $request, $id)
    {
        $salarie = Salarie::findOrFail($id);
        $salarie->update($request->validated()); 

        return redirect()->route('salaries.index')->with('success', 'Salaries berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salarie $salarie)
    {
        try {
            $salarie->delete();

            return redirect()->route('salaries.index')->with('success', 'Hapus Salarie Success!');

        } catch (\Throwable $e) {
            
            return redirect()->route('salaries.index')->with('success', 'Failed Hapus Salarie.');
        }
    }
}
