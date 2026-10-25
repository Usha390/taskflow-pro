@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Top Bar --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Overview</h1>
                <p class="text-xs text-gray-500 mt-0.5">{{ now()->format('l, F j, Y') }}</p>
            </div>
                        <div class="flex items-center gap-2">
                @if(in_array(auth()->user()->role, ['admin', 'manager'])) 
                <a href="{{ route('tasks.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Task
                </a>
                @endif
            </div>
        </div>

        {{-- Statistics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            {{-- Total Projects --}}
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100/50 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                        Active
                    </span>
                </div>
                <div>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $stats['total_projects'] }}</p>
                    <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Total Projects</p>
                </div>
            </div>

            {{-- Total Tasks --}}
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $stats['total_tasks'] }}</p>
                    <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Total Tasks</p>
                </div>
            </div>

            {{-- Completed --}}
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @if($stats['total_tasks'] > 0)
                        <span class="text-xs font-semibold text-gray-500">
                            {{ round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) }}%
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $stats['completed_tasks'] }}</p>
                    <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Completed</p>
                </div>
            </div>

            {{-- Overdue --}}
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 hover:border-red-200 hover:shadow-lg hover:shadow-red-100/50 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-200 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @if($stats['overdue_tasks'] > 0)
                        <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                            Alert
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $stats['overdue_tasks'] }}</p>
                    <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Overdue</p>
                </div>
            </div>

        </div>

        {{-- Recent Tasks --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            
            {{-- Section Header --}}
            <div class="px-6 py-5 flex items-center justify-between border-b border-gray-50">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Recent Activity</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Your latest tasks</p>
                </div>
                <a href="{{ route('tasks.index') }}" 
                   class="inline-flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors group">
                    View all
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
</svg>
                </a>
            </div>

            @if($recentTasks->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($recentTasks as $task)
                        <a href="{{ route('tasks.show', $task) }}" 
                          class="block px-6 py-4 hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors group">
                            <div class="flex items-center gap-4">

                                {{-- Status Indicator --}}
                                <div class="flex-shrink-0">
                                    @if($task->status == 'done')
                                        <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    @elseif($task->status == 'in_progress')
                                        <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center">
                                            <div class="w-2.5 h-2.5 bg-blue-500 rounded-full animate-pulse"></div>
                                        </div>
                                    @elseif($task->status == 'review')
                                        <div class="w-9 h-9 rounded-full bg-purple-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center">
                                            <div class="w-2.5 h-2.5 bg-gray-400 rounded-full"></div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Task Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                            {{ $task->title }}
                                        </p>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                            @if($task->priority == 'urgent') bg-red-100 text-red-700
                                            @elseif($task->priority == 'high') bg-orange-100 text-orange-700
                                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-700
                                            @else bg-green-100 text-green-700 @endif">
                                            {{ $task->priority }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                            {{ $task->project->name }}
                                        </span>
                                        @if($task->assignee)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $task->assignee->name }}
                                            </span>
                                        @endif
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $task->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                {{-- Arrow --}}
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 mb-4">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">No recent activity</h3>
                    <p class="text-xs text-gray-500 mb-4">Start by creating a task in one of your projects</p>
                    <a href="{{ route('projects.index') }}" 
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium rounded-lg transition-colors">
                        Browse Projects
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection