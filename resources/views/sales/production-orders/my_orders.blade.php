@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-900">{{ __('My Orders') }}</h1>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('production-orders.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
        <div class="flex flex-col">
            <label for="status" class="font-semibold mb-1">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">All</option>
                @foreach(['pending','processing','accepted','rejected','shipping','delivered'] as $status)
                    <option value="{{ $status }}" {{ ($statusFilter ?? '') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="client" class="font-semibold mb-1">Client</label>
            <select name="client" id="client" class="form-control">
                <option value="">All</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ ($clientFilter ?? '') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Filter</button>
    </form>

    @if($allOrders->isEmpty())
        <div class="bg-yellow-50 p-4 rounded-md text-yellow-700">
            {{ __('No orders found.') }}
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($allOrders as $wrapper)
                @php
                    $order = $wrapper->data;
                    $type = $wrapper->type;

                    $clientName = $order->clientOrder?->client?->name
                                  ?? $order->client?->name
                                  ?? $order->user?->name
                                  ?? '-';
                @endphp

                <div class="bg-white p-4 sm:p-6 rounded-lg shadow hover:bg-gray-50 transition">
                    <h3 class="text-lg font-semibold mb-2">
                        {{ __('Order #') . ($order->id ?? '-') }}
                        @if($type === 'production')
                            <span class="text-sm text-gray-500">(Production)</span>
                        @endif
                    </h3>
                    <p><strong>{{ __('Client:') }}</strong> {{ $clientName }}</p>
                    <p><strong>{{ __('Status:') }}</strong> {{ ucfirst($order->status ?? '-') }}</p>
                    <p><strong>{{ __('Total Amount:') }}</strong> ₹{{ number_format($order->total ?? 0, 2) }}</p>

                    <a href="{{ $type === 'production' && $order->id ? route('production-orders.show', $order->id) : '#' }}" 
                       class="text-blue-600 hover:underline mt-2 inline-block">
                        {{ __('View Details') }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
