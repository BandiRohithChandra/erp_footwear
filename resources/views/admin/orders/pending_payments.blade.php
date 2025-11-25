@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="container mx-auto px-4 py-6" x-data="ordersComponent()">

    <!-- Back Buttons -->
    <div class="flex justify-between items-center mb-4">
        
        <a href="{{ url('/admin/online') }}" class="dashboard-button inline-flex items-center gap-2">
            <!-- Left Arrow SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
            Back To Dashboard
        </a>
    </div>

    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">Pending Payments Dashboard</h1>
        <div class="flex flex-wrap gap-2 items-center">
            <select x-model="selectedClient" class="border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200">
                <option value="">All Clients</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }} ({{ ucfirst($client->category) }})</option>
                @endforeach
            </select>

            <select x-model="paymentFilter" class="border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200">
                <option value="pending">Pending Payments</option>
                <option value="all">All Payments</option>
            </select>

            <input type="text" x-model="searchTerm" placeholder="Search by Order ID or Client" class="border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200">

            <button @click="exportFiltered()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">Export</button>
            <button @click="printTable()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">Print</button>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block shadow rounded-lg border border-gray-200 w-full overflow-x-auto">
        <table class="min-w-full table-auto bg-white divide-y divide-gray-200">
            <thead class="table-header">
                <tr>
                    <th class="px-4 py-3 text-left">Order ID</th>
                    <th class="px-4 py-3 text-left">Client</th>
                    <th class="px-4 py-3 text-left">Products</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Paid</th>
                    <th class="px-4 py-3 text-right">Due</th>
                    <th class="px-4 py-3 text-center">Payment Method</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="order in filteredOrders()" :key="order.id">
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-2 font-medium" x-text="order.id"></td>
                        <td class="px-4 py-2 font-medium text-gray-700" x-text="order.client_name"></td>
                        <td class="px-4 py-2">
                            <template x-for="(item, index) in order.items" :key="index">
                                <span class="inline-block bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs m-1 shadow-sm" 
                                      x-text="`${item.name} x${item.quantity}`"></span>
                            </template>
                        </td>
                        <td class="px-4 py-2 text-right font-semibold" x-text="`₹${order.total.toFixed(2)}`"></td>
                        <td class="px-4 py-2 text-right">
                            <input type="number" x-model="order.paid" 
                                   class="border rounded px-2 py-1 w-24 text-right focus:ring focus:ring-green-200">
                        </td>
                        <td class="px-4 py-2 text-right font-semibold" x-text="`₹${(order.total - order.paid).toFixed(2)}`"></td>
                        <td class="px-4 py-2 text-center" x-text="order.payment_method"></td>
                        <td class="px-4 py-2 text-center">
                            <select x-model="order.status" class="border rounded px-2 py-1 focus:ring focus:ring-green-200">
                                <option value="pending">Pending</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button @click="updatePayment(order)" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded shadow">Update</button>
                            <button @click="viewInvoice(order.id)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow mt-1">View</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden flex flex-col gap-4">
        <template x-for="order in filteredOrders()" :key="order.id">
            <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="font-bold text-gray-800 text-lg">Order #<span x-text="order.id"></span></h2>
                    <span class="text-sm px-2 py-1 rounded-full" 
                          :class="{
                            'bg-yellow-100 text-yellow-800': order.status === 'pending',
                            'bg-orange-100 text-orange-800': order.status === 'partial',
                            'bg-green-100 text-green-800': order.status === 'paid'
                          }" 
                          x-text="order.status.toUpperCase()"></span>
                </div>
                <p class="text-gray-600"><strong>Client:</strong> <span x-text="order.client_name"></span></p>
                <p class="text-gray-600"><strong>Products:</strong></p>
                <div class="flex flex-wrap gap-1 mb-2">
                    <template x-for="(item,index) in order.items" :key="index">
                        <span class="bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs shadow-sm" x-text="`${item.name} x${item.quantity}`"></span>
                    </template>
                </div>
                <div class="flex justify-between items-center mt-2 flex-wrap gap-2">
                    <div>
                        <p class="text-gray-600"><strong>Total:</strong> ₹<span x-text="order.total.toFixed(2)"></span></p>
                       <input type="number" x-model.number="order.paid" 
       class="border rounded px-2 py-1 w-24 text-right focus:ring focus:ring-green-200">
                        <p class="text-gray-600 font-semibold">
    <strong>Due:</strong> ₹<span x-text="(order.total - order.paid).toFixed(2)"></span>
