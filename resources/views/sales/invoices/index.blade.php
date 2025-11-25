@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">{{ __('Invoices') }}</h1>

        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 mb-6 px-4 py-2 rounded-full text-white font-semibold"
            style="background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <span class="text-lg">←</span> Back
        </a>

        <div class="overflow-x-auto">
            @if($invoices->isEmpty())
                <p class="text-gray-600 text-center py-6">No invoices found.</p>
            @else
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-sm font-semibold tracking-wide">
                            <th class="py-3 px-6 text-left">Invoice ID</th>
                            <th class="py-3 px-6 text-left">Invoice Date</th>
                            <th class="py-3 px-6 text-left">Customer Name</th>
                            <th class="py-3 px-6 text-left">Invoice Type</th>
                            <th class="py-3 px-6 text-left">Due Date</th>
                            <th class="py-3 px-6 text-right">Amount (₹)</th>
                            <th class="py-3 px-6 text-right">Paid (₹)</th>
                            <th class="py-3 px-6 text-right">Remaining (₹)</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700 text-sm divide-y divide-gray-200">
                        @foreach ($invoices as $invoice)
                            @php
    // Company GST (owner GST) from settings
    $companyGst = \App\Models\Settings::get('company_gst');
    $companyState = $companyGst ? substr($companyGst, 0, 2) : null;

    // Client GST
    $clientGst = $invoice->client->gst_no ?? null;
    $clientState = $clientGst && strlen($clientGst) >= 2 ? substr($clientGst, 0, 2) : null;

    // Detect GST type
    if ($companyState === $clientState && $companyState !== null) {
        // INTRA-STATE
        $cgst = ($invoice->amount * 2.5) / 100;
        $sgst = ($invoice->amount * 2.5) / 100;
        $igst = 0;
    } else {
        // INTER-STATE
        $cgst = 0;
        $sgst = 0;
        $igst = ($invoice->amount * 5) / 100;
    }

    // Final Total
    $totalWithGST = $invoice->amount + $cgst + $sgst + $igst;

    $paid = $invoice->amount_paid ?? 0;
    $remaining = $totalWithGST - $paid;

    $link = route('sales.quotations.invoice', $invoice->id);
@endphp


                            <tr
                                onclick="window.location='{{ $link }}'"
                                class="hover:bg-indigo-50 transition duration-150 cursor-pointer align-middle">

                                {{-- Invoice ID --}}
                                <td class="py-3 px-6 font-medium text-gray-800 whitespace-nowrap">
                                    #{{ $invoice->id }}
                                </td>

                                {{-- Invoice Date --}}
                                <td class="py-3 px-6 whitespace-nowrap text-gray-700">
                                    {{ $invoice->created_at ? $invoice->created_at->format('d M Y') : 'N/A' }}
                                </td>

                                {{-- Customer Name --}}
                                <td class="py-3 px-6 whitespace-nowrap">
                                    {{ $invoice->client->business_name ?? $invoice->client->name ?? 'N/A' }}
                                </td>

                                {{-- Invoice Type --}}
                                <td class="py-3 px-6 whitespace-nowrap capitalize">
                                    {{ $invoice->type ?? 'N/A' }}
                                </td>

                               {{-- Due Date + Grace Period + Remaining Days --}}
<td class="py-3 px-6 whitespace-nowrap text-gray-700">

    @php
        $paymentType = $invoice->payment_type;
        $due = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->startOfDay() : null;
        $today = \Carbon\Carbon::now()->startOfDay();
        $daysLeft = $due ? $today->diffInDays($due, false) : null;
    @endphp

    {{-- IMMEDIATE PAYMENT --}}
    @if($paymentType === 'immediate')
        <div class="font-semibold text-indigo-700">Immediate</div>
        <div class="text-xs text-gray-500">No due date</div>

    {{-- GRACE PAYMENT --}}
    @elseif($paymentType === 'grace' && $due)
        <div class="font-semibold text-gray-800">
            {{ $due->format('d M Y') }}
        </div>

        {{-- Grace Period --}}
        @if($invoice->grace_period)
            <div class="text-xs text-blue-600">
                Grace: {{ $invoice->grace_period }} days
            </div>
        @endif

        {{-- Remaining Days / Overdue --}}
        <div class="text-xs mt-1 font-medium 
            @if($daysLeft > 0) text-green-600
            @elseif($daysLeft == 0) text-orange-600
            @else text-red-600
            @endif">

            @if($daysLeft > 0)
                {{ $daysLeft }} days left
            @elseif($daysLeft == 0)
                Due today
            @else
                Overdue by {{ abs($daysLeft) }} days
            @endif
        </div>

    @else
        <span class="text-gray-400">Not set</span>
    @endif
