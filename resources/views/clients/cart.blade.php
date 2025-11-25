@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Back Button -->
    <button type="button" onclick="history.back()" 
        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </button>

    <h1 class="text-3xl md:text-4xl font-bold text-center mb-8">🛒 Your Shopping Cart</h1>

    @if(count($cart) > 0)
        @php
            $subtotal = 0;
            $gstRate = 0.18;
        @endphp

        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Cart Items -->
            <div class="flex-1 bg-white rounded-2xl shadow p-4 lg:p-6 space-y-6">
                @foreach($cart as $cartItem)
                    @php
                        $product = $cartItem->product;
                        $comboSubtotal = $cartItem->price * $cartItem->quantity;
                        $subtotal += $comboSubtotal;

                        // Determine image path
                        $imagePath = null;

                        // 1. Cart item image
                        if(!empty($cartItem->image)) {
                            $imagePath = $cartItem->image;
                        }
                        // 2. Product main image
                        elseif(!empty($product->image)) {
                            $imagePath = $product->image;
                        }
                        // 3. First variation image
                        elseif(!empty($product->variations)) {
                            $variations = is_array($product->variations) ? $product->variations : json_decode($product->variations, true);
                            if(!empty($variations) && !empty($variations[0]['images']) && is_array($variations[0]['images'])) {
                                $imagePath = $variations[0]['images'][0];
                            }
                        }
                    @endphp

                    <!-- Single Cart Item Card -->
                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col justify-between">

                        <!-- Top: Product details -->
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <!-- Product Image -->
                            <div class="md:col-span-1 flex-shrink-0 w-24 h-24 md:w-28 md:h-28">
                                @if(!empty($imagePath))
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover rounded-xl">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-xl text-gray-400">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="md:col-span-5 flex flex-col justify-center space-y-1">
                                <div class="font-medium text-lg">{{ $product->name }}</div>
                                <div class="text-gray-600">Color: {{ $cartItem->color ?? 'N/A' }}</div>
                                <div class="text-gray-600">Size: {{ $cartItem->size ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Bottom: Price + Subtotal + Actions -->
                        <div class="mt-4 border-t pt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            
                            <!-- Price + Subtotal -->
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <span class="font-semibold text-green-700">Price: ₹{{ number_format($cartItem->price, 2) }}</span>
                                <span class="font-bold text-green-800">Subtotal: ₹{{ number_format($comboSubtotal, 2) }}</span>
                            </div>

                            <!-- Quantity + Buttons -->
                            <div class="flex flex-col sm:flex-row justify-end sm:items-center gap-3 w-full">

                                <!-- Update Quantity -->
                                <form action="{{ route('client.cart.update', $cartItem->id) }}" method="POST" 
                                      class="flex items-center gap-2 bg-white border rounded-xl px-3 py-2 shadow-sm w-full sm:w-auto">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="0"
                                           class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-center text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    <button type="submit" 
                                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                                        Update
                                    </button>
                                </form>

                                <!-- Remove Button -->
                                <a href="{{ route('client.cart.remove', $cartItem->id) }}" 
                                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-red-500 hover:text-white transition font-medium shadow-sm text-center">
                                    Remove
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="lg:w-96 bg-green-50 rounded-2xl shadow p-6 flex-shrink-0 sticky top-20">
                @php
                    $gstAmount = $subtotal * $gstRate;
                    $total = $subtotal + $gstAmount;
                @endphp
                <h2 class="text-xl font-bold mb-4 border-b border-green-400 pb-2">Order Summary</h2>

                <div class="flex justify-between mb-2 text-gray-700">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2 text-gray-700">
                    <span>GST (18%)</span>
                    <span>₹{{ number_format($gstAmount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-green-700 text-lg mb-4">
                    <span>Total Bill</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                <form action="{{ route('client.checkout') }}" method="GET">
                    <button type="submit" class="block mx-auto bg-green-600 text-white font-semibold py-2 px-6 rounded-xl hover:bg-green-700 transition">
                        Proceed to Checkout
                    </button>
                </form>
            </div>
        </div>
    @else
        <p class="text-center text-gray-500 text-lg mt-12">Your cart is empty.</p>
    @endif
</div>
@endsection
