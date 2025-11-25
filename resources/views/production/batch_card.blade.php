@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">

        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Batch Cards</h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('batch.flow.card') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 bg-white p-4 rounded-lg shadow-lg">
            <select name="product_id" class="border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Select Product --</option>
                @foreach(\App\Models\Product::all() as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->sku }} - {{ $product->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="from_date" value="{{ request('from_date') }}" class="border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500">

             <button type="submit" class="search-btn">🔍 Search</button>
        </form>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col overflow-hidden">
                    
                    <!-- Product Image -->
                    <div class="h-48 w-full overflow-hidden">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                             alt="{{ $product->name }}" 
                             class="h-full w-full object-cover transition-transform duration-500 hover:scale-110">
                    </div>

                    <!-- Product Info -->
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-500 text-sm mb-1">SKU: {{ $product->sku }}</p>
                            <p class="text-gray-400 text-sm mb-2">Created: {{ $product->created_at->format('Y-m-d') }}</p>
                            @if($product->description)
                                <p class="text-gray-400 text-sm">{{ Str::limit($product->description, 60) }}</p>
                            @endif
                        </div>

                        <!-- View Button -->
                        <a href="{{ route('products.show', $product->id) }}" 
                           class="mt-4 inline-block text-center bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold py-2 rounded-lg shadow-md hover:from-indigo-600 hover:to-purple-600 transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 text-lg mt-10">No products found.</p>
            @endforelse
        </div>
    </div>
</div>


<style>
/* Search button */
.search-btn {
    background-color: #4f46e5; /* Indigo */
    color: white;
    font-weight: 600;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.search-btn:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
}

.search-btn:active {
    background-color: #3730a3;
    transform: translateY(1px);
}
</style>
@endsection
