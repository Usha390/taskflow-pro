@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Activity Log</h1>
            <p class="text-sm text-gray-500 mt-1">Track all actions across your projects</p>
        </div>

        {{-- Activities List --}}
        @if($activities->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @foreach($activities as $activity)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3">
                                
                                {{-- Icon --}}
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $activity->color }}">
                                    <span class="text-lg">{{ $activity->icon }}</span>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold text-sm text-gray-900">
                                            {{ $activity->user?->name ?? 'System' }}
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            {{ $activity->description }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">No activity yet</h3>
                <p class="text-sm text-gray-500">Start creating tasks and projects to see activity</p>
            </div>
        @endif

    </div>
</div>
@endsection