@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Overview of your projects and tasks</p>
        </div>

        {{-- Quick Stats (4 Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            {{-- Total Tasks --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Tasks</p>
                        <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Completed --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Completed</p>
                        <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active</p>
                        <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $stats['active'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Overdue --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Overdue</p>
                        <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $stats['overdue'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row 1 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            {{-- Status Distribution --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Task Status Distribution</h2>
                <div class="relative" style="height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Priority Distribution --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Priority Breakdown</h2>
                <div class="relative" style="height: 300px;">
                    <canvas id="priorityChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Completion Trend --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Task Completion Trend (Last 7 Days)</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Project Overview --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Overview</h2>

            @if($projects->count() > 0)
                <div class="space-y-4">
                    @foreach($projects as $project)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $project->color }}"></div>
                                    <span class="font-semibold">{{ $project->name }}</span>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $project->completed_tasks_count }} / {{ $project->tasks_count }} done
                                </span>
                            </div>

                            @php
                                $percentage = $project->tasks_count > 0 
                                    ? round(($project->completed_tasks_count / $project->tasks_count) * 100) 
                                    : 0;
                            @endphp

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $percentage }}% complete</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-8">No projects yet</p>
            @endif
        </div>

    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

{{-- Charts JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // ============================================
    // 1. Status Distribution (Doughnut)
    // ============================================
    const statusCtx = document.getElementById('statusChart');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusData['labels']),
            datasets: [{
                data: @json($statusData['data']),
                backgroundColor: [
                    '#9CA3AF',
                    '#3B82F6',
                    '#A855F7',
                    '#10B981',
                ],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // ============================================
    // 2. Priority Distribution (Bar)
    // ============================================
    const priorityCtx = document.getElementById('priorityChart');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: @json($priorityData['labels']),
            datasets: [{
                label: 'Tasks',
                data: @json($priorityData['data']),
                backgroundColor: [
                    '#10B981',
                    '#FBBF24',
                    '#F97316',
                    '#EF4444',
                ],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // ============================================
    // 3. Completion Trend (Line)
    // ============================================
    const trendCtx = document.getElementById('trendChart');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($trend['labels']),
            datasets: [{
                label: 'Tasks Completed',
                data: @json($trend['data']),
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4F46E5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

});
</script>
@endsection