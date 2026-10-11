@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full mr-3" 
                         style="background-color: {{ $project->color }}"></div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}" 
                       class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md">
                        Edit
                    </a>
                    <form action="{{ route('projects.destroy', $project) }}" 
                          method="POST"
                          onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-gray-600 mt-4">{{ $project->description ?? 'No description' }}</p>
            <div class="mt-4 text-sm text-gray-500">
                Created by: <strong>{{ $project->creator->name }}</strong>
            </div>
        </div>

        {{-- Members --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Members ({{ $project->members->count() }})</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($project->members as $member)
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">
                        {{ $member->name }} ({{ $member->pivot->role }})
                    </span>
                @endforeach
            </div>
        </div>

      {{-- Tasks --}}
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Tasks ({{ $project->tasks->count() }})</h2>
        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm">
            + Add Task
        </a>
    </div>
    
    @if($project->tasks->count() > 0)
        <div class="space-y-2">
            @foreach($project->tasks as $task)
                <div class="border-l-4 pl-4 py-2
                    @if($task->status == 'done') border-green-500
                    @elseif($task->status == 'in_progress') border-yellow-500
                    @elseif($task->status == 'review') border-purple-500
                    @else border-gray-300 @endif">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('tasks.show', $task) }}" 
                                   class="font-semibold hover:text-indigo-600">
                                    {{ $task->title }}
                                </a>
                                
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($task->priority == 'urgent') bg-red-100 text-red-800
                                    @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                    @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $task->priority }}
                                </span>
                            </div>
                            
                            @if($task->assignee)
                                <p class="text-xs text-gray-500 mt-1">
                                    Assigned to: {{ $task->assignee->name }}
                                </p>
                            @endif
                        </div>
                        
                        <span class="text-xs text-gray-500">
                            {{ str_replace('_', ' ', $task->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No tasks yet. Create your first task!</p>
    @endif
</div>

        {{-- Back --}}
        <div class="mt-6">
            <a href="{{ route('projects.index') }}" 
               class="text-indigo-600 hover:text-indigo-800">
                ← Back to Projects
            </a>
        </div>
    </div>
</div>
@endsection