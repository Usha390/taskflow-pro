@extends('layouts.app')

@section('title', 'Kanban Board')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kanban Board</h1>
                <p class="text-sm text-gray-500 mt-1">Drag & drop tasks to update their status</p>
            </div>
            
            <div class="flex items-center gap-3">
                {{-- Project Selector --}}
                <select id="project-selector" 
                        class="border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        onchange="window.location.href='{{ route('kanban.index') }}?project_id=' + this.value">
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" 
                            {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Add Task Button --}}
                @if($selectedProjectId)
                    <a href="{{ route('tasks.create', ['project_id' => $selectedProjectId]) }}" 
                       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Task
                    </a>
                @endif
            </div>
        </div>

        {{-- Empty State --}}
        @if($projects->count() == 0)
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Projects Yet</h3>
                <p class="text-sm text-gray-500 mb-4">Create a project first to use Kanban board</p>
                <a href="{{ route('projects.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
                    Create Project
                </a>
            </div>
        @else

            {{-- Kanban Columns --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                @foreach($statuses as $statusKey => $statusLabel)
                    <div class="flex flex-col">
                        
                        {{-- Column Header --}}
                        <div class="flex items-center justify-between mb-3 px-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                    {{ $statusLabel }}
                                </h3>
                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold rounded-full
                                    @if($statusKey == 'todo') bg-gray-200 text-gray-700
                                    @elseif($statusKey == 'in_progress') bg-blue-100 text-blue-700
                                    @elseif($statusKey == 'review') bg-purple-100 text-purple-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ isset($tasks[$statusKey]) ? $tasks[$statusKey]->count() : 0 }}
                                </span>
                            </div>
                        </div>

                        {{-- Tasks Container --}}
                        <div class="kanban-column bg-gray-100 rounded-xl p-3 min-h-[500px] flex-1"
                             data-status="{{ $statusKey }}">

                            @if(isset($tasks[$statusKey]) && $tasks[$statusKey]->count() > 0)
                                @foreach($tasks[$statusKey] as $task)
                                    <div class="task-card bg-white rounded-lg p-3 mb-2 shadow-sm border border-gray-200 cursor-move hover:shadow-md transition-shadow"
                                         data-task-id="{{ $task->id }}">

                                        {{-- Task Title --}}
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <a href="{{ route('tasks.show', $task) }}" 
                                               class="font-medium text-sm text-gray-900 hover:text-indigo-600 line-clamp-2">
                                                {{ $task->title }}
                                            </a>
                                        </div>

                                        {{-- Priority Badge --}}
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                                @if($task->priority == 'urgent') bg-red-100 text-red-700
                                                @elseif($task->priority == 'high') bg-orange-100 text-orange-700
                                                @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-700
                                                @else bg-green-100 text-green-700 @endif">
                                                {{ $task->priority }}
                                            </span>

                                            @if($task->due_date)
                                                <span class="text-[10px] text-gray-500
                                                    @if($task->due_date->isPast() && $task->status != 'done')
                                                        text-red-600 font-semibold
                                                    @endif">
                                                    📅 {{ $task->due_date->format('M d') }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Assignee --}}
                                        @if($task->assignee)
                                            <div class="flex items-center gap-1.5 pt-2 border-t border-gray-100">
                                                <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-white text-[10px] font-semibold">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                </div>
                                                <span class="text-[11px] text-gray-600 truncate">
                                                    {{ $task->assignee->name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 text-gray-400 text-xs">
                                    No tasks
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach

            </div>
        @endif

    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="fixed bottom-5 right-5 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span id="toast-message" class="text-sm font-medium">Task updated!</span>
    </div>
</div>
@endsection

