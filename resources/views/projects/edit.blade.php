@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Project</h1>

            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Project Name *
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $project->name) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('name')
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
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Color --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        Color
                    </label>
                    <input type="color" 
                           name="color" 
                           value="{{ old('color', $project->color) }}"
                           class="w-20 h-10 border-gray-300 rounded-md">
                    @error('color')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('projects.show', $project) }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection