<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Position::query();
 
        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $query->search($search);
        }

        $positions = $query->orderBy('created_at', 'DESC')->paginate(10)->withQueryString();

        return view('positions.index',compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PositionRequest $request)
    {
        Position::create($request->validated());

        return redirect()->route('positions.index')->with('success', 'Tambah Jabatan Success!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PositionRequest $request, Position $position)
    {
        $position->update($request->validated());

        return redirect()->route('positions.index')->with('success', 'Edit Jabatan Success!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        try {
            $position->delete();

            return redirect()->route('positions.index')->with('success', 'Hapus Jabatan Success!');

        } catch (\Throwable $e) {
            # code...
            return redirect()->route('positions.index')->with('success', 'Failed Hapus Jabatan.');
        }
    }
}
