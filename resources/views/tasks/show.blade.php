@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Task Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                        
                        <span class="text-xs px-3 py-1 rounded-full
                            @if($task->priority == 'urgent') bg-red-100 text-red-800
                            @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($task->priority) }}
                        </span>

                        <span class="text-xs px-3 py-1 rounded-full bg-indigo-100 text-indigo-800">
                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                        </span>
                    </div>
                    
                    <p class="text-gray-500 text-sm">
                        Project: <a href="{{ route('projects.show', $task->project) }}" 
                                    class="text-indigo-600 hover:underline">
                            {{ $task->project->name }}
                        </a>
                    </p>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" 
                       class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md">
                        Edit
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" 
                          method="POST"
                          onsubmit="return confirm('Delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            {{-- Description --}}
            @if($task->description)
                <div class="border-t pt-4 mt-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
                    <p class="text-gray-600">{{ $task->description }}</p>
                </div>
            @endif
        </div>

        {{-- Task Details --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Details</h2>
            
            <div class="grid grid-cols-2 gap-4">
                {{-- Assignee --}}
                <div>
                    <p class="text-sm text-gray-500">Assigned To</p>
                    <p class="font-semibold">
                        {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}
                    </p>
                </div>

                {{-- Due Date --}}
                <div>
                    <p class="text-sm text-gray-500">Due Date</p>
                    <p class="font-semibold
                        @if($task->due_date && $task->due_date->isPast() && $task->status != 'done')
                            text-red-600
                        @endif">
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No deadline' }}
                        @if($task->due_date && $task->due_date->isPast() && $task->status != 'done')
                            (Overdue)
                        @endif
                    </p>
                </div>

                {{-- Status --}}
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-semibold">{{ str_replace('_', ' ', ucfirst($task->status)) }}</p>
                </div>

                {{-- Priority --}}
                <div>
                    <p class="text-sm text-gray-500">Priority</p>
                    <p class="font-semibold">{{ ucfirst($task->priority) }}</p>
                </div>

                {{-- Created --}}
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="font-semibold">{{ $task->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Comments --}}
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">
        Comments ({{ $task->comments->count() }})
    </h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Existing Comments --}}
    @if($task->comments->count() > 0)
        <div class="space-y-4 mb-6">
            @foreach($task->comments as $comment)
                <div class="border-l-4 border-indigo-500 pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $comment->user->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                            <form action="{{ route('comments.destroy', $comment) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Delete this comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-xs">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                    <p class="text-gray-700 mt-2">{{ $comment->body }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 mb-6">No comments yet. Be the first to comment!</p>
    @endif

    {{-- Add Comment Form --}}
    <form action="{{ route('comments.store', $task) }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <textarea name="body" 
                      rows="3"
                      placeholder="Write a comment..."
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      required>{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                Post Comment
            </button>
        </div>
    </form>
</div>
{{-- Attachments --}}
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">
        Attachments ({{ $task->attachments->count() }})
    </h2>

    {{-- Existing Attachments --}}
    @if($task->attachments->count() > 0)
        <div class="space-y-3 mb-6">
            @foreach($task->attachments as $attachment)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <a href="{{ Storage::url($attachment->file_path) }}" 
                               target="_blank"
                               class="font-medium text-gray-900 hover:text-indigo-600">
                                {{ $attachment->original_name }}
                            </a>
                            <p class="text-xs text-gray-500">
                                {{ $attachment->human_size }} · Uploaded by {{ $attachment->user->name }} · {{ $attachment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ Storage::url($attachment->file_path) }}" 
                           download
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Download
                        </a>
                        
                        @if($attachment->user_id === auth()->id() || auth()->user()->isAdmin())
                            <form action="{{ route('attachments.destroy', $attachment) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Delete this file?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 mb-6">No attachments yet.</p>
    @endif

    {{-- Upload Form --}}
    <form action="{{ route('attachments.store', $task) }}" 
          method="POST"
          enctype="multipart/form-data">
        @csrf
        
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-indigo-400 transition-colors">
            <input type="file" 
                   name="file" 
                   class="w-full text-sm text-gray-600"
                   required>
            @error('file')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-2">
                Max 10 MB. Allowed: PDF, DOC, DOCX, PNG, JPG, ZIP, TXT
            </p>
        </div>

        <div class="flex justify-end mt-3">
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                Upload File
            </button>
        </div>
    </form>
</div>

        {{-- Back --}}
        <div class="mt-6">
            <a href="{{ route('projects.show', $task->project) }}" 
               class="text-indigo-600 hover:text-indigo-800">
                ← Back to Project
            </a>
        </div>
    </div>
</div>
@endsection