@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                <p class="text-sm text-gray-500 mt-1">Your recent activity</p>
            </div>

            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button type="submit" 
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifications List --}}
        @if($notifications->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @foreach($notifications as $notification)
                        <form method="POST" 
      action="{{ route('notifications.read', $notification->id) }}"
      class="ajax-notification-form">
    @csrf
    <button type="submit" 
            class="ajax-notification-btn w-full text-left p-4 hover:bg-gray-50 transition-colors
                {{ $notification->read_at ? '' : 'bg-indigo-50/30' }}"
            data-notification-id="{{ $notification->id }}">
                                <div class="flex items-start gap-3">
                                    
                                    {{-- Icon --}}
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $notification->read_at ? 'bg-gray-100' : 'bg-indigo-100' }}">
                                        <svg class="w-5 h-5 {{ $notification->read_at ? 'text-gray-500' : 'text-indigo-600' }}" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm {{ $notification->read_at ? 'text-gray-600' : 'text-gray-900 font-medium' }}">
                                            {{ $notification->data['message'] ?? 'New notification' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    {{-- Unread indicator --}}
                                   @if(!$notification->read_at)
    <div class="unread-dot w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0 mt-2"></div>
@endif

                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">No notifications</h3>
                <p class="text-sm text-gray-500">You're all caught up!</p>
            </div>
        @endif

    </div>
</div>
@endsection