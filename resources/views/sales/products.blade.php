@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ view: '{{ request()->query('view', '4') }}' }">
    
    <h1 class="text-3xl sm:text-4xl font-extrabold text-center mb-8 text-gray-900">🛍️ Our Products</h1>

    {{-- Back Button --}}
    <div class="mb-6 flex justify-start">
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-200 font-medium">
            &larr; Back
        </a>
    </div>

    {{-- View toggle --}}
    <div class="flex justify-end mb-6">
        <select x-model="view" @change="window.location='{{ url()->current() }}?view=' + view"
                class="px-4 py-2 border rounded-lg bg-white text-gray-700 font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="5" {{ request()->query('view') == '5' ? 'selected' : '' }}>5 per row</option>
            <option value="4" {{ request()->query('view', '4') == '4' ? 'selected' : '' }}>4 per row</option>
            <option value="1" {{ request()->query('view') == '1' ? 'selected' : '' }}>List view</option>
        </select>
    </div>

    {{-- Grid View --}}
    <div x-show="view !== '1'" 
         class="grid gap-6
                grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @foreach($products as $product)
            @php
                $imagePath = null;
                if(!empty($product->image)) {
                    $imagePath = is_array($product->image) ? $product->image[0] : $product->image;
                } elseif(!empty($product->variations)) {
                    $firstVarImages = $product->variations[0]['images'] ?? [];
                    if(!empty($firstVarImages)) {
                        $imagePath = $firstVarImages[0];
                    }
                }
                $imageUrl = $imagePath ? asset('storage/' . ltrim($imagePath, '/')) : null;
            @endphp
            <a href="{{ route('sales.products.show', $product->id) }}">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow hover:shadow-lg transition duration-300 flex flex-col">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-48 sm:h-56 md:h-48 object-contain bg-gray-50">
                    @else
                        <div class="w-full h-48 sm:h-56 md:h-48 flex items-center justify-center bg-gray-100 text-gray-400 font-semibold">
                            No Image
                        </div>
                    @endif
                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ $product->name }}</h2>
                            <p class="text-gray-500 text-sm mt-1">Article No: {{ $product->sku }}</p>
                            <p class="text-indigo-600 font-semibold mt-2 text-lg">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        <span class="mt-4 inline-block text-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium text-sm hover:bg-indigo-700 transition duration-200">
                            View
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- List View --}}
<div x-show="view === '1'" class="w-full overflow-x-auto">
    <table class="min-w-full border border-gray-200 bg-white shadow-md divide-y divide-gray-200">
        <thead class="bg-gray-50 text-gray-700 font-semibold uppercase text-sm">
            <tr>
                <th class="px-4 py-3 text-left">Image</th>
                <th class="px-4 py-3 text-left">Product Name</th>
                <th class="px-4 py-3 text-left">Article No</th>
                <th class="px-4 py-3 text-left">Price</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($products as $product)
                @php
                    $imagePath = null;
                    if(!empty($product->image)) {
                        $imagePath = is_array($product->image) ? $product->image[0] : $product->image;
                    } elseif(!empty($product->variations)) {
                        $firstVarImages = $product->variations[0]['images'] ?? [];
                        if(!empty($firstVarImages)) {
                            $imagePath = $firstVarImages[0];
                        }
                    }
                    $imageUrl = $imagePath ? asset('storage/' . ltrim($imagePath, '/')) : null;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-16 h-16 object-contain rounded bg-gray-50">
                        @else
                            <div class="w-16 h-16 flex items-center justify-center bg-gray-100 text-gray-400 rounded">
                                No Image
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->sku }}</td>
                    <td class="px-4 py-3 text-indigo-600 font-semibold">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('sales.products.show', $product->id) }}" 
                           class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
                           View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


    {{-- Pagination --}}
    <div class="mt-8 text-center">
        {{ $products->appends(['view' => request()->query('view', '4')])->links() }}
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
