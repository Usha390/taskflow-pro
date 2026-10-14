<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    // Kanban board দেখাও
    public function index(Request $request)
    {
        // User এর সব project
        $projects = auth()->user()->projects;

        // Selected project (default: first)
        $selectedProjectId = $request->project_id ?? $projects->first()?->id;

        // Tasks grouped by status
        $tasks = collect();
        if ($selectedProjectId) {
            $tasks = Task::where('project_id', $selectedProjectId)
                ->with(['assignee'])
                ->orderBy('position')
                ->get()
                ->groupBy('status');
        }

        // Status columns
        $statuses = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];

        return view('kanban.index', compact(
            'projects',
            'selectedProjectId',
            'tasks',
            'statuses'
        ));
    }

    // Drag & Drop এর সময় status update
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated!',
        ]);
    }
}