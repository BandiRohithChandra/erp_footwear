@extends('layouts.app')

@section('content')
<div class="products-container">

    {{-- Back Button --}}
    <div class="back-button">
        <button onclick="window.history.back()">← Back</button>
    </div>

    

    {{-- Page Header --}}
    <div class="products-header">
        <h1>{{ ucfirst($category) }} Products</h1>
        <p>Discover a curated selection of products in the "{{ $category }}" category.</p>
    </div>

    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                @php
    $imagePath = $product->image;

    // If main image is empty, use the first variation image
    if (empty($imagePath) && !empty($product->variations)) {
        $variations = is_array($product->variations) ? $product->variations : json_decode($product->variations, true);
        if (!empty($variations) && !empty($variations[0]['images']) && count($variations[0]['images']) > 0) {
            $imagePath = $variations[0]['images'][0];
        }
    }

    // Correct URL for browser
    $imageUrl = $imagePath ? asset('storage/' . ltrim($imagePath, '/')) : null;
@endphp

                <a href="{{ route('client.products.show', $product->id) }}" class="product-card">
                    <div class="product-image">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                            

                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p>₹{{ number_format($product->price, 2) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination">
            {{ $products->links() }}
        </div>
    @else
        <p class="no-products">No products found in this category.</p>
    @endif
</div>

<style>
/* Container */
.products-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 60px 20px;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(120deg, #f0f9ff, #fef6fb);
    color: #111827;
}

/* Back Button */
.back-button {
    margin-bottom: 30px;
}

.back-button button {
    background-color: #6366f1; /* Indigo */
    color: #ffffff;
    padding: 10px 18px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.back-button button:hover {
    background-color: #4f46e5;
}

/* Page Header */
.products-header {
    text-align: center;
    margin-bottom: 50px;
}

.products-header h1 {
    font-size: 44px;
    font-weight: 800;
    color: #1f2937; 
    margin-bottom: 12px;
}

.products-header p {
    font-size: 17px;
    color: #6b7280; 
}

/* Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 35px;
}

/* Card */
.product-card {
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15); /* Indigo shadow */
    text-decoration: none;
    color: inherit;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(99, 102, 241, 0.25);
}

/* Product Image */
/* Product Image */
.product-image {
    width: 100%;
    height: 300px; /* Fixed height like e-commerce sites */
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-bottom: 1px solid #e5e7eb; /* optional separator */
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* ensures entire product is visible without stretching */
    transition: transform 0.3s ease;
}
.product-card:hover .product-image img {
    transform: scale(1.05);
}

/* Optional: for placeholder image */
.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    color: #9ca3af;
    font-size: 16px;
}
/* Product Info */
.product-info {
    padding: 22px;
    text-align: center;
}

.product-info h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #111827;
}

.product-info p {
    font-size: 16px;
    color: #374151;
    font-weight: 500;
}

/* Pagination */
.pagination {
    margin-top: 40px;
    text-align: center;
}

/* No Products */
.no-products {
    text-align: center;
    color: #6b7280;
    font-size: 18px;
    margin-top: 50px;
}

/* Responsive */
@media (max-width: 768px) {
    .products-container {
        padding: 40px 15px;
    }

    .products-header h1 {
        font-size: 34px;
    }
}
</style>
@endsection
