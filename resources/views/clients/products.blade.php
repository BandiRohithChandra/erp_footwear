@extends('layouts.app')

@section('content')
<style>
/* Container & Heading */
body, .container { font-family: 'Roboto', sans-serif; background: #f9fafb; color: #1f2937; }
h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 30px; text-align: center; color: #111827; }

/* Product Card (Grid Mode) */
.erp-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border-radius: 12px;
    overflow: hidden;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    border: 1px solid #e5e7eb;
}
.erp-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.erp-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.erp-card img:hover { transform: scale(1.05); }

.erp-card-content {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(90deg, #4f46e5, #4338ca);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    display: inline-block;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #4338ca, #3730a3);
    transform: translateY(-2px);
}

/* Product Info */
.erp-card h2 { font-size: 1.1rem; font-weight: 600; margin-bottom: 5px; color: #111827; }
.erp-card p { margin-bottom: 6px; color: #4b5563; font-size: 0.9rem; }

/* Price */
.erp-card .price { font-size: 1.05rem; font-weight: 700; color: #111827; }

/* Grid */
.grid { 
    display: grid; 
    gap: 20px; 
}

/* Make button clickable only, no nested link issues */
.erp-card-link { text-decoration: none; color: inherit; }

/* Pagination */
.mt-6 { margin-top: 1.5rem; text-align: center; }

/* Dropdown Toggle */
.view-toggle {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}
.view-toggle select {
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    background: #fff;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.95rem;
    color: #374151;
}

/* --- LIST VIEW AS TABLE --- */
.list-view {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.list-view th, .list-view td {
    padding: 10px 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    font-size: 0.9rem;
    color: #374151;
}
.list-view th {
    background: #f9fafb;
    font-weight: 600;
}
.list-view img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
}
</style>

@php
    $currentView = request()->get('view', '4'); // default grid 4
@endphp

<div class="container mx-auto" x-data="{ view: '{{ $currentView }}' }">
    <h1>🛍️ Products / Articles</h1>

    {{-- Grid/List dropdown --}}
    <div class="view-toggle">
        <select x-model="view" @change="window.location.href = '?view=' + view">
            <option value="5">5 per row</option>
            <option value="4">4 per row</option>
            <option value="1">List view</option>
        </select>
    </div>

    {{-- Products Grid --}}
    <div x-show="view !== '1'" class="grid" 
         :style="'grid-template-columns: repeat(' + view + ', 1fr);'">
        @foreach($products as $product)
            @php
                $imagePath = $product->image;
                if (empty($imagePath) && !empty($product->variations)) {
                    $variations = is_array($product->variations) ? $product->variations : json_decode($product->variations, true);
                    if (!empty($variations) && !empty($variations[0]['image'])) {
                        $imagePath = $variations[0]['image'];
                    }
                }
            @endphp

            <a href="{{ route('client.products.show', $product->id) }}" class="erp-card-link">
                <div class="erp-card flex flex-col transition hover:shadow-lg">
                    @if(!empty($imagePath) && file_exists(public_path('storage/' . $imagePath)))
                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}">
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500 rounded">
                            No Image
                        </div>
                    @endif

                    <div class="erp-card-content flex flex-col justify-between p-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                            <p class="text-gray-500 text-sm">SKU: {{ $product->sku }}</p>
                            <p class="price font-bold text-gray-900 mt-1">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        <div class="btn-primary mt-3 text-center cursor-pointer">
                            View Product
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Products List View (Table) --}}
   {{-- Products List View (Table) --}}
<table x-show="view === '1'" class="list-view">
    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Article No</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        @php
            $imagePath = $product->image;
            if (empty($imagePath) && !empty($product->variations)) {
                $variations = is_array($product->variations) ? $product->variations : json_decode($product->variations, true);
                if (!empty($variations) && !empty($variations[0]['image'])) {
                    $imagePath = $variations[0]['image'];
                }
            }
        @endphp
        <tr 
            class="cursor-pointer hover:bg-gray-50 transition-colors"
            onclick="window.location='{{ route('client.products.show', $product->id) }}'"
        >
            <td>
                @if(!empty($imagePath) && file_exists(public_path('storage/' . $imagePath)))
                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}">
                @else
                    <span class="text-gray-400 text-sm">No Image</span>
                @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->sku }}</td>
            <td>₹{{ number_format($product->price, 2) }}</td>
            <td>
                <a href="{{ route('client.products.show', $product->id) }}" class="btn-primary" onclick="event.stopPropagation();">
                    View
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


    {{-- Pagination --}}
    <div class="mt-6">
        {{ $products->appends(['view' => $currentView])->links() }}
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
