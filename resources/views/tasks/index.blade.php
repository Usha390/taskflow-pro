@extends('layouts.app')

@section('title', 'All Tasks')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">All Tasks</h1>
            <a href="{{ route('tasks.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                + New Task
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

       {{-- Filters --}}
<div class="bg-white rounded-lg shadow p-5 mb-6">
    <form method="GET" action="{{ route('tasks.index') }}">
        
        {{-- Filter Row 1: Search + Status + Priority --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            {{-- Search --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Search Title</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search tasks..."
                       class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </div>

            {{-- Priority --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Priority</label>
                <select name="priority" class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
        </div>

        {{-- Filter Row 2: Project + Assignee --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Project --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Project</label>
                <select name="project_id" class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Assignee --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Assigned To</label>
                <select name="assigned_to" class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Filter Row 3: Due Date Range --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Due Date From</label>
                <input type="date" 
                       name="due_from" 
                       value="{{ request('due_from') }}"
                       class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Due Date To</label>
                <input type="date" 
                       name="due_to" 
                       value="{{ request('due_to') }}"
                       class="w-full border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
            <div class="text-xs text-gray-500">
                @if(request()->hasAny(['search', 'status', 'priority', 'project_id', 'assigned_to', 'due_from', 'due_to']))
                    <span class="font-medium">Filters active</span>
                @else
                    <span>No filters applied</span>
                @endif
            </div>
            
            <div class="flex gap-2">
                @if(request()->hasAny(['search', 'status', 'priority', 'project_id', 'assigned_to', 'due_from', 'due_to']))
                    <a href="{{ route('tasks.index') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition-colors">
                        Clear All
                    </a>
                @endif
                
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md text-sm font-medium transition-colors">
                    Apply Filters
                </button>
            </div>
        </div>
    </form>
</div>
        {{-- Tasks List --}}
        @if($tasks->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @foreach($tasks as $task)
                    <div class="border-b border-gray-200 p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="font-semibold text-lg">{{ $task->title }}</h3>
                                    
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($task->priority == 'urgent') bg-red-100 text-red-800
                                        @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                        @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $task->priority }}
                                    </span>
                                    
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                        {{ $task->status }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-500 mt-1">
                                    Project: {{ $task->project->name }}
                                    @if($task->assignee)
                                        | Assigned to: {{ $task->assignee->name }}
                                    @endif
                                    @if($task->due_date)
                                        | Due: {{ $task->due_date->format('M d, Y') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('tasks.show', $task) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    View
                                </a>
                                <a href="{{ route('tasks.edit', $task) }}" 
                                   class="text-gray-600 hover:text-gray-800 text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500 mb-4">No tasks yet.</p>
                <a href="{{ route('tasks.create') }}" 
                   class="text-indigo-600 hover:text-indigo-800">
                    Create your first task →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection