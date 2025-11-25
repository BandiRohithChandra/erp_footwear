@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 rounded-lg shadow" id="invoice-content">
         <a href="{{ url()->previous() }}" class="back-btn">
                    <span class="back-icon">←</span> Back
                </a>
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Invoice Details') }}</h1>
           
            <div class="flex items-center gap-2">
                
                <a href="{{ route('sales.invoices.download-pdf', $invoice) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">{{ __('Download PDF') }}</a>
                <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{ __('Print') }}</button>
            </div>
        </div>

        <style>
            .back-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%);
                color: #fff;
                font-weight: 600;
                font-size: 14px;
                border-radius: 30px;
                text-decoration: none;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                transition: all 0.3s ease;
            }
            .back-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.3);
                background: linear-gradient(90deg, #92D3F5 0%, #9747FF 100%);
            }
            .back-icon { font-size: 16px; }

            input.po-input {
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 2px 6px;
                font-size: 14px;
                width: 150px;
                text-align: left;
            }
        </style>

       <!-- Client & Company Details -->
<div class="flex justify-between mb-6">
    <!-- Client Details -->
    <div class="w-1/2">
        <h4 class="text-lg font-semibold mb-2">{{ __('Party / Client Details') }}</h4>
        <p><strong>{{ $invoice->order->user->business_name ?? $invoice->order->user->name ?? 'N/A' }}</strong></p>
        <p>GST: {{ $invoice->order->user->gst_no ?? 'N/A' }}</p>
        <p>Address: {{ $invoice->order->user->address ?? 'N/A' }}</p>
        <p>Phone: {{ $invoice->order->user->phone ?? 'N/A' }}</p>
        <p>Contact Person: {{ $invoice->order->user->contact_person ?? 'N/A' }}</p>
        <p><strong>PO No:</strong> <input type="text" class="po-input" placeholder="Enter PO Number" value="{{ $invoice->po_no ?? '' }}"></p>
        <p><strong>Order No:</strong> {{ $invoice->order->id }}</p>
        <p><strong>Order Date:</strong> {{ $invoice->order->created_at->format('d M Y') }}</p>
        <p><strong>Article No:</strong> {{ $invoice->order->article_no ?? '-' }}</p>

        <h4 class="mt-4 text-lg font-semibold mb-2">{{ __('Shipping / Transport Details') }}</h4>
        <p><strong>{{ $invoice->order->transport_name ?? '-' }}</strong></p>
        <p>Address: {{ $invoice->order->transport_address ?? '-' }}</p>
        <p>ID: {{ $invoice->order->transport_id ?? '-' }}</p>
        <p>Phone: {{ $invoice->order->transport_phone ?? '-' }}</p>
    </div>

    <!-- Company Details -->
    <div class="w-1/2 text-right">
        <h4 class="text-lg font-semibold mb-2">{{ __('Company Details') }}</h4>
        <p><strong>CREATIVE SHOES</strong></p>
        <p>GSTIN: 27AMRPK6699L1ZV</p>
        <p>Ground Floor, Room No.5, Municipal Chawl No.6,<br>Transit Camp Road, Byculla</p>
        <p>Phone: +91 XXXXXXXX9</p>
        <p>Email: kiran@gmail.com</p>
        <p>Contact Person: SIRATULLAH JAMIRULLAH KHAN</p>
    </div>
</div>


        <!-- Invoice Summary -->
        <div class="mb-6 border-t border-b py-4 flex justify-between">
            <div>
                <p><strong>{{ __('Invoice #:') }}</strong> {{ $invoice->id }}</p>
                <p><strong>{{ __('Date:') }}</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                @if ($invoice->due_date)
                <p><strong>{{ __('Due Date:') }}</strong> {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
            </div>
            <div class="text-right">
                <p><strong>{{ __('Status:') }}</strong> {{ ucfirst($invoice->status) }}</p>
                <p><strong>{{ __('Total Amount:') }}</strong> ₹{{ number_format($invoice->amount, 2) }}</p>
                <p><strong>{{ __('Amount Paid:') }}</strong> ₹{{ number_format($invoice->amount_paid, 2) }}</p>
                <p><strong>{{ __('Remaining Balance:') }}</strong> ₹{{ number_format($invoice->remaining_balance, 2) }}</p>
                <p><strong>{{ __('Amount in Words:') }}</strong> {{ ucwords(\NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format($invoice->amount)) }} only</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-6">
            <table class="min-w-full bg-gray-50 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">{{ __('Product') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Quantity') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Unit Price') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @foreach (json_decode($invoice->items, true) as $item)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 px-6">{{ $item['name'] }}</td>
                        <td class="py-3 px-6 text-right">{{ $item['quantity'] }}</td>
                        <td class="py-3 px-6 text-right">₹{{ number_format($item['price'], 2) }}</td>
                        <td class="py-3 px-6 text-right">₹{{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold">
                        <td colspan="3" class="py-3 px-6 text-right">{{ __('Total Amount') }}</td>
                        <td class="py-3 px-6 text-right">₹{{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Bank Details -->
        <div class="mb-6 flex justify-between bg-gray-100 p-4 rounded">
            <div>
                <h4 class="font-semibold">{{ __('Bank Details') }}</h4>
                <p>{{ __('Bank Name:') }} ABC Bank</p>
                <p>{{ __('Account Name:') }} Company Pvt Ltd</p>
                <p>{{ __('Account Number:') }} 1234567890</p>
                <p>{{ __('IFSC:') }} ABCD0123456</p>
            </div>
        </div>

        <!-- Payment History -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">{{ __('Payment History') }}</h3>
            @if ($invoice->payments->isEmpty())
                <p class="text-gray-600">{{ __('No payments recorded yet.') }}</p>
            @else
                <table class="min-w-full bg-gray-50 rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">{{ __('Date') }}</th>
                            <th class="py-3 px-6 text-right">{{ __('Amount') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Method') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($invoice->payments as $payment)
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-6">{{ $payment->payment_date->format('d M Y') }}</td>
                                <td class="py-3 px-6 text-right">₹{{ number_format($payment->amount, 2) }}</td>
                                <td class="py-3 px-6">{{ $payment->payment_method ?? 'N/A' }}</td>
                                <td class="py-3 px-6">{{ $payment->notes ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #invoice-content, #invoice-content * { visibility: visible; }
    #invoice-content { position: absolute; left: 0; top: 0; width: 100%; }
    button, a, form { display: none !important; }
    input.po-input { border: none; }
}
</style>
@endsection
