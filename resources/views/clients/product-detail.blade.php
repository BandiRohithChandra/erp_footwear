@extends('layouts.app')

@section('content')
<style>
/* ===== Container ===== */
.product-container {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 50px auto;
    gap: 30px;
    font-family: 'Inter', sans-serif;
    padding: 0 20px;
}

/* ===== Back Button ===== */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #4f46e5;
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    margin-bottom: 25px;
}
.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

/* ===== Left: Product Images ===== */
.product-images-wrapper {
    flex: 1 1 45%;
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.main-image-container {
    width: 100%;
    max-width: 500px;
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 16px;
    background: #f9fafb;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 15px;
}

.main-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.main-image:hover { 
    transform: scale(1.05);
}

/* Thumbnails */
.thumbnails-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    overflow-x: auto;
    padding-bottom: 5px;
}
.thumbnails-row::-webkit-scrollbar { height: 6px; }
.thumbnails-row::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
}
.thumbnail {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: 0.3s;
}
.thumbnail:hover { transform: scale(1.1); }
.active-thumbnail { border-color: #4f46e5; }

/* ===== Right: Product Details ===== */
.product-details {
    flex: 1 1 50%;
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.product-title { font-size: 2rem; font-weight: 800; color: #111827; }
.product-price { font-size: 1.8rem; font-weight: 700; color: #4f46e5; }
.product-category, .product-sku { font-size: 0.95rem; color: #6b7280; }
.product-description { font-size: 1rem; color: #374151; line-height: 1.6; }

/* Color Options */
.color-options { display: flex; gap: 12px; flex-wrap: wrap; }
.color-swatch {
    width: 35px; height: 35px; border-radius: 50%;
    border: 2px solid #d1d5db; cursor: pointer; transition: 0.3s;
}
.color-swatch.selected {
    border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
}

/* Size & Quantity */
.size-qty-row {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; background: #f9fafb; padding: 12px 15px;
    border-radius: 10px; border: 1px solid #e5e7eb; transition: 0.2s;
}
.size-qty-row:hover { box-shadow: 0 3px 6px rgba(0,0,0,0.08); }
.quantity-input {
    width: 60px; text-align: center; border-radius: 6px;
    padding: 6px 8px; border: 1px solid #d1d5db;
}

/* Buttons */
.action-buttons { display: flex; gap: 20px; margin-top: 30px; flex-wrap: wrap; }
.btn { flex: 1; min-width: 140px; padding: 12px 0; font-weight: 600; border-radius: 12px; cursor: pointer; border: none; transition: 0.3s; }
.btn-add { background: #10b981; color: #fff; }
.btn-add:hover { background: #059669; }
.btn-buy { background: #f59e0b; color: #fff; }
.btn-buy:hover { background: #d97706; }

/* Toast Notification */
.toast {
    position: fixed; top: 20px; right: 20px; z-index: 9999;
    background: #10b981; color: #fff; padding: 12px 20px;
    border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); cursor: pointer;
}

/* Responsive */
@media(max-width: 992px){
    .product-container { flex-direction: column; gap: 30px; }
    .product-images-wrapper, .product-details { width: 100%; }
    .main-image-container { height: 400px; }
}
@media(max-width: 576px){
    .main-image-container { height: 250px; }
}
</style>

<!-- Back Button -->
<button type="button" onclick="history.back()" class="back-btn">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Back
</button>

<!-- Server-side Toast -->
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show=false, 3000)" x-transition.opacity class="toast">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show=false, 3000)" x-transition.opacity class="toast error">
    {{ session('error') }}
</div>
@endif

<!-- Product Container -->
<div class="product-container" x-data="productData()" x-init="init()">

    <!-- Client-side Toast -->
    <div x-show="toast.show" x-text="toast.message" x-transition.opacity @click="toast.show=false" class="toast"></div>

    <!-- Product Images -->
    <div class="product-images-wrapper">
        <div class="main-image-container">
            <template x-if="selectedImage">
                <img :src="selectedImage" alt="{{ $product->name }}" class="main-image">
            </template>
            <template x-if="!selectedImage">
                <div class="no-image">No Image</div>
            </template>
        </div>

        <div class="thumbnails-row" x-show="currentThumbnails.length > 0">
            <template x-for="img in currentThumbnails" :key="img">
                <img :src="img" @click="selectedImage = img" @mouseover="selectedImage = img"
                     :class="{'active-thumbnail': selectedImage === img}" class="thumbnail">
            </template>
        </div>

        <div class="thumbnail-description mt-3 text-gray-700">
            {{ $product->description }}
        </div>
    </div>

    <!-- Product Details -->
    <div class="product-details">
        <h1 class="product-title">{{ $product->name }}</h1>
        <p class="product-price">₹<span x-text="price.toFixed(2)"></span></p>

        <!-- Color Selection -->
        <div x-show="colors.length > 0">
            <p>Select Color: <span x-text="selectedColor"></span></p>
            <div class="color-options">
                <template x-for="color in colors" :key="color">
                    <div class="color-swatch" :class="{ 'selected': selectedColor === color }"
                         :style="{ backgroundColor: color }" @click="selectColor(color)"></div>
                </template>
            </div>
        </div>

        <!-- Sizes & Quantities -->
        <div class="size-qty-wrapper">
            <template x-for="item in sizeQtyList" :key="item.size">
                <div class="size-qty-row">
                    <span x-text="item.size"></span>
                    <input type="number" min="0" x-model.number="item.qty" class="quantity-input">
                </div>
            </template>
        </div>

        <!-- Add to Cart / Buy Now -->
        <form id="cartForm" method="POST" :action="cartUrl">
            @csrf
            <div class="action-buttons">
                <button type="button" class="btn btn-add" @click="submitCart('products')">Add to Cart</button>
                <button type="button" class="btn btn-buy" @click="submitCart('checkout')">Buy Now</button>
            </div>
        </form>
    </div>
</div>

<script>
function productData() {
    const variations = @json($product->variations ?? []);
    const colors = [...new Set(variations.map(v => v.color))];
    let selectedColor = colors[0] ?? null;

    let quantitiesByColor = {};
    let pricesByColor = {};

    colors.forEach(color => {
        const variant = variations.find(v => v.color === color);
        const sizes = variant?.sizes ? JSON.parse(variant.sizes) : Array.from({length: 10}, (_, i) => i + 35);
        quantitiesByColor[color] = sizes.map(size => ({ size: size, qty: 0 }));
        pricesByColor[color] = Number(variant?.price ?? {{ (float)$product->price }});
    });

    const mainProductImage = '/storage/{{ $product->image ?? ($product->variations[0]->images[0] ?? '') }}';

    return {
        variations,
        colors,
        selectedColor,
        sizeQtyList: quantitiesByColor[selectedColor] ?? [],
        selectedImage: mainProductImage,
        price: Number(pricesByColor[selectedColor] ?? {{ (float)$product->price }}),
        toast: { show: false, message: '' },
        cartUrl: '{{ route('client.cart.add') }}',

        init() {
            if (this.selectedColor) this.updateVariant(this.selectedColor);
        },

        selectColor(color) {
            if (this.selectedColor) quantitiesByColor[this.selectedColor] = this.sizeQtyList;
            this.selectedColor = color;
            this.sizeQtyList = quantitiesByColor[color] || [];
            this.updateVariant(color);
        },

        updateVariant(color) {
            const variant = this.variations.find(v => v.color === color);
            if (variant) {
                this.selectedImage = variant.image
                    ? '/storage/' + variant.image
                    : variant.images?.length ? '/storage/' + variant.images[0] : mainProductImage;
            } else {
                this.selectedImage = mainProductImage;
            }
            this.price = Number(pricesByColor[color] ?? {{ (float)$product->price }});
        },

        get currentThumbnails() {
            const variant = this.variations.find(v => v.color === this.selectedColor);
            if (variant) {
                if (variant.images?.length) return variant.images.map(img => '/storage/' + img);
                if (variant.image) return ['/storage/' + variant.image];
            }
            return mainProductImage ? [mainProductImage] : [];
        },

        submitCart(destination) {
            if (this.selectedColor) quantitiesByColor[this.selectedColor] = this.sizeQtyList;

            let itemsToSend = [];
            this.colors.forEach(color => {
                const variant = this.variations.find(v => v.color === color);
                const price = pricesByColor[color] ?? {{ (float)$product->price }};
                const image = variant?.image ?? variant?.images?.[0] ?? '{{ $product->image ?? '' }}';
                const quantities = quantitiesByColor[color]
                    .filter(item => item.qty > 0)
                    .map(item => ({
                        product_id: {{ $product->id }},
                        color,
                        size: item.size,
                        qty: item.qty,
                        price,
                        image
                    }));
                itemsToSend = itemsToSend.concat(quantities);
            });

            if (!itemsToSend.length) {
                this.toast.message = '⚠️ Please enter quantity';
                this.toast.show = true;
                setTimeout(() => this.toast.show = false, 2000);
                return;
            }

            let oldInput = document.querySelector('input[name="items"]');
            if (oldInput) oldInput.remove();

            let itemsInput = document.createElement('input');
            itemsInput.type = 'hidden';
            itemsInput.name = 'items';
            itemsInput.value = JSON.stringify(itemsToSend);
            document.getElementById('cartForm').appendChild(itemsInput);

            let redirectInput = document.querySelector('input[name="redirect_to"]');
            if (!redirectInput) {
                redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect_to';
                document.getElementById('cartForm').appendChild(redirectInput);
            }
            redirectInput.value = destination === 'checkout' ? 'checkout' : 'products';

            document.getElementById('cartForm').action = this.cartUrl;
            document.getElementById('cartForm').submit();
        }
    }
}
</script>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
