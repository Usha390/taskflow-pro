<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projectIds = $user->projects()->pluck('projects.id');

        // ============================================
        // Quick Stats (4 Cards)
        // ============================================
        $stats = [
            'total' => Task::whereIn('project_id', $projectIds)->count(),
            'completed' => Task::whereIn('project_id', $projectIds)
                ->where('status', 'done')
                ->count(),
            'overdue' => Task::whereIn('project_id', $projectIds)
                ->where('due_date', '<', now())
                ->where('status', '!=', 'done')
                ->count(),
            'active' => Task::whereIn('project_id', $projectIds)
                ->where('status', '!=', 'done')
                ->count(),
        ];

        // ============================================
        // Status Distribution (Doughnut Chart)
        // ============================================
        $statusData = [
            'labels' => ['To Do', 'In Progress', 'Review', 'Done'],
            'data' => [
                Task::whereIn('project_id', $projectIds)->where('status', 'todo')->count(),
                Task::whereIn('project_id', $projectIds)->where('status', 'in_progress')->count(),
                Task::whereIn('project_id', $projectIds)->where('status', 'review')->count(),
                Task::whereIn('project_id', $projectIds)->where('status', 'done')->count(),
            ],
        ];

        // ============================================
        // Priority Distribution (Bar Chart)
        // ============================================
        $priorityData = [
            'labels' => ['Low', 'Medium', 'High', 'Urgent'],
            'data' => [
                Task::whereIn('project_id', $projectIds)->where('priority', 'low')->count(),
                Task::whereIn('project_id', $projectIds)->where('priority', 'medium')->count(),
                Task::whereIn('project_id', $projectIds)->where('priority', 'high')->count(),
                Task::whereIn('project_id', $projectIds)->where('priority', 'urgent')->count(),
            ],
        ];

        // ============================================
        // Task Completion Trend (Line Chart — Last 7 days)
        // ============================================
        $trendLabels = [];
        $trendData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trendLabels[] = $date->format('M d');

            $trendData[] = Task::whereIn('project_id', $projectIds)
                ->where('status', 'done')
                ->whereDate('updated_at', $date->format('Y-m-d'))
                ->count();
        }

        $trend = [
            'labels' => $trendLabels,
            'data' => $trendData,
        ];

        // ============================================
        // Project Overview
        // ============================================
        $projects = $user->projects()
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($q) {
                $q->where('status', 'done');
            }])
            ->get();

        return view('reports.index', compact('stats', 'statusData', 'priorityData', 'trend', 'projects'));
    }
}
