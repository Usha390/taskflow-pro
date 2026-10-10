<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // সব project এর list
    public function index()
    {
        $projects = Project::forUser(auth()->id())
           // ->withCount('tasks')
            ->latest()
            ->paginate(9);

        return view('projects.index', compact('projects'));
    }

    // নতুন project তৈরি form
    public function create()
    {
        return view('projects.create');
    }

    // Form submit হলে save করো
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#4F46E5',
            'created_by' => auth()->id(),
        ]);

        // Creator কে owner হিসেবে add করো
        $project->members()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    // একটা নির্দিষ্ট project দেখাও
    public function show(Project $project)
    {
        $project->load(['members', 'creator']);

        return view('projects.show', compact('project'));
    }

    // Edit form
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update করো
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    // Delete করো (soft delete)
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}