</p>

                        <p class="text-gray-600"><strong>Payment Method:</strong> <span x-text="order.payment_method"></span></p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <select x-model="order.status" class="border rounded px-2 py-1 focus:ring focus:ring-green-200">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                        <button @click="updatePayment(order)" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded shadow">Update</button>
                        <button @click="viewInvoice(order.id)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow">View</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-show="filteredOrders().length === 0" class="flex flex-col items-center py-20 text-gray-500">
        <p class="text-lg">No orders found.</p>
    </div>

</div>

<script>



function ordersComponent() {
    return {
        orders: @json($orders),
        selectedClient: '',
        paymentFilter: 'pending',
        searchTerm: '',

        filteredOrders() {
            return this.orders.filter(o => {
                const statusMatch = this.paymentFilter === 'all' 
                    ? true 
                    : (o.status === 'pending' || o.due > 0);
                const clientMatch = this.selectedClient === '' || o.client_id == this.selectedClient;
                const searchMatch = this.searchTerm === '' 
                    || o.client_name.toLowerCase().includes(this.searchTerm.toLowerCase()) 
                    || o.id.toString().includes(this.searchTerm);
                return statusMatch && clientMatch && searchMatch;
            });
        },

        viewInvoice(orderId) { 
            window.open(`{{ url('admin/invoice') }}/${orderId}`, '_blank'); 
        },

        exportFiltered() { /* your export code */ },

        printTable() { window.print(); },

        updatePayment(order) {
    // Validate input
    if(order.paid < 0 || order.paid > order.total){
        alert('Paid amount must be between 0 and total.');
        return;
    }
fetch(`/admin/orders/${order.id}/update-payment`, {
    method: 'POST',
    headers: { 
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ paid: order.paid }) // <--- THIS IS REQUIRED
})

    .then(async res => {
        // If server returns 500 or other errors
        if(!res.ok){
            const text = await res.text();
            console.error('Server error response:', text);
            throw new Error('Server returned an error.');
        }
        return res.json();
    })
    .then(data => {
    if(data.success){
        alert('Payment & status updated successfully!');
        order.due = order.total - order.paid;
        order.status = data.status; // backend-calculated status
        order.paid = data.paid;
    } else {
        alert(data.message || 'Failed to update payment.');
    }
})
    .catch(err => {
        console.error('Fetch error:', err);
        alert('Something went wrong while updating payment. Check console for details.');
    });
}

    }
}

</script>

<style>
.table-header {
    background-color: #1f2937; /* dark gray */
    color: white;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 0.05em;
}

.table-header th {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 2px solid #e5e7eb;
}

.back-button { 
    display:inline-flex; 
    align-items:center; 
    gap:6px; 
    padding:6px 14px; 
    background:#f2f2f7; 
    border-radius:12px; 
    text-decoration:none; 
    font-weight:600; 
    transition: all .3s; 
}
.back-button:hover { background:#0071e3; color:#fff; }

.dashboard-button { 
    display:inline-flex; 
    align-items:center; 
    gap:6px; 
    padding:6px 14px; 
    background:#4f46e5; 
    color:white;
    border-radius:12px; 
    text-decoration:none; 
    font-weight:600; 
    transition: all .3s; 
}
.dashboard-button:hover { background:#3730a3; }
</style>

@endsection
