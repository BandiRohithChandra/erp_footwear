@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
/* ===== GENERAL STYLES ===== */
body, .container { font-family: 'Roboto', sans-serif; color: #1f2937; background-color: #f9fafb; }
.container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
h1 { font-size: 2.5rem; font-weight: 700; text-align: center; color: #111827; margin-bottom: 50px; letter-spacing: 1px; }

/* ===== BACK BUTTON ===== */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-radius: 30px;
    text-decoration: none;
    margin-bottom: 30px;
    transition: 0.3s ease;
}
.back-btn:hover { background: linear-gradient(90deg, #92D3F5 0%, #9747FF 100%); transform: translateY(-2px); }

/* ===== CART LAYOUT ===== */
.cart-card {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.cart-items { 
    flex: 2;
    background-color: #ffffff; 
    border-radius: 20px; 
    box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
    padding: 30px; 
    transition: transform 0.3s ease; 
}
.cart-items:hover { transform: translateY(-2px); }
.cart-summary {
    flex: 1; 
    background: linear-gradient(180deg, #f0fdf4, #d1fae5); 
    border-radius: 20px; 
    box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
    padding: 35px; 
    height: fit-content; 
    position: sticky; 
    top: 100px;
}

/* ===== PRODUCT CARD ===== */
.product-card {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 20px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.07);
    background: #ffffff;
    transition: 0.3s ease;
}
.product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 25px rgba(0,0,0,0.12); }

/* ===== PRODUCT IMAGE ===== */
.product-card img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }

/* ===== BUTTONS ===== */
.btn-checkout {
    background: linear-gradient(90deg, #16a34a, #059669);
    color: #fff;
    padding: 18px 24px;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    font-size: 1.15rem;
    font-weight: 600;
    width: 100%;
    margin-top: 25px;
    transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 6px 15px rgba(22,163,74,0.3);
}
.btn-checkout:hover { background: linear-gradient(90deg, #059669, #047857); transform: translateY(-3px); box-shadow: 0 8px 20px rgba(5,150,105,0.4); }

/* ===== CART SUMMARY ===== */
.cart-summary h2 { font-size: 1.9rem; margin-bottom: 30px; color: #065f46; font-weight: 700; border-bottom: 2px solid #10b981; padding-bottom: 15px; letter-spacing: 0.5px; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 1.05rem; color: #374151; }
.summary-row.total { font-weight: 700; font-size: 1.2rem; color: #065f46; }

/* ===== RESPONSIVE ===== */
@media(max-width: 992px) {
    .cart-card { flex-direction: column; }
    .cart-summary { position: relative; top: auto; }
}
@media(max-width: 768px) {
    h1 { font-size: 2rem; margin-bottom: 40px; }
    .product-card { gap: 15px; padding: 15px; }
}
@media(max-width: 480px) {
    h1 { font-size: 1.7rem; margin-bottom: 30px; }

    /* Stack product card items vertically and center */
    .product-card {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center;
    }

    /* Center info section */
    .product-card > div:nth-child(2) {
        align-items: center;
    }

    /* Center quantity + update */
    .product-card > div:nth-child(3) {
        align-items: center;
    }

    .cart-summary { padding: 25px; }
}

</style>

<div class="container">
    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="back-btn">← Back</a>

    <h1>🛒 Your Shopping Cart</h1>

    @if(count($cart) > 0)
        @php
            $subtotal = 0;
            $gstRate = 0.18;
        @endphp

        <div class="cart-card">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cart as $cartKey => $cartItem)
                    @php
                        $comboSubtotal = $cartItem['price'] * $cartItem['quantity'];
                        $subtotal += $comboSubtotal;

                        $variations = isset($cartItem['variations']) ? json_decode($cartItem['variations'], true) : [];
                        $imagePath = null;

                        if(!empty($variations)) {
                            foreach($variations as $variation) {
                                if(($variation['color'] ?? null) === ($cartItem['color'] ?? null) &&
                                   ($variation['size'] ?? null) === ($cartItem['size'] ?? null)) {
                                    $imagePath = $variation['images'][0] ?? null;
                                    break;
                                }
                            }
                        }

                        if(empty($imagePath) && !empty($cartItem['image'])) {
                            $imagePath = is_array($cartItem['image']) ? $cartItem['image'][0] : $cartItem['image'];
                        }

                        $imageUrl = $imagePath ? asset('storage/' . ltrim($imagePath, '/')) : null;
                    @endphp

                    <div class="product-card">
                        <!-- Image -->
                        <div style="flex:0 0 110px; height:110px; display:flex; align-items:center; justify-content:center; border-radius:12px; overflow:hidden; background:linear-gradient(135deg,#f0f4f8,#e2e8f0);">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $cartItem['name'] }}">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:13px;">No Image</div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div style="flex:1; display:flex; flex-direction:column; gap:6px;">
                            <div style="font-weight:700; font-size:17px; color:#111827;">{{ $cartItem['name'] }}</div>
                            <div style="color:#6b7280; font-size:14px;">
                                Color: <span style="font-weight:600; color:#374151;">{{ $cartItem['color'] }}</span> | 
                                Size: <span style="font-weight:600; color:#374151;">{{ $cartItem['size'] }}</span>
                            </div>
                            <div style="margin-top:8px; color:#059669; font-weight:700; font-size:15px;">Subtotal: ₹{{ number_format($comboSubtotal,2) }}</div>
                        </div>

                        <!-- Quantity + Update -->
                        <div style="flex:0 0 170px; display:flex; flex-direction:column; gap:10px; align-items:center;">
                            <form action="{{ route('sales.cart.update', $cartKey) }}" method="POST" style="display:flex; gap:5px;">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $cartItem['quantity'] }}" min="1" style="width:65px; text-align:center; border-radius:8px; border:1px solid #cbd5e1; padding:5px; background:#f8fafc;">
                                <button type="submit" style="padding:7px 14px; background:#4f46e5; color:white; border:none; border-radius:10px; cursor:pointer; font-weight:600; transition:0.2s;" 
                                onmouseover="this.style.background='#4338ca'" 
                                onmouseout="this.style.background='#4f46e5'">Update</button>
                            </form>
                            <a href="{{ route('sales.cart.remove', $cartKey) }}" style="color:#ef4444; font-weight:600; text-decoration:none; margin-top:5px; transition:0.2s;" 
                            onmouseover="this.style.color='#b91c1c'" 
                            onmouseout="this.style.color='#ef4444'">Remove</a>
                        </div>

                        <!-- Price -->
                        <div style="flex:0 0 120px; text-align:center; font-weight:700; font-size:17px; color:#111827;">
                            ₹{{ number_format($cartItem['price'],2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            @php
                $gstAmount = $subtotal * $gstRate;
                $total = $subtotal + $gstAmount;
            @endphp
            <div class="cart-summary">
                <h2>Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>GST (18%)</span>
                    <span>₹{{ number_format($gstAmount, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total Bill</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                <form action="{{ route('sales.orders.create') }}" method="GET">
                    <button type="submit" class="btn-checkout">Proceed to Checkout</button>
                </form>
            </div>
        </div>

    @else
        <p style="text-align:center; font-size:1.2rem; color:#6b7280; margin-top:50px;">Your cart is empty.</p>
    @endif
</div>
@endsection
