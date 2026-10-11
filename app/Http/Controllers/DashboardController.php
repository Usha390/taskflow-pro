<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // User এর সব project IDs
        $projectIds = auth()->user()->projects()->pluck('projects.id');

        // Statistics
        $stats = [
            'total_projects' => $projectIds->count(),
            'total_tasks' => Task::whereIn('project_id', $projectIds)->count(),
            'completed_tasks' => Task::whereIn('project_id', $projectIds)
                ->where('status', 'done')
                ->count(),
            'overdue_tasks' => Task::whereIn('project_id', $projectIds)
                ->overdue()
                ->count(),
        ];

        // Recent Tasks
        $recentTasks = Task::whereIn('project_id', $projectIds)
            ->with(['project', 'assignee'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentTasks'));
    }
}
