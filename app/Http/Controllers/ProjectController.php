<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Validasi nilai sortDirection
        $sortDirection = in_array($request->input('sortDirection'), ['asc', 'desc']) ? $request->input('sortDirection') : 'asc';

        // Validasi dan tetapkan nilai sortBy
        $sortBy = $request->input('sortBy', 'status');
        if (!in_array($sortBy, ['name', 'description', 'start_date', 'end_date', 'status'])) {
            $sortBy = 'status'; // Atur ke nilai default jika tidak valid
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $statusFilters = $request->input('status');
            $query->whereIn('status', $statusFilters);
        }

        // Filter dan urutkan data
        $project = $query->orderBy($sortBy, $sortDirection)->paginate(10);

        return view('projects.index', compact('project'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());

        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('danger', 'Data berhasil dihapus');
    }
}
