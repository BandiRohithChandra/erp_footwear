@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen pt-20">
    <div class="max-w-6xl mx-auto px-4">

        <!-- Back Button -->
        <button type="button" onclick="history.back()" 
            class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-purple-600 to-blue-400 text-white font-semibold rounded-full shadow hover:from-blue-400 hover:to-purple-600 transition mb-6">
            <span class="text-lg">←</span> Back
        </button>

        <h1 class="text-3xl font-extrabold text-center text-gray-900 mb-10">💳 Checkout</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow p-6 flex-1">
                <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                <table class="w-full border-collapse mb-4">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-600 uppercase text-sm font-semibold">
                            <th class="p-2 text-left">Product</th>
                            <th class="p-2 text-left">Qty</th>
                            <th class="p-2 text-left">Price</th>
                            <th class="p-2 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $cartItem)
                            @php
                                $product = $cartItem->product;
                                $price = $cartItem->price ?? $product->price;
                                $subtotalItem = $price * $cartItem->quantity;
                            @endphp
                            <tr class="border-b">
                                <td class="p-2">{{ $product->name }} ({{ $cartItem->size ?? '-' }})</td>
                                <td class="p-2">{{ $cartItem->quantity }}</td>
                                <td class="p-2">₹{{ number_format($price,2) }}</td>
                                <td class="p-2">₹{{ number_format($subtotalItem,2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @php
                    $subtotal = $cart->sum(fn($item) => ($item->price ?? $item->product->price) * $item->quantity);
                    $gstAmount = round($subtotal * 0.18,2);
                    $total = round($subtotal + $gstAmount,2);
                @endphp
                <div class="space-y-1 font-semibold">
                    <p>Subtotal: ₹{{ number_format($subtotal,2) }}</p>
                    <p>GST (18%): ₹{{ number_format($gstAmount,2) }}</p>
                    <p class="text-lg font-bold">Total: ₹{{ number_format($total,2) }}</p>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="bg-white rounded-2xl shadow p-6 flex-1">
                <h2 class="text-2xl font-bold mb-4">Shipping & Payment</h2>
                <form id="checkout_form" class="space-y-4">
                    @csrf

                    <!-- Company Transport -->
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" id="use_company_transport" class="accent-indigo-500">
                            <span class="text-gray-700 font-medium">Use Company Transport Details</span>
                        </label>
                    </div>

                    <!-- Client Transport Fields -->
                    <div id="client_transport_fields" class="space-y-4">
                        <div>
                            <label>Transport Name</label>
                            <input type="text" name="transport_name" placeholder="Transport Name"
                                class="w-full p-3 border rounded-lg">
                        </div>
                        <div>
                            <label>Transport Address</label>
                            <textarea name="transport_address" placeholder="Transport Address"
                                class="w-full p-3 border rounded-lg"></textarea>
                        </div>
                        <div>
                            <label>Transport Phone</label>
                            <input type="text" name="transport_phone" placeholder="Transport Phone"
                                class="w-full p-3 border rounded-lg">
                        </div>
                        <div>
                            <label>Transport ID</label>
                            <input type="text" name="transport_id" placeholder="Transport ID"
                                class="w-full p-3 border rounded-lg">
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label>Payment Method</label>
                        <select name="payment_method" id="payment_method" required
                            class="w-full p-3 border rounded-lg">
                            <option value="">Select Payment</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="upi">UPI</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>

                    <!-- Card Fields -->
                    <div id="card_fields" class="hidden space-y-2">
                        <input type="text" name="card_number" placeholder="Card Number"
                            class="w-full p-3 border rounded-lg">
                        <div class="flex gap-2">
                            <input type="text" name="expiry" placeholder="MM/YY"
                                class="flex-1 p-3 border rounded-lg">
                            <input type="text" name="cvv" placeholder="CVV"
                                class="flex-1 p-3 border rounded-lg">
                        </div>
                    </div>

                    <!-- UPI Fields -->
                    <div id="upi_fields" class="hidden space-y-2">
                        <input type="text" name="upi_id" placeholder="Enter UPI ID"
                            class="w-full p-3 border rounded-lg">
                    </div>

                    <!-- Pay Amount -->
                    <div>
                        <label>Amount to Pay</label>
                        <input type="number" name="pay_amount" id="pay_amount" step="0.01" min="0" max="{{ $total }}"
                            class="w-full p-3 border rounded-lg mb-2" value="{{ $total }}">
                        <input type="range" id="pay_slider" min="0" max="{{ $total }}" step="0.01" value="{{ $total }}" class="w-full">
                    </div>

                    <button type="submit" id="pay_now_btn"
                        class="w-full p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Pay Now ₹{{ number_format($total,2) }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Order Popup -->
<div id="order_popup" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full text-center relative">
        <span onclick="closePopup()" class="absolute top-3 right-4 text-gray-500 text-2xl cursor-pointer">&times;</span>
        <h2 id="order_status" class="text-xl font-bold text-green-500 mb-3">✅ Order Placed Successfully!</h2>
        <p id="order_message" class="mb-5 text-gray-700"></p>
        <div>
            <a href="{{ route('clients.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">View My Orders</a>
        </div>
    </div>
</div>

<script>
const useCompanyTransportCheckbox = document.getElementById('use_company_transport');
const clientTransportFields = document.getElementById('client_transport_fields');
const paymentSelect = document.getElementById('payment_method');
const cardFields = document.getElementById('card_fields');
const upiFields = document.getElementById('upi_fields');
const payAmountInput = document.getElementById('pay_amount');
const paySlider = document.getElementById('pay_slider');
const payNowBtn = document.getElementById('pay_now_btn');
const checkoutForm = document.getElementById('checkout_form');
const popup = document.getElementById('order_popup');
const orderMsg = document.getElementById('order_message');
const orderStatus = document.getElementById('order_status');
const totalAmount = {{ $total }};

// Slider & Input Sync
payAmountInput.addEventListener('input', () => {
    let val = parseFloat(payAmountInput.value || 0);
    val = Math.min(Math.max(val, 0), totalAmount);
    payAmountInput.value = val.toFixed(2);
    paySlider.value = val.toFixed(2);
    payNowBtn.innerText = `Pay Now ₹${val.toFixed(2)}`;
});
paySlider.addEventListener('input', () => {
    let val = parseFloat(paySlider.value);
    payAmountInput.value = val.toFixed(2);
    payNowBtn.innerText = `Pay Now ₹${val.toFixed(2)}`;
});

// Show/hide card & UPI fields
paymentSelect.addEventListener('change', () => {
    cardFields.classList.add('hidden');
    upiFields.classList.add('hidden');
    if(paymentSelect.value === 'card') cardFields.classList.remove('hidden');
    if(paymentSelect.value === 'upi') upiFields.classList.remove('hidden');
});

// Disable/enable transport fields
useCompanyTransportCheckbox.addEventListener('change', () => {
    const inputs = clientTransportFields.querySelectorAll('input, textarea');
    if(useCompanyTransportCheckbox.checked){
        inputs.forEach(i => { i.value = ''; i.disabled = true; i.classList.add('bg-gray-100','cursor-not-allowed'); });
    } else {
        inputs.forEach(i => { i.disabled = false; i.classList.remove('bg-gray-100','cursor-not-allowed'); });
    }
});

// AJAX form submission
// AJAX form submission
checkoutForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    payNowBtn.disabled = true;
    payNowBtn.innerText = 'Processing...';

    const formData = {
        transport_name: useCompanyTransportCheckbox.checked ? 'Company Transport Name' : checkoutForm.transport_name.value,
        transport_address: useCompanyTransportCheckbox.checked ? 'Company Address' : checkoutForm.transport_address.value,
        transport_phone: useCompanyTransportCheckbox.checked ? '0000000000' : checkoutForm.transport_phone.value,
        transport_id: useCompanyTransportCheckbox.checked ? 'COMP123' : checkoutForm.transport_id.value,
        payment_method: checkoutForm.payment_method.value,
        card_number: checkoutForm.card_number?.value || null,
        expiry: checkoutForm.expiry?.value || null,
        cvv: checkoutForm.cvv?.value || null,
        upi_id: checkoutForm.upi_id?.value || null,
        pay_amount: parseFloat(checkoutForm.pay_amount.value),
        use_company_transport: useCompanyTransportCheckbox.checked,
        _token: checkoutForm._token.value
    };

    // Validate payment method fields
    if(formData.payment_method === 'card' && (!formData.card_number || !formData.expiry || !formData.cvv)){
        alert('Please fill in all card details.');
        payNowBtn.disabled = false;
        return;
    }
    if(formData.payment_method === 'upi' && !formData.upi_id){
        alert('Please enter UPI ID.');
        payNowBtn.disabled = false;
        return;
    }

    try {
        const res = await fetch("{{ route('client.checkout.pay') }}", {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });

        const data = await res.json();

        payNowBtn.disabled = false;
        payNowBtn.innerText = `Pay Now ₹${parseFloat(payAmountInput.value).toFixed(2)}`;

        if(res.ok) {
            if(data.success){
                orderStatus.innerText = `✅ Order ${data.status}`;
                orderMsg.innerHTML = `Your order <strong>#${data.order_id}</strong> placed!<br>
                                      Paid: ₹${data.paid_amount} / Total: ₹${data.total}<br>
                                      Balance: ₹${data.balance}`;
                popup.classList.remove('hidden');
            } else {
                alert(data.message || 'Something went wrong!');
            }
        } else if(res.status === 422) {
            // Laravel validation errors
            const errors = data.errors;
            let errorMsg = '';
            for(const field in errors){
                errorMsg += `${errors[field].join(', ')}\n`;
            }
            alert(errorMsg);
        } else {
            alert(data.message || 'Error placing order!');
        }
    } catch(err) {
        payNowBtn.disabled = false;
        payNowBtn.innerText = `Pay Now ₹${parseFloat(payAmountInput.value).toFixed(2)}`;
        console.error(err);
        alert('Error placing order! Check console for details.');
    }
});

function closePopup(){ popup.classList.add('hidden'); window.location.href='/products'; }
</script>
@endsection
