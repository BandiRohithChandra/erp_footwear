@extends('layouts.app')

@section('content')
<style>
/* ===== Container ===== */
.product-container {
    max-width: 1200px;
    margin: 40px auto;
    display: flex;
    gap: 40px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    flex-wrap: wrap;
}

/* ===== Left: Product Images ===== */
.product-images-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.main-image-container {
    background: #fff;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    display: flex;
    justify-content: center;
    align-items: center;
}

.main-image-container img {
    max-width: 100%;
    max-height: 550px;
    object-fit: contain;
    border-radius: 16px;
    transition: transform 0.3s ease;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.main-image-container img:hover {
    transform: scale(1.05);
}

/* Thumbnails */
.thumbnails-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-start;
}
.thumbnail {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.thumbnail.active-thumbnail {
    border-color: #4f46e5;
    box-shadow: 0 0 10px rgba(79,70,229,0.4);
}
.thumbnail:hover { transform: scale(1.1); }

/* ===== Right: Product Details ===== */
.product-details {
    flex: 1;
    background: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.product-title { font-size: 2rem; font-weight: 800; color: #111827; }
.product-category, .product-sku { font-size: 0.95rem; color: #6b7280; }
.product-price { font-size: 1.8rem; font-weight: 700; color: #4f46e5; }
.product-description { font-size: 1rem; color: #374151; line-height: 1.6; }

/* Color Options */
.color-options {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.color-swatch {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #d1d5db;
    transition: all 0.3s ease;
}
.color-swatch.selected {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
}
.color-swatch:hover { transform: scale(1.1); }

/* Size & Quantity */
.size-qty-wrapper { margin-top: 20px; }
.size-qty-table { display: flex; flex-direction: column; gap: 12px; }
.size-qty-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #f9fafb;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}
.size-qty-row:hover { box-shadow: 0 3px 6px rgba(0,0,0,0.08); }
.size-label { width: 70px; text-align: center; font-weight: 500; border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 8px; }
.quantity-input { width: 70px; padding: 6px 8px; border-radius: 6px; border: 1px solid #d1d5db; text-align: center; font-weight: 500; }

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 20px;
    margin-top: 30px;
    flex-wrap: wrap;
}
.btn {
    flex: 1;
    min-width: 140px;
    padding: 12px 0;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}
.btn-add { background: linear-gradient(to right, #10b981, #059669); color: #fff; }
.btn-add:hover { background: linear-gradient(to right, #059669, #047857); }
.btn-buy { background: linear-gradient(to right, #4f46e5, #6366f1); color: #fff; }
.btn-buy:hover { background: linear-gradient(to right, #4338ca, #4f46e5); }

/* Toast */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: #10b981;
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    cursor: pointer;
}

/* Responsive */
@media(max-width: 1200px){
    .product-container { gap: 30px; }
}
@media(max-width: 992px){
    .product-container { flex-direction: column; gap: 30px; }
    .main-image-container img { max-height: 450px; }
}
@media(max-width: 768px){
    .product-details { padding: 20px; }
    .main-image-container img { max-height: 350px; }
    .product-title { font-size: 1.7rem; }
    .product-price { font-size: 1.5rem; }
}
@media(max-width: 480px){
    .main-image-container img { max-height: 250px; }
    .color-swatch { width: 28px; height: 28px; }
    .size-label, .quantity-input { width: 60px; font-size: 0.85rem; }
    .btn { font-size: 0.85rem; padding: 10px 0; min-width: 120px; }
}
</style>

<a href="{{ url()->previous() }}" class="back-btn">← Back</a>

<div class="product-container" x-data="productData()" x-init="init()">
    <!-- Toast Notification -->
    <div x-show="toast.show" x-text="toast.message" 
         x-transition.opacity 
         @click="toast.show=false" 
         class="toast">
    </div>

    <!-- Left: Images -->
    <div class="product-images-wrapper">
        <div class="main-image-container">
            <template x-if="selectedImage">
                <img :src="selectedImage" alt="{{ $product->name }}">
            </template>
            <template x-if="!selectedImage">
                <div class="no-image">No Image</div>
            </template>
        </div>

        <div class="thumbnails-row" x-show="currentThumbnails.length > 0">
            <template x-for="img in currentThumbnails" :key="img">
                <img :src="img" 
                     @click="selectedImage = img" 
                     @mouseover="selectedImage = img"
                     :class="{'active-thumbnail': selectedImage === img}" 
                     class="thumbnail">
            </template>
        </div>
    </div>

    <!-- Right: Product Details -->
    <div class="product-details">
        <h1 class="product-title">{{ $product->name }}</h1>
        <p class="product-category">Category: {{ $product->category ?? 'N/A' }}</p>
        <p class="product-sku">Article No: {{ $product->sku }}</p>
        <p class="product-price" x-text="'₹' + selectedPrice.toFixed(2)"></p>
        <p class="product-description">{{ $product->description }}</p>

        <!-- Color Selection -->
        <div class="color-wrapper" x-show="colors.length > 0">
            <p class="section-title">Select Color
                <span x-text="selectedColor ? `: ${selectedColor}` : ''" class="font-semibold ml-2"></span>
            </p>
            <div class="color-options">
                <template x-for="color in colors" :key="color">
                    <div class="color-swatch"
                         :class="{ 'selected': selectedColor === color }"
                         :style="{ backgroundColor: color }"
                         @click="selectColor(color)"></div>
                </template>
            </div>
        </div>

        <!-- Sizes & Quantities -->
        <div class="size-qty-wrapper">
            <p class="section-title">Select Sizes & Quantities</p>
            <div class="size-qty-table">
                <template x-for="item in sizeQtyList" :key="item.size">
                    <div class="size-qty-row">
                        <span class="size-label" x-text="item.size"></span>
                        <input type="number" min="0" class="quantity-input" x-model.number="item.qty" placeholder="Enter qty">
                    </div>
                </template>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn btn-add" @click="submitCart('products')">Add to Cart</button>
            <button type="button" class="btn btn-buy" @click="submitCart('checkout')">Buy Now</button>
        </div>
    </div>
</div>

<script>
function productData() {
    const variations = @json($product->variations ?? []);
    const colors = [...new Set(variations.map(v => v.color))];
    let selectedColor = colors[0] ?? null;
    const standardSizes = [6,7,8,9,10,11,12];

    let quantitiesByColor = {};
    let pricesByColor = {};
    colors.forEach(color => {
        const variant = variations.find(v => v.color === color);
        const sizes = variant?.sizes ?? standardSizes;
        quantitiesByColor[color] = sizes.map(size => ({ size: size, qty: 0 }));
        pricesByColor[color] = Number(variant?.price ?? {{ (float)$product->price }});
    });

    // Fallback main product image
    const mainImage = '/storage/{{ $product->image ?? '' }}';

    return {
        variations,
        colors,
        selectedColor,
        sizeQtyList: quantitiesByColor[selectedColor] ?? [],
        selectedImage: mainImage,
        selectedPrice: pricesByColor[selectedColor] ?? {{ (float)$product->price }},
        toast: { show: false, message: '' },

        init() {
            const variant = this.variations.find(v => v.color === this.selectedColor);
            if (variant) {
                if (variant.image) this.selectedImage = '/storage/' + variant.image;
                else if (variant.images && variant.images.length > 0) this.selectedImage = '/storage/' + variant.images[0];
            }
        },

        selectColor(color) {
            if (this.selectedColor) quantitiesByColor[this.selectedColor] = this.sizeQtyList;
            this.selectedColor = color;
            this.sizeQtyList = quantitiesByColor[color] || [];
            const variant = this.variations.find(v => v.color === color);
            if (variant) {
                if (variant.image) this.selectedImage = '/storage/' + variant.image;
                else if (variant.images && variant.images.length > 0) this.selectedImage = '/storage/' + variant.images[0];
                else this.selectedImage = mainImage;
                this.selectedPrice = Number(variant.price ?? {{ (float)$product->price }});
            } else {
                this.selectedImage = mainImage;
                this.selectedPrice = {{ (float)$product->price }};
            }
        },

        get currentThumbnails() {
            const variant = this.variations.find(v => v.color === this.selectedColor);
            if (!variant) return [this.selectedImage];
            if (variant.images && variant.images.length > 0) return variant.images.map(img => '/storage/' + img);
            return variant.image ? ['/storage/' + variant.image] : [this.selectedImage];
        },

        submitCart(destination) {
            let allQuantities = {};
            let hasQuantities = false;
            this.colors.forEach(color => {
                const quantities = {};
                quantitiesByColor[color].forEach(item => {
                    if (item.qty > 0) { quantities[item.size] = item.qty; hasQuantities = true; }
                });
                if (Object.keys(quantities).length > 0) allQuantities[color] = { quantities: quantities, price: pricesByColor[color] };
            });
            if (!hasQuantities) { alert('Please enter at least one size with quantity.'); return; }

            this.toast.message = destination==='checkout' ? '✅ Redirecting to checkout...' : '✅ Added to cart';
            this.toast.show = true;

            setTimeout(() => {
                const form = document.createElement('form'); 
                form.method = 'POST'; 
                form.action = '{{ route('sales.cart.add', $product->id) }}';
                const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='{{ csrf_token() }}'; form.appendChild(csrf);
                const pid = document.createElement('input'); pid.type='hidden'; pid.name='product_id'; pid.value='{{ $product->id }}'; form.appendChild(pid);

                for (const [color, data] of Object.entries(allQuantities)) {
                    const colorInput = document.createElement('input'); colorInput.type='hidden'; colorInput.name=`items[${color}][color]`; colorInput.value=color; form.appendChild(colorInput);
                    const priceInput = document.createElement('input'); priceInput.type='hidden'; priceInput.name=`items[${color}][price]`; priceInput.value=data.price; form.appendChild(priceInput);
                    for (const [size, qty] of Object.entries(data.quantities)) {
                        const input = document.createElement('input'); input.type='hidden'; input.name=`items[${color}][quantities][${size}]`; input.value=qty; form.appendChild(input);
                    }
                }
                const redirectInput = document.createElement('input'); redirectInput.type='hidden'; redirectInput.name='redirect_to'; redirectInput.value=destination==='checkout' ? 'orders' : 'products'; form.appendChild(redirectInput);
                document.body.appendChild(form); form.submit();
            }, 1200);
        }
    }
}
</script>


<script src="//unpkg.com/alpinejs" defer></script>
@endsection
