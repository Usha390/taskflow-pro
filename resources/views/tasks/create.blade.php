@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Create New Task</h1>

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                {{-- Project --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Project *
                    </label>
                    <select name="project_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        <option value="">-- Select Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" 
                                {{ old('project_id', $selectedProject) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Task Title *
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="4"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status + Priority --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            Status *
                        </label>
                        <select name="status" 
                                class="w-full border-gray-300 rounded-md"
                                required>
                            <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Review</option>
                            <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            Priority *
                        </label>
                        <select name="priority" 
                                class="w-full border-gray-300 rounded-md"
                                required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
{{-- Assigned To --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">
        Assigned To
    </label>
    <select name="assigned_to" 
            class="w-full border-gray-300 rounded-md">
        <option value="">-- Unassigned --</option>
        @foreach(\App\Models\User::all() as $user)
            @if($user->role === 'developer')
                <option value="{{ $user->id }}" 
                    {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endif
        @endforeach
    </select>
    @error('assigned_to')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
                {{-- Due Date --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        Due Date
                    </label>
                    <input type="date" 
                           name="due_date" 
                           value="{{ old('due_date') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('projects.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection