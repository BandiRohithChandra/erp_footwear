@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6">{{ __('Add New Transaction') }}</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- DESCRIPTION --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <input type="text" name="description" value="{{ old('description') }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
            </div>

            {{-- TYPE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" id="type"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            {{-- CATEGORY (Dynamic) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" id="category"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                </select>
            </div>

            {{-- DOCUMENT (Dynamic dropdown) --}}
            <div id="document_wrapper" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Select Document</label>
                <select id="reference_id" name="reference_id"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500"></select>
                <input type="hidden" id="reference_type" name="reference_type">
            </div>

            {{-- AMOUNT --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="number" name="amount" step="0.01" min="0"
                       value="{{ old('amount') }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
            </div>

            {{-- TAX RATE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                <select name="tax_rate_select" id="tax_rate_select"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500"
                        onchange="toggleCustomTax(this)" required>
                    <option value="0">Tax Exempt</option>
                    <option value="5">5%</option>
                    <option value="15">15% (VAT)</option>
                    <option value="18">18% (GST)</option>
                    <option value="20">20%</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            {{-- CUSTOM TAX --}}
            <div id="custom_tax_container" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Custom Tax Rate (%)</label>
                <input type="number" name="tax_rate" id="tax_rate" step="0.01"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
            </div>

            {{-- DATE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="transaction_date"
                       value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
            </div>

        </div>

        <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Add Transaction
        </button>
    </form>
</div>

{{-- ======================== --}}
{{-- Dynamic JS Logic --}}
{{-- ======================== --}}
<script>
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category');
    const docWrapper = document.getElementById('document_wrapper');
    const referenceSelect = document.getElementById('reference_id');
    const referenceType = document.getElementById('reference_type');

    const categories = {
        income: [
            { value: "invoice_payment", text: "Invoice Payment" },
            { value: "other_income", text: "Other Income" }
        ],
        expense: [
            { value: "purchase_order", text: "Purchase Order" },
            { value: "worker_payroll", text: "Worker Payroll" },
            { value: "salary_advance", text: "Salary Advance" },
            { value: "expense_claim", text: "Expense Claim" },
            { value: "other_expense", text: "Other Expense" }
        ]
    };

    const referenceMap = {
        invoice_payment: "invoice",
        purchase_order: "supplier_order",
        worker_payroll: "worker_payroll",
        salary_advance: "salary_advance",
        expense_claim: "expense_claim"
    };

    function loadCategories() {
        const type = typeSelect.value;
        categorySelect.innerHTML = "";

        categories[type].forEach(cat => {
            const opt = document.createElement("option");
            opt.value = cat.value;
            opt.textContent = cat.text;
            categorySelect.appendChild(opt);
        });

        loadDocuments();
    }

    async function loadDocuments() {
        const category = categorySelect.value;

        if (category.includes("other")) {
            docWrapper.classList.add('hidden');
            referenceType.value = "";
            referenceSelect.innerHTML = "";
            return;
        }

        referenceType.value = referenceMap[category];
        const url = `{{ route('transactions.fetch-items') }}?category=${category}`;

        referenceSelect.innerHTML = "";

        try {
            let response = await fetch(url);
            let data = await response.json();

            data.items.forEach(item => {
                let opt = document.createElement("option");
                opt.value = item.id;
                opt.textContent = item.label;
                referenceSelect.appendChild(opt);
            });

            docWrapper.classList.remove('hidden');

        } catch (err) {
            console.error(err);
        }
    }

    typeSelect.addEventListener("change", loadCategories);
    categorySelect.addEventListener("change", loadDocuments);

    loadCategories();

    function toggleCustomTax(select) {
        const customTaxContainer = document.getElementById('custom_tax_container');
        customTaxContainer.classList.toggle('hidden', select.value !== 'custom');
    }
</script>
@endsection
