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
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="grid grid-cols-4 gap-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search tasks..."
                       class="border-gray-300 rounded-md">
                
                <select name="status" class="border-gray-300 rounded-md">
                    <option value="">All Status</option>
                    <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                </select>

                <select name="priority" class="border-gray-300 rounded-md">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>

                <button type="submit" 
                        class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
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