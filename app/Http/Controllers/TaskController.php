<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    // সব task দেখাও (filter সহ)
    public function index(Request $request)
    {
        $projectIds = auth()->user()->projects()->pluck('projects.id');

        $tasks = Task::whereIn('project_id', $projectIds)
            ->with(['project', 'assignee'])
            ->byStatus($request->status)
            ->byPriority($request->priority)
            ->when($request->search, function ($q, $search) {
                return $q->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    // নতুন task form
    public function create(Request $request)
    {
        $projects = auth()->user()->projects;
        $selectedProject = $request->project_id;

        return view('tasks.create', compact('projects', 'selectedProject'));
    }

    // Form submit হলে save করো
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            

        ]);

        $task = Task::create($validated);
   
        
        // Notification trigger
        if ($task->assignee && $task->assignee->id !== auth()->id()) {
            $task->assignee->notify(new \App\Notifications\TaskAssignedNotification($task));
        }

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task created successfully!');
    }

    // একটা task দেখাও
    public function show(Task $task)
    {
        $task->load(['project', 'assignee']);

        return view('tasks.show', compact('task'));
    }

    // Edit form
    public function edit(Task $task)
    {
        $projects = auth()->user()->projects;
        $users = User::all();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    // Update করো
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);
       
        
    // Notification trigger
    if ($task->assignee && $task->assignee->id !== auth()->id()) {
        $task->assignee->notify(new \App\Notifications\TaskAssignedNotification($task));
    }

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task updated successfully!');
    }

    // Delete করো
    public function destroy(Task $task)
    {
        $projectId = $task->project_id;
        $task->delete();
   

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Task deleted successfully!');
    }

    // Status update (AJAX এর জন্য)
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }
}