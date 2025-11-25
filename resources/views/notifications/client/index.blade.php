@extends('layouts.app')

@section('content')
<div class="container">
    <div class="header">
        <h1>🔔 Order Notifications</h1>
    </div>

    <!-- Back Button -->
<button type="button" onclick="history.back()" 
        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Back
</button>


    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($notifications->isEmpty())
        <div class="no-notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>No new notifications yet.</span>
        </div>
    @else
        {{-- Mark All as Read Button --}}
        <div class="mark-all">
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                @csrf
                <button type="submit">Mark All as Read</button>
            </form>
        </div>

        <div class="notifications-list">
            @foreach($notifications as $notification)
                @php
                    $data = $notification->data;
                    $status = strtolower($data['status'] ?? '');
                @endphp

                <div class="notification-card {{ $status }}">
                    <div class="icon">
                        @switch($status)
                            @case('pending') ⏳ @break
                            @case('delivered') ✅ @break
                            @case('rejected') 
                            @case('cancelled') ❌ @break
                            @default 📦
                        @endswitch
                    </div>
                    <div class="content">
                        <div class="message-row">
                            <p>{{ $data['message'] ?? 'Order update' }}</p>
                            @if(!$notification->read_at)
                                <span class="badge">New</span>
                            @endif
                        </div>
                        @if(isset($data['order_id']))
                            <a href="{{ route('client.orders.show', $data['order_id']) }}" class="view-link">View Order</a>
                        @endif
                        <p class="time">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)
                        <div class="actions">
                            <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit">Mark as Read</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
/* Container */
.container {
    max-width: 700px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Header */
.header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1c1c1e;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
}

/* Flash success */
.alert-success {
    background: #d1fae5;
    color: #065f46;
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
}

/* No notifications */
.no-notifications {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9f9f9;
    border: 1px solid #e0e0e0;
    padding: 20px;
    border-radius: 24px;
    color: #999;
}

.no-notifications .icon {
    width: 32px;
    height: 32px;
    margin-right: 10px;
}

/* Mark all */
.mark-all {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.mark-all button {
    background: #0a84ff;
    color: #fff;
    padding: 8px 16px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.2s ease;
}

.mark-all button:hover {
    background: #0066cc;
}

/* Notifications list */
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Notification card */
.notification-card {
    display: flex;
    align-items: flex-start;
    background: #fff;
    border-left: 6px solid #ccc;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    transition: box-shadow 0.3s ease;
}

.notification-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

/* Status colors */
.notification-card.pending { border-color: #facc15; }
.notification-card.delivered { border-color: #22c55e; }
.notification-card.rejected, .notification-card.cancelled { border-color: #ef4444; }

/* Icon */
.notification-card .icon {
    font-size: 24px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 20px;
}

.notification-card.pending .icon { background: #fef3c7; color: #b45309; }
.notification-card.delivered .icon { background: #d1fae5; color: #166534; }
.notification-card.rejected .icon, .notification-card.cancelled .icon { background: #fee2e2; color: #991b1b; }
.notification-card.default .icon { background: #f3f4f6; color: #6b7280; }

/* Content */
.notification-card .content {
    flex: 1;
}

.notification-card .message-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notification-card p {
    margin: 0;
    font-size: 16px;
    color: #1c1c1e;
}

.badge {
    background: #0a84ff;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 12px;
}

/* View link */
.view-link {
    display: inline-block;
    margin-top: 6px;
    font-size: 14px;
    color: #0a84ff;
    text-decoration: none;
}

.view-link:hover {
    text-decoration: underline;
}

/* Time */
.time {
    font-size: 12px;
    color: #999;
    margin-top: 4px;
}

/* Actions */
.actions button {
    background: #f3f4f6;
    border: none;
    padding: 6px 12px;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s ease;
}

.actions button:hover {
    background: #e5e7eb;
}
</style>
@endsection
