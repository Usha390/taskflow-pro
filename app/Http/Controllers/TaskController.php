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
    $user = auth()->user();
    $projectIds = $user->projects()->pluck('projects.id');

    $tasks = Task::whereIn('project_id', $projectIds)
        ->with(['project', 'assignee'])

        // Search by title
        ->when($request->search, fn($q, $s) =>
            $q->where('title', 'like', "%{$s}%"))

        // Filter by status
        ->when($request->status, fn($q, $s) =>
            $q->where('status', $s))

        // Filter by priority
        ->when($request->priority, fn($q, $p) =>
            $q->where('priority', $p))

        // Filter by project
        ->when($request->project_id, fn($q, $pid) =>
            $q->where('project_id', $pid))

        // Filter by assignee
        ->when($request->assigned_to, fn($q, $uid) =>
            $q->where('assigned_to', $uid))

        // Filter by due date range
        ->when($request->due_from, fn($q, $d) =>
            $q->whereDate('due_date', '>=', $d))
        ->when($request->due_to, fn($q, $d) =>
            $q->whereDate('due_date', '<=', $d))

        ->latest()
        ->paginate(15)
        ->withQueryString();   // ← Important: Pagination এ filter preserve

    // Filter dropdown এর জন্য data
    $projects = $user->projects;
    $users = \App\Models\User::all();

    return view('tasks.index', compact('tasks', 'projects', 'users'));
}

        // নতুন task form
    public function create(Request $request)
    {
        // Role Check: Developer ra task create korte parbe na
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $projects = auth()->user()->projects;
        $selectedProject = $request->project_id;

        return view('tasks.create', compact('projects', 'selectedProject'));
    }

    // Form submit হলে save করো
       public function store(Request $request)
    {
        // Role Check: Developer ra task create korte parbe na
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        // Notun Check: Jodi assign kora user ta Developer na hoy
        if ($request->assigned_to) {
            $assignedUser = \App\Models\User::find($request->assigned_to);
            if ($assignedUser && $assignedUser->role !== 'developer') {
                return back()->withErrors(['assigned_to' => 'Tasks can only be assigned to Developers.'])->withInput();
            }
        }

        $task = Task::create($validated);

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

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task updated successfully!');
    }
    // Delete করো
    public function destroy(Task $task)
    {
        // Role Check: Developer ra task delete korte parbe na
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

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