</td>


                                {{-- Amount --}}
                                <td class="py-3 px-6 text-right font-semibold text-indigo-700">
                                    ₹{{ number_format($totalWithGST, 2) }}
                                    <div class="text-xs text-gray-500 leading-tight">
    @if($igst > 0)
        IGST (5%): ₹{{ number_format($igst, 2) }}
    @else
        CGST (2.5%): ₹{{ number_format($cgst, 2) }} <br>
        SGST (2.5%): ₹{{ number_format($sgst, 2) }}
    @endif
</div>

                                </td>

                                {{-- Amount Paid --}}
                                <td class="py-3 px-6 text-right text-green-700 font-medium">
                                    ₹{{ number_format($paid, 2) }}
                                </td>

                                {{-- Remaining --}}
                                <td class="py-3 px-6 text-right text-red-600 font-medium">
                                    ₹{{ number_format(max($remaining, 0), 2) }}
                                </td>

                                {{-- Status --}}
                                <td class="py-3 px-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold inline-block
                                    @if($invoice->status === 'paid') bg-green-100 text-green-700
                                    @elseif($invoice->status === 'partially_paid') bg-yellow-100 text-yellow-700
                                    @elseif($invoice->status === 'cancelled') bg-gray-100 text-gray-700
                                    @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="py-3 px-6 text-center whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ $link }}"
                                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition shadow-sm">
                                            View
                                        </a>

                                        @if($invoice->status !== 'paid')
                                            <button
                                                onclick="openPartialPaymentModal({{ $invoice->id }}, {{ $totalWithGST }}, {{ $paid }})"
                                                class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded text-xs font-semibold transition shadow-sm">
                                                💰 Edit
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif
        </div>
    </div>

    {{-- ✅ Partial Payment Modal --}}
    <div id="paymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 transition-all duration-300 ease-in-out">

        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 transform scale-95 transition-all duration-300 animate-fadeIn">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">💰 Edit Partial Payment</h2>
            <p class="text-sm text-gray-500 mb-4">Enter the new total amount paid for this invoice.</p>

            <input type="number" id="partialAmountInput"
                class="border border-gray-300 w-full px-3 py-2 rounded-lg mb-5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter amount (₹)" min="0">

            <div class="flex justify-end gap-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium transition">
                    Cancel
                </button>
                <button onclick="confirmPartialPayment()"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-sm transition">
                    Save
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.25s ease-out;
        }
    </style>

    <script>
        let selectedInvoice = { id: null, totalWithGST: 0, currentPaid: 0, remaining: 0 };

        function openPartialPaymentModal(id, totalWithGST, currentPaid) {
            selectedInvoice = {
                id,
                totalWithGST,
                currentPaid,
                remaining: totalWithGST - currentPaid
            };

            // Empty the input each time the modal opens
            const input = document.getElementById('partialAmountInput');
            input.value = '';
            input.placeholder = `Enter amount (Max ₹${selectedInvoice.remaining.toFixed(2)})`;

            // Show modal
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
        }

        function confirmPartialPayment() {
            const enteredAmount = parseFloat(document.getElementById('partialAmountInput').value);

            if (isNaN(enteredAmount) || enteredAmount <= 0) {
                alert('Please enter a valid payment amount.');
                return;
            }

            if (enteredAmount > selectedInvoice.remaining) {
                alert(`You cannot pay more than the remaining amount (₹${selectedInvoice.remaining.toFixed(2)}).`);
                return;
            }

            const newTotalPaid = selectedInvoice.currentPaid + enteredAmount;

            // Decide new status
            const newStatus = newTotalPaid >= selectedInvoice.totalWithGST
                ? 'paid'
                : 'partially_paid';

            closeModal();

            updateInvoiceStatus(selectedInvoice.id, newStatus, newTotalPaid);
        }

        function updateInvoiceStatus(invoiceId, newStatus, amountPaid) {
            fetch(`/invoices/${invoiceId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus,
                    amount_paid: amountPaid
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Partial payment updated successfully.');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to update invoice.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error updating payment.');
                });
        }
    </script>

@endsection