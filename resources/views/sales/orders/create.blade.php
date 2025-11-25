@extends('layouts.app')

@section('content')
<div class="container">
    <div class="back-btn-wrapper">
        <a href="{{ url()->previous() }}" class="btn btn-add">
            ← Back
        </a>
    </div>

    <div class="order-form">
        <h1 class="title">Create Sales Order</h1>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="salesOrderForm" action="{{ route('sales.orders.store') }}" method="POST">
            @csrf
            <div class="section">
                <h2>Products</h2>
                <div class="table-wrapper">
                    <table id="productsTable" class="styled-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @forelse($orderItems as $item)
                                @php
                                    $lineTotal = ($item['quantity'] ?? 1) * ($item['price'] ?? 0);
                                    $subtotal += $lineTotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            @if(!empty($item['image']) && file_exists(public_path('storage/' . $item['image'])))
                                                <img src="{{ asset('storage/' . $item['image']) }}" class="product-image" alt="{{ $item['name'] }}">
                                            @else
                                                <div class="no-image">No Image</div>
                                            @endif
                                            <div class="product-details">
                                                <strong>{{ $item['name'] }}</strong>
                                                <small>Color: {{ $item['color'] ?? 'N/A' }}, Size: {{ $item['size'] ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="products[{{ $loop->index }}][product_id]" value="{{ $item['product_id'] }}">
                                        <input type="hidden" name="products[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
                                        <input type="hidden" name="products[{{ $loop->index }}][price]" value="{{ $item['price'] }}">
                                        <input type="hidden" name="products[{{ $loop->index }}][color]" value="{{ $item['color'] ?? 'N/A' }}">
                                        <input type="hidden" name="products[{{ $loop->index }}][size]" value="{{ $item['size'] ?? 'N/A' }}">
                                    </td>
                                    <td class="quantity">{{ $item['quantity'] }}</td>
                                    <td>₹{{ number_format($item['price'], 2) }}</td>
                                    <td class="product-total">₹{{ number_format($lineTotal, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No products in cart. Add items to proceed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @php
                $gstRate = 0.18;
                $gst = $subtotal * $gstRate;
                $total = $subtotal + $gst;
            @endphp

            <div class="section price-summary">
                <div class="summary-row"><span>Subtotal</span><span id="subtotal">₹{{ number_format($subtotal, 2) }}</span></div>
                <div class="summary-row"><span>GST (18%)</span><span id="gst">₹{{ number_format($gst, 2) }}</span></div>
                <div class="summary-row total"><span>Total</span><span id="total">₹{{ number_format($total, 2) }}</span></div>
                <input type="hidden" name="subtotal" id="hiddenSubtotal" value="{{ $subtotal }}">
                <input type="hidden" name="gst" id="hiddenGst" value="{{ $gst }}">
                <input type="hidden" name="total" id="hiddenTotal" value="{{ $total }}">
            </div>

            <div class="section form-grid">
                <div>
                    <label>Payment Method</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="upi">UPI</option>
                        <option value="netbanking">Net Banking</option>
                    </select>
                </div>

                <div>
                    <label>Customer</label>
                    <select name="client_id" id="customer_id" required>
                        <option value="">-- Select Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" data-address="{{ $client->address ?? '' }}">
                                {{ $client->name }} ({{ ucfirst($client->category) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="section">
                <label>Delivery Address</label>
                <textarea name="address" id="address" required></textarea>
            </div>

            <div class="section">
                <button type="submit" id="placeOrderBtn" disabled>Place Order</button>
                <!-- <button type="button" id="generateInvoiceBtn" style="display:none;">Generate Invoice</button> -->
            </div>
        </form>
    </div>
</div>

<div id="orderModal" class="modal">
    <div class="modal-content">
        <span id="closeModal" class="close">&times;</span>
        <div class="modal-body">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" d="M14 27l7 7 17-17"/>
            </svg>
            <h2>Order Placed!</h2>
            <p>Your order has been successfully placed. Thank you for shopping with us!</p>
        </div>
    </div>
</div>

<style>
/* ===== Container ===== */
.container { max-width: 1000px; margin: 20px auto; padding: 0 15px; font-family: Arial, sans-serif; }

/* ===== Back Button ===== */
.back-btn-wrapper { margin-bottom: 20px; }
.btn-add { font-weight: bold; background: #4f46e5; color: #fff; padding: 8px 15px; border-radius: 5px; display: inline-block; text-decoration: none; }
.btn-add:hover { background: #4338ca; }

/* ===== Form Styles ===== */
.order-form { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.title { font-size: 26px; font-weight: bold; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
.section { margin-bottom: 20px; }
input, textarea, select { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }

/* ===== Responsive Grid ===== */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
@media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }

/* ===== Price Summary ===== */
.price-summary { background: #f7f7f7; padding: 15px; border-radius: 6px; margin-bottom: 15px; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-weight: 500; }
.summary-row.total { font-size: 18px; font-weight: bold; border-top: 1px solid #ccc; padding-top: 5px; }

/* ===== Buttons ===== */
button { background: #4f46e5; color: #fff; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s; }
button:disabled { opacity: 0.5; cursor: not-allowed; }
button:hover:not(:disabled) { background: #4338ca; }

/* ===== Table Styles ===== */
.table-wrapper { overflow-x: auto; margin-top: 15px; }
.styled-table { width: 100%; border-collapse: collapse; font-size: 14px; border-radius: 10px; overflow: hidden; min-width: 600px; }
.styled-table thead tr { background-color: #4f46e5; color: #fff; font-weight: bold; }
.styled-table th, .styled-table td { padding: 12px 10px; text-align: left; }
.styled-table tbody tr:hover { background-color: #f7f7ff; }

/* ===== Product Info ===== */
.product-info { display: flex; align-items: center; gap: 12px; }
.product-image { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; }
.no-image { width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #888; border-radius: 6px; }
.product-details strong { font-size: 15px; }
.product-details small { color: #555; font-size: 12px; }

/* ===== Modal ===== */
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { position: relative; top: 25%; margin: auto; padding: 30px; background: #fff; width: 90%; max-width: 400px; border-radius: 10px; text-align: center; }
.checkmark { width: 72px; height: 72px; margin: 0 auto 15px; stroke: #4BB543; stroke-width: 4; stroke-miterlimit: 10; }
.close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
.close:hover { color: #000; }

/* ===== Alerts ===== */
.alert-success { background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px; }
.alert-error { background:#fee2e2; color:#b91c1c; padding:15px; border-radius:8px; margin-bottom:20px; }
.text-center { text-align:center; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentMethod = document.getElementById('payment_method');
    const customerSelect = document.getElementById('customer_id');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const generateInvoiceBtn = document.getElementById('generateInvoiceBtn');
    const salesForm = document.getElementById('salesOrderForm');
    const modal = document.getElementById("orderModal");
    const closeModal = document.getElementById("closeModal");
    const subtotalEl = document.getElementById('subtotal');
    const gstEl = document.getElementById('gst');
    const totalEl = document.getElementById('total');
    const GST_RATE = 0.18;
    const lastOrderId = @json($lastOrderId ?? null); // Get lastOrderId from controller

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#productsTable tbody tr').forEach(row => {
            if (row.querySelector('.quantity')) {
                const qty = parseInt(row.querySelector('.quantity').textContent) || 0;
                const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
                const lineTotal = qty * price;
                subtotal += lineTotal;
                row.querySelector('.product-total').textContent = '₹' + lineTotal.toFixed(2);
            }
        });
        const gst = subtotal * GST_RATE;
        const total = subtotal + gst;

        subtotalEl.textContent = '₹' + subtotal.toFixed(2);
        gstEl.textContent = '₹' + gst.toFixed(2);
        totalEl.textContent = '₹' + total.toFixed(2);

        document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
        document.getElementById('hiddenGst').value = gst.toFixed(2);
        document.getElementById('hiddenTotal').value = total.toFixed(2);
    }

    function toggleButtons() {
        const hasProducts = document.querySelectorAll('#productsTable tbody tr .quantity').length > 0;
        const formValid = paymentMethod.value && customerSelect.value && hasProducts;
        placeOrderBtn.disabled = !formValid;
        // Enable generateInvoiceBtn if lastOrderId exists, regardless of form state
        if (lastOrderId) {
            generateInvoiceBtn.style.display = 'inline-block';
            generateInvoiceBtn.disabled = false;
            generateInvoiceBtn.dataset.orderId = lastOrderId;
        } else {
            generateInvoiceBtn.style.display = formValid ? 'inline-block' : 'none';
            generateInvoiceBtn.disabled = !formValid;
        }
    }

    [paymentMethod, customerSelect].forEach(el => el.addEventListener('change', toggleButtons));

    customerSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('address').value = selectedOption.getAttribute('data-address') || '';
        toggleButtons();
    });

    closeModal.addEventListener('click', () => { modal.style.display = 'none'; });

    salesForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('{{ route("sales.orders.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            // Hide Place Order button
            placeOrderBtn.style.display = 'none';

            // Show modal
            modal.style.display = 'block';

            setTimeout(() => {
                modal.style.display = 'none';
                // Open invoice in new tab
                window.open('/sales/orders/' + data.order_id + '/invoice', '_blank');

                // ✅ Clear the form fields
                salesForm.reset();

                // ✅ Clear products table
                const tbody = document.querySelector('#productsTable tbody');
                tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;">No products in cart. Add items to proceed.</td></tr>`;

                // ✅ Reset totals
                subtotalEl.textContent = '₹0.00';
                gstEl.textContent = '₹0.00';
                totalEl.textContent = '₹0.00';
                document.getElementById('hiddenSubtotal').value = 0;
                document.getElementById('hiddenGst').value = 0;
                document.getElementById('hiddenTotal').value = 0;
            }, 1500); 
        } else {
            alert(data.message || 'Something went wrong!');
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Error submitting order!'); 
    });
});


    calculateTotals();
    toggleButtons();

    generateInvoiceBtn.addEventListener('click', function() {
        const orderId = this.dataset.orderId;
        if(!orderId) { alert('Order ID not found!'); return; }
        window.open('/sales/orders/' + orderId + '/invoice', '_blank');
    });
});
</script>
@endsection