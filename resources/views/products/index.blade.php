@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Articles') }}</h1>
       @if(auth()->user() && auth()->user()->is_remote == 0)
    <a href="{{ route('products.create') }}"
       class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
        + {{ __('Add New Article') }}
    </a>
@endif



    </div>

    @if(auth()->check() && auth()->user()->hasRole('Admin') && auth()->user()->is_remote == 0)
<div x-data="{
        syncing: false,
        progress: 0,
        startSync() {
            this.syncing = true;
            this.progress = 0;
            const totalDuration = 2000; // 2 seconds
            const steps = 60; // frames
            const increment = 100 / steps;
            let currentStep = 0;

            let interval = setInterval(() => {
                if (currentStep < steps) {
                    currentStep++;
                    this.progress = increment * currentStep;
                } else {
                    this.progress = 100;
                    clearInterval(interval);
                    setTimeout(() => this.syncing = false, 500);
                }
            }, totalDuration / steps); // ~33ms per frame
            $nextTick(() => $refs.syncForm.submit());
        }
    }" class="mt-4 flex flex-col items-center gap-4">

    {{-- Sync Button --}}
    <button 
        @click="startSync()"
        :disabled="syncing"
        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
        <span x-show="!syncing">Sync to Online</span>
        <span x-show="syncing">Syncing...</span>
    </button>

    {{-- Speedometer Animation --}}
    <div x-show="syncing" class="relative w-40 h-20">
        <svg viewBox="0 0 200 100" class="w-full h-full">
            <!-- Background semi-circle -->
            <path d="M10,90 A90,90 0 0,1 190,90" stroke="#e5e7eb" stroke-width="8" fill="none" />
            
            <!-- Progress Arc -->
            <path 
                x-bind:d="`M10,90 A90,90 0 0,1 ${10 + 180 * (progress/100)},90`" 
                stroke="url(#grad)" stroke-width="8" fill="none" stroke-linecap="round" />

            <!-- Gradient Definition -->
            <defs>
                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#3b82f6" />
                    <stop offset="100%" stop-color="#22c55e" />
                </linearGradient>
            </defs>

            <!-- Needle -->
            <line x1="100" y1="90"
                  x-bind:x2="100 + 80 * Math.cos(Math.PI * (1 - progress/100))"
                  x-bind:y2="90 - 80 * Math.sin(Math.PI * (1 - progress/100))"
                  stroke="#ef4444" stroke-width="3" stroke-linecap="round" />

            <!-- Center Circle -->
            <circle cx="100" cy="90" r="4" fill="#111827" />
        </svg>

        <!-- Percentage Display -->
        <div class="absolute inset-0 flex items-center justify-center font-bold text-gray-800 text-lg" x-text="Math.floor(progress) + '%'"></div>
    </div>

    {{-- Hidden Form --}}
    <form x-ref="syncForm" method="POST" action="{{ route('products.sync') }}" class="hidden">
        @csrf
    </form>
</div>
@endif



{{-- Search, Export & Import --}}
<form method="GET" action="{{ route('products.index') }}"
      class="mb-8 bg-gray-50 p-4 rounded-lg shadow-sm">

    <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

        {{-- Left: Search input + button --}}
        <div class="flex items-center gap-2 w-full lg:w-auto">
            <input type="text" name="search" value="{{ request()->query('search') }}"
                   placeholder="{{ __('Search by name or SKU') }}"
                   class="border rounded-lg px-3 py-2 w-full sm:w-80 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm shadow-sm">
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 text-sm font-medium shadow-sm">
                <i class="fas fa-search"></i>
                <span>{{ __('Search') }}</span>
            </button>
        </div>

        {{-- Right: Action buttons --}}
        <div class="flex items-center gap-2 w-full lg:w-auto lg:justify-end">
            <a href="{{ route('products.export') }}"
               class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium shadow-sm">
                <i class="fas fa-file-export"></i>
                <span>{{ __('Export CSV') }}</span>
            </a>
            <a href="{{ route('products.import') }}"
               class="flex items-center gap-2 px-5 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm font-medium shadow-sm">
                <i class="fas fa-file-import"></i>
                <span>{{ __('Import CSV') }}</span>
            </a>
        </div>

    </div>
</form>




    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-6 text-sm shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Products --}}
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($products as $product)
            @php
                $variations = is_array($product->variations) ? $product->variations : (json_decode($product->variations, true) ?? []);
            @endphp

            {{-- Product Card --}}
            <div class="border rounded-lg shadow-sm hover:shadow-md transition bg-white flex flex-col">
                <div class="flex justify-between items-start p-4 border-b">
    <div>
        <h2 class="font-semibold text-lg text-gray-800">{{ $product->name }}</h2>
        <p class="text-sm text-gray-500">
            SKU: {{ $product->sku }} | Type: {{ $product->category ?? '-' }}
        </p>
    </div>

    <div class="inline-flex items-center space-x-3">
        @if(auth()->user() && auth()->user()->is_remote == 0)
    <a href="{{ route('products.edit', $product) }}"
       class="text-blue-600 hover:underline text-sm">{{ __('Edit') }}</a>
@endif


        <form action="{{ route('products.destroy', $product) }}" method="POST"
              onsubmit="return confirm('{{ __('Are you sure?') }}');" class="inline-flex items-center">
            @csrf @method('DELETE')
            <button type="submit"
                    class="text-red-600 hover:underline text-sm">{{ __('Delete') }}</button>
        </form>
    </div>
</div>


                {{-- Variations --}}
                @if(count($variations) > 0)
                    <div class="divide-y flex-1">
                        @foreach ($variations as $variation)
                            @php
                                $images = is_array($variation['images'] ?? null) ? $variation['images'] : (json_decode($variation['images'] ?? '[]', true) ?? []);
                                $firstImage = $images[0] ?? null;
                            @endphp
                            <div class="flex items-center p-4 hover:bg-gray-50 cursor-pointer"
                                 onclick="window.location='{{ route('products.show', $product) }}'">
                                <div class="flex-shrink-0">
                                    @if($firstImage)
    <img src="{{ asset('storage/' . $firstImage) }}"
     alt="{{ $variation['color'] ?? '-' }}"
     class="w-16 h-16 object-cover rounded-md shadow-sm">

@else
    <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-gray-400 rounded-md">
        {{ __('No Image') }}
    </div>
@endif


                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="font-medium text-gray-800">
                                        {{ $variation['color'] ?? '-' }} - {{ $variation['size'] ?? '-' }}
                                    </div>
                                    @php
    // Extract available sizes from variation
    $availableSizes = $variation['sizes'] ?? [];

    // Get HSN code (from variation or fallback to product)
    $hsnCode = $variation['hsn_code'] ?? ($product->hsn_code ?? '-');
@endphp

<div class="text-sm text-gray-600">
    🧾 <strong>HSN:</strong> {{ $hsnCode }} |
    👟 <strong>Sizes:</strong>
    @if(!empty($availableSizes))
        {{ implode(', ', $availableSizes) }}
    @else
        <span class="text-gray-400">No sizes</span>
    @endif
</div>

                                </div>
                                <div class="ml-auto text-right">
                                    <span class="text-gray-700 font-semibold">{{ $variation['quantity'] ?? 0 }}</span>
                                    <div class="text-xs text-gray-500">{{ __('Qty') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-gray-500 text-sm">{{ __('No variations available') }}</div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 text-gray-500 col-span-full">
                {{ __('No products found') }}
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
