@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Projects</h1>
            <a href="{{ route('projects.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                + New Project
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Projects Grid --}}
        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center mb-3">
                            <div class="w-3 h-3 rounded-full mr-2" 
                                 style="background-color: {{ $project->color }}"></div>
                            <h2 class="text-lg font-semibold">{{ $project->name }}</h2>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">
                            {{ Str::limit($project->description, 100) }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                {{ $project->tasks_count }} tasks
                            </span>
                            <a href="{{ route('projects.show', $project) }}" 
                               class="text-indigo-600 hover:text-indigo-800">
                                View →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500 mb-4">No projects yet.</p>
                <a href="{{ route('projects.create') }}" 
                   class="text-indigo-600 hover:text-indigo-800">
                    Create your first project →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection