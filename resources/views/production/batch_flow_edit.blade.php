@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col items-center py-10">
    <div class="w-full max-w-6xl">

        <!-- Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Batch Flow</h2>
            <div class="flex gap-4">
                <a href="{{ route('batch.flow.index') }}" class="text-indigo-600 hover:underline">← Back to Batches</a>
            </div>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Product Error -->
        @if (!$batch->product)
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                No product associated with this batch. Please assign a valid product and try again.
            </div>
        @else

        <!-- Form Card -->
        <div class="bg-white shadow-lg rounded-xl p-8">
            <form method="POST" action="{{ route('batch.flow.update', $batch->id) }}" class="space-y-6" id="batch-form">
                @csrf
                @method('PUT')

<!-- ==============================================================
     STEP 1: LABOR ASSIGNMENT – OLD LAYOUT + BULLET COLLAPSE (INITIAL CLOSED)
     ============================================================== -->
<div class="space-y-8 mt-6">
    <h3 class="text-2xl font-bold mb-4 text-gray-800">Labor Assignment</h3>

    @php
        $variations = is_string($batch->variations) ? json_decode($batch->variations, true) : ($batch->variations ?? []);
    @endphp

    @if($batch->product->processes && $batch->product->processes->isNotEmpty())

        <!-- Bullet List (Same as before) -->
        <div class="mb-8 bg-gray-50 rounded-2xl p-6 border">
            <h4 class="font-bold text-lg text-gray-700 mb-4">Select Process to Assign Labor:</h4>
            <div class="space-y-3">
                @foreach($batch->product->processes as $process)
                    @php
                        $procId = $process->id;
                        $procStatus = $processStatuses[$procId] ?? 'pending';
                        $isCompleted = ($procStatus === 'completed');
                    @endphp
                    <button type="button"
                            onclick="
                                document.querySelectorAll('.process-section').forEach(s => s.classList.add('hidden'));
                                document.getElementById('section-{{ $procId }}').classList.remove('hidden');
                                document.getElementById('section-{{ $procId }}').scrollIntoView({behavior: 'smooth', block: 'start'});
                            "
                            class="w-full text-left px-5 py-4 rounded-xl border-2 flex items-center gap-4 transition-all
                                   {{ $isCompleted ? 'bg-green-50 border-green-300' : 'bg-white border-indigo-300 hover:bg-indigo-50' }}
                                   hover:shadow-md">
                        <div class="w-3 h-3 rounded-full {{ $isCompleted ? 'bg-green-500' : 'bg-indigo-500' }}"></div>
                        <span class="font-semibold text-gray-800">{{ $process->name }}</span>
                        <span class="ml-auto text-sm font-medium text-gray-600">
                            ₹{{ number_format($process->pivot->labor_rate ?? 0, 0) }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-full
                                     {{ $isCompleted ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($procStatus) }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- All Process Sections – NOW ALL HIDDEN INITIALLY -->
        @foreach($batch->product->processes as $process)
            @php
                $procId = $process->id;
                $procStatus = $processStatuses[$procId] ?? 'pending';
                $isCompleted = ($procStatus === 'completed');
                $availableLabors = $processLabors[$procId] ?? collect();
                $assignedLaborsList = collect();

                if(isset($assignedLabors[$procId])) {
                    foreach ($assignedLabors[$procId] as $empId => $vData) {
                        $emp = \App\Models\Employee::find($empId);
                        if ($emp) $assignedLaborsList->push($emp);
                    }
                }
                $processWorkers = $availableLabors->merge($assignedLaborsList)->unique('id');
            @endphp

            <!-- YE LINE CHANGE KI HAI – AB SAB hidden HAIN -->
            <div id="section-{{ $procId }}" class="process-section hidden rounded-3xl p-6 border transition-all duration-300 shadow-md
                 {{ $isCompleted ? 'bg-green-50 border-green-200 shadow-green-100' : 'bg-indigo-50 border-indigo-200 hover:shadow-indigo-200' }}">

                <!-- BAaki SAB KUCH BILKUL WAHI PURANA LAYOUT -->
                <div class="flex flex-wrap justify-between items-center mb-6 border-b pb-3">
                    <h3 class="text-xl font-bold text-indigo-800 flex items-center gap-2">
                        {{ $process->name }}
                    </h3>
                    <div class="flex gap-3 items-center">
                        <div class="text-gray-700 font-semibold">
                            Rate: <span class="text-gray-900">₹{{ number_format($process->pivot->labor_rate ?? 0, 2) }}</span>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full border
                            {{ $isCompleted ? 'bg-green-100 border-green-300 text-green-700' : 'bg-yellow-100 border-yellow-300 text-yellow-700' }}">
                            Status: {{ ucfirst($procStatus) }}
                        </span>
                    </div>
                </div>

                <!-- SAARA PURANA CODE (Variations, Tables, Inputs) WAHI RAHEGA -->
                @foreach($variations as $vIndex => $variation)
                    @php
                        $variationSizes = array_keys($variation['sizes'] ?? []);
                        $colorName = $variation['color'] ?? 'Variation';
                        $bgColorClass = match(strtolower($colorName)) {
                            'brown' => 'bg-amber-50',
                            'tan' => 'bg-orange-50',
                            'black' => 'bg-gray-50',
                            'white' => 'bg-slate-50',
                            default => 'bg-sky-50'
                        };
                    @endphp

                    <div class="rounded-2xl p-4 mb-6 border shadow-sm {{ $bgColorClass }}">
                        <h4 class="text-lg font-semibold mb-3 text-gray-800 flex items-center gap-2">
                            Variation: <span class="text-indigo-700">{{ ucfirst($colorName) }}</span>
                        </h4>

                        <!-- Size Table -->
                        <div class="overflow-x-auto mb-4">
                            <table class="min-w-full border text-sm text-center bg-white rounded-lg shadow-sm">
                                <thead class="bg-indigo-100 text-gray-800">
                                    <tr>
                                        @foreach($variationSizes as $size)
                                            <th class="border px-3 py-2 font-semibold">Size {{ $size }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach($variationSizes as $size)
                                            @php $available = $variation['sizes'][$size] ?? 0; @endphp
                                            <td class="border px-3 py-2 font-medium text-gray-800 size-summary"
                                                data-process-id="{{ $procId }}"
                                                data-size="{{ $size }}"
                                                data-variation-index="{{ $vIndex }}"
                                                data-available="{{ $available }}">
                                                {{ $available }}
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Labor Table (100% Old Wala) -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border text-sm text-center bg-white rounded-lg shadow-sm">
                                <thead class="bg-indigo-100 text-gray-800">
                                    <tr>
                                        <th class="border px-3 py-2 whitespace-nowrap text-left">Worker</th>
                                        @foreach($variationSizes as $size)
                                            <th class="border px-3 py-2 whitespace-nowrap">Size {{ $size }}</th>
                                        @endforeach
                                        <th class="border px-3 py-2 whitespace-nowrap">Start Date</th>
                                        <th class="border px-3 py-2 whitespace-nowrap">End Date</th>
                                        <th class="border px-3 py-2 whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($processWorkers as $worker)
                                        @php
                                            $empId = $worker->id;
                                            $isAssigned = isset($assignedLabors[$procId][$empId]);
                                            $startDateRaw = $assignedLaborsDates[$procId][$empId][$vIndex]['start_date'] ?? null;
                                            $endDateRaw   = $assignedLaborsDates[$procId][$empId][$vIndex]['end_date'] ?? null;
                                            $startDate = ($startDateRaw && strtotime($startDateRaw)) ? \Carbon\Carbon::parse($startDateRaw)->format('Y-m-d') : '';
                                            $endDate   = ($endDateRaw && strtotime($endDateRaw)) ? \Carbon\Carbon::parse($endDateRaw)->format('Y-m-d') : '';
                                            $workerStatus = $assignedLaborsStatus[$procId][$empId][$vIndex] ?? 'pending';
                                            $isWorkerCompleted = ($workerStatus === 'completed');
                                        @endphp
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition">
                                            <td class="text-left px-3 py-2 whitespace-nowrap">
                                                <label class="flex items-center gap-2">
                                                    <input type="checkbox" class="worker-checkbox accent-indigo-600"
                                                           data-process-id="{{ $procId }}"
                                                           name="labors[{{ $procId }}][]" value="{{ $empId }}"
                                                           {{ $isAssigned ? 'checked' : '' }}
                                                           {{ $isWorkerCompleted ? 'disabled' : '' }}>
                                                    <span class="font-medium text-gray-800">{{ $worker->name }} <small class="text-gray-500">({{ $worker->labor_type }})</small></span>
                                                </label>
                                            </td>
                                            @foreach($variationSizes as $size)
                                                @php
                                                    $available = $variation['sizes'][$size] ?? 0;
                                                    $assigned  = $assignedLabors[$procId][$empId][$vIndex][$size] ?? 0;
                                                @endphp
                                                <td class="border px-3 py-2 whitespace-nowrap">
                                                    <input type="number"
                                                           name="worker_qty[{{ $procId }}][{{ $empId }}][{{ $vIndex }}][{{ $size }}]"
                                                           value="{{ $assigned }}"
                                                           min="0"
                                                           max="{{ $available }}"
                                                           data-available="{{ $available }}"
                                                           data-process-id="{{ $procId }}"
                                                           data-variation-index="{{ $vIndex }}"
                                                           data-size="{{ $size }}"
                                                           class="worker-qty w-16 text-center border rounded-md py-1 text-sm focus:ring-2 focus:ring-indigo-300"
                                                           {{ $isAssigned && !$isWorkerCompleted ? '' : 'disabled bg-gray-100' }}>
                                                    <small class="text-[10px] text-gray-400 hidden over-limit">Max: {{ $available }}</small>
                                                </td>
                                            @endforeach
                                            <td class="border px-3 py-2 whitespace-nowrap">
                                                <input type="date" name="start_date[{{ $procId }}][{{ $empId }}][{{ $vIndex }}]" value="{{ $startDate }}"
                                                       class="w-full border rounded-md py-1 px-2 text-sm focus:ring-2 focus:ring-indigo-300"
                                                       {{ $isWorkerCompleted ? 'disabled bg-gray-100' : '' }}>
                                            </td>
                                            <td class="border px-3 py-2 whitespace-nowrap">
                                                <input type="date" name="end_date[{{ $procId }}][{{ $empId }}][{{ $vIndex }}]" value="{{ $endDate }}"
                                                       class="w-full border rounded-md py-1 px-2 text-sm focus:ring-2 focus:ring-indigo-300"
                                                       {{ $isWorkerCompleted ? 'disabled bg-gray-100' : '' }}>
                                            </td>
                                            <td class="border px-3 py-2 whitespace-nowrap">
                                                <select name="status[{{ $procId }}][{{ $empId }}][{{ $vIndex }}]"
                                                        class="w-full border rounded-md py-1 px-2 text-sm focus:ring-2 focus:ring-indigo-300
                                                               {{ $workerStatus === 'completed' ? 'bg-green-100 text-green-800 border-green-300' : 
                                                                  ($workerStatus === 'in_progress' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : 
                                                                  'bg-gray-100 text-gray-700 border-gray-300') }}">
                                                    <option value="pending" {{ $workerStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="in_progress" {{ $workerStatus === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $workerStatus === 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="{{ count($variationSizes) + 5 }}" class="py-3 text-gray-500 italic">No available labors found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    @else
        <div class="text-red-600 font-semibold text-center py-10">
            No processes associated with this product. Please assign processes first.
        </div>
    @endif
</div>      <!-- ==============================================================
                     STEP 2: BATCH DETAILS + PRODUCT DETAILS (MOVED TO BOTTOM)
                     ============================================================== -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12 border-t pt-8">
                    <!-- Left Column: Batch Details -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-700">Batch Details</h3>

                        <!-- Batch No + Order No -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="batch_no" class="block text-sm font-medium text-gray-700">Batch No</label>
                                <input type="text" name="batch_no" id="batch_no" 
                                       value="{{ $batch->batch_no }}" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed" />
                            </div>
                            <div>
                                <label for="order_no" class="block text-sm font-medium text-gray-700">Order No</label>
                                <input type="text" id="order_no" name="order_no" value="{{ $batch->order_no }}" readonly
                                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed">
                            </div>
                        </div>

                       <!-- Client + PO No -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- PARTY (CLIENT) SECTION -->
    <div>
        <label class="block text-sm font-medium text-gray-700">
            Parties <span class="text-red-500">*</span>
        </label>

        {{-- Show currently selected parties --}}
        @if($batch->clients->isNotEmpty())
            <ul class="mt-2 space-y-1 mb-2">
                @foreach($batch->clients as $c)
                    <li class="flex justify-between items-center bg-gray-100 px-2 py-1 rounded text-sm">
                        <span>{{ $c->name }} ({{ ucfirst($c->category) }})</span>
                    </li>
                @endforeach
            </ul>

            <button type="button" id="open-party-editor"
                    class="text-indigo-600 hover:underline text-sm">
                Change / Add Parties
            </button>
        @endif

        {{-- Checkbox editor (shown when no clients or when user clicks "Change") --}}
        <div id="party-editor"
             class="{{ $batch->clients->isNotEmpty() ? 'hidden' : '' }} mt-3 space-y-3 max-h-60 overflow-y-auto border rounded-lg p-4 bg-gray-50">
             
            @foreach($clients as $c)
                <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 px-2 py-1 rounded">
                    <input type="checkbox"
                           name="client_ids[]"
                           value="{{ $c->id }}"
                           class="party-checkbox rounded text-indigo-600 focus:ring-indigo-500"
                           {{ $batch->clients->contains('id', $c->id) ? 'checked' : '' }}>
                    <span class="text-sm">{{ $c->name }} <span class="text-gray-500">({{ ucfirst($c->category) }})</span></span>
                </label>
            @endforeach

            <div class="mt-4 pt-3 border-t">
                <button type="button" id="add-new-party-btn"
                        class="w-full text-left text-green-600 font-medium hover:underline text-sm">
                    + Add New Party
                </button>
            </div>
        </div>

        {{-- REMOVED THIS LINE → it was sending empty value and breaking validation --}}
        {{-- <input type="hidden" name="client_ids[]" value=""> --}}
    </div>

    <!-- PO No -->
    <div>
        <label for="po_no" class="block text-sm font-medium text-gray-700">
            PO No <span class="text-red-500">*</span>
        </label>
        <input type="text" name="po_no" id="po_no" required
               value="{{ old('po_no', $batch->po_no) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
    </div>
</div>

                        <!-- Batch Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="batch_start_date" class="block text-sm font-medium text-gray-700">Batch Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="batch_start_date" id="batch_start_date" required
                                       value="{{ $batch->start_date->format('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="batch_end_date" class="block text-sm font-medium text-gray-700">Batch End Date</label>
                                <input type="date" name="batch_end_date" id="batch_end_date"
                                       value="{{ $batch->end_date?->format('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>

                        <!-- Article Selection -->
                        <div>
                            <label for="article_no" class="block text-sm font-medium text-gray-700">Article No <span class="text-red-500">*</span></label>
                            <select name="article_no" id="article_no" required disabled
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
                                <option value="">-- Select Article --</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}" {{ $batch->product_id == $article->id ? 'selected' : '' }}>
                                        {{ $article->sku }} - {{ $article->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Right Column: Product Details -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-700">Product Details</h3>
                        <div class="flex flex-col items-center p-4 bg-gray-50 border rounded-lg shadow-sm" id="product-image-card">
                            @php
                                $variations = $batch->product->variations 
                                    ?? (is_string($batch->variations) ? json_decode($batch->variations, true) : $batch->variations ?? []);
                                $firstImage = $variations[0]['images'][0] ?? $batch->product->image ?? null;
                                $imageUrl = $firstImage 
                                    ? (str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . ltrim($firstImage, '/'))) 
                                    : null;
                            @endphp

                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $batch->product->name }}" class="w-48 h-48 object-cover rounded-lg border mb-4 shadow-sm">
                            @else
                                <div class="w-48 h-48 flex items-center justify-center bg-gray-100 text-gray-400 rounded-lg border">
                                    No Image
                                </div>
                            @endif

                            <h3 id="product-name" class="text-lg font-semibold">{{ $batch->product->name ?? 'N/A' }}</h3>
                            <p id="product-desc" class="text-sm text-gray-600 text-center">{{ $batch->product->description ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="mt-8">
                    <button type="submit" class="submit-btn">Update Batch & Labor Assignments</button>
                </div>
            </form>

            <!-- Add New Client Modal (unchanged) -->
             <div id="add-client-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
                    <div class="flex justify-between items-center sticky top-0 bg-white z-10 border-b border-gray-200 py-4 px-6">
                        <h2 class="text-2xl font-bold text-blue-600">Add New Client</h2>
                        <button type="button" id="close-client-modal" class="text-gray-400 hover:text-gray-600 text-3xl font-bold transition-all">&times;</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6">
                        <form id="add-client-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @csrf
                            <div class="col-span-1">
                                <label for="business_name" class="block text-sm font-medium text-gray-700">Business/Company Name <span class="text-red-500">*</span></label>
                                <input type="text" id="business_name" name="business_name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                <p id="business_name_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div class="col-span-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">Contact Person Name <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" >
                                <p id="name_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div class="col-span-1 md:col-span-2 lg:col-span-3">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                                <textarea id="address" name="address" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" ></textarea>
                                <p id="address_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                                <select id="category" name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="wholesale">Wholesale</option>
                                    <option value="retail">Retail</option>
                                </select>
                                <p id="category_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div>
                                <label for="gst_no" class="block text-sm font-medium text-gray-700">GST No <span class="text-red-500">*</span></label>
                                <input type="text" id="gst_no" name="gst_no" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                <p id="gst_no_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" @if(!auth()->user()->is_remote)  @endif>
                                <p id="email_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div>
                                <label for="sales_rep_id" class="block text-sm font-medium text-gray-700">Assign Sales Rep @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                <select id="sales_rep_id" name="sales_rep_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" @if(!auth()->user()->is_remote)  @endif>
                                    <option value="">-- Select Sales Rep --</option>
                                    @foreach($salesReps as $rep)
                                        <option value="{{ $rep->id }}">{{ $rep->name }}</option>
                                    @endforeach
                                </select>
                                <p id="sales_rep_id_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                <input type="text" id="phone" name="phone" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" @if(!auth()->user()->is_remote)  @endif>
                                <p id="phone_error" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Company Document @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                    <input type="file" name="company_document" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf" @if(!auth()->user()->is_remote)  @endif>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Aadhar Certificate @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                    <input type="file" name="aadhar_certificate" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf" @if(!auth()->user()->is_remote)  @endif>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">GST Certificate @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                    <input type="file" name="gst_certificate" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf" @if(!auth()->user()->is_remote)  @endif>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Electricity Bill @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                    <input type="file" name="electricity_certificate" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf" @if(!auth()->user()->is_remote)  @endif>
                                </div>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" @if(!auth()->user()->is_remote)  @endif>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password @if(!auth()->user()->is_remote) <span class="text-red-500">*</span> @endif</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" @if(!auth()->user()->is_remote)  @endif>
                            </div>
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 flex justify-end gap-3 mt-6">
                                <button type="button" id="cancel-client-btn" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                                <button type="submit" id="save-client-btn" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Save Client</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
        @endif
    </div>
</div>


<style>
.submit-btn {
    width: 100%;
    background-color: #4f46e5;
    color: white;
    font-weight: 600;
    padding: 12px;
    border: none;
    border-radius: 8px;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.submit-btn:hover { background-color: #4338ca; transform: scale(1.02); }
.submit-btn:active { background-color: #3730a3; transform: scale(0.98); }
table th, table td { text-align: left; }
table tbody tr:hover { background-color: #f3f4f6; }
input.variations-input, input.table-input { 
    width: 100%; 
    border: 1px solid #d1d5db; 
    border-radius: 4px; 
    padding: 2px 4px; 
    font-size: 12px;
    height: 28px;
}
input.variations-input[data-size] { 
    width: 40px !important; 
    padding: 4px 2px;
}
.variations-input:focus, .table-input:focus, .worker-qty:focus, .start-date:focus, .end-date:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}
input[readonly], select[disabled] { background-color: #f3f4f6; cursor: not-allowed; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ==============================================================
       1️⃣ VARIATION ROWS (Add / Delete) → NOT USED ON EDIT PAGE
       ============================================================== */
    // This section is only for Create Batch page
    // On Edit page, variations are already loaded via Labor tables
    // So we safely ignore these (no #variations-details exists)
    const variationsTbody = document.getElementById('variations-details');
    const addVariationBtn = document.getElementById('add-variation');

    // Only attach if elements exist (prevents errors on Edit page)
    if (addVariationBtn && variationsTbody) {
        addVariationBtn.addEventListener('click', () => {
            const index = variationsTbody.querySelectorAll('tr').length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border px-3 py-2"><input type="text" name="variations[${index}][color]" class="variations-input" placeholder="Color"></td>
                ${Array.from({ length: 10 }, (_, i) => {
                    const size = 35 + i;
                    return `<td class="border px-3 py-2 text-center">
                        <input type="number" min="0" name="variations[${index}][${size}]" value="0" class="variations-input" data-size="${size}">
                    </td>`;
                }).join('')}
                <td class="border px-3 py-2"><input type="text" name="variations[${index}][sole_color]" class="variations-input" placeholder="Sole Color"></td>
                <td class="border px-3 py-2 text-center">
                    <button type="button" class="delete-variation bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </button>
                </td>`;
            variationsTbody.appendChild(row);
        });

        variationsTbody.addEventListener('click', e => {
            if (e.target.closest('.delete-variation')) {
                e.target.closest('tr').remove();
            }
        });
    }

    /* ==============================================================
       2️⃣ WORKER CHECKBOX — Enable/Disable Quantity & Dates
       ============================================================== */
    function updateWorkerRow(checkbox) {
        const row = checkbox.closest('tr');
        const isChecked = checkbox.checked;
        const qtyInputs = row.querySelectorAll('.worker-qty');
        const dateInputs = row.querySelectorAll('[name^="start_date"], [name^="end_date"]');

        qtyInputs.forEach(input => {
            input.disabled = !isChecked;
            if (!isChecked) input.value = '0';
        });
        dateInputs.forEach(input => {
            input.disabled = !isChecked;
            if (!isChecked) input.value = '';
        });

        qtyInputs.forEach(i => i.dispatchEvent(new Event('input')));
    }

    document.querySelectorAll('.worker-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', () => updateWorkerRow(checkbox));
        updateWorkerRow(checkbox);
    });

    /* ==============================================================
       3️⃣ LIVE QTY VALIDATION + REMAINING CALCULATION
       ============================================================== */
    document.querySelectorAll('.worker-qty').forEach(input => {
        const updateQty = () => {
            const processId = input.dataset.processId;
            const size = input.dataset.size;
            const variationIndex = input.dataset.variationIndex;

            const allInputs = document.querySelectorAll(
                `.worker-qty[data-process-id="${processId}"][data-size="${size}"][data-variation-index="${variationIndex}"]`
            );

            let totalAssigned = 0;
            allInputs.forEach(i => {
                const chk = i.closest('tr')?.querySelector('.worker-checkbox');
                if (chk?.checked && i !== input) {
                    totalAssigned += parseInt(i.value) || 0;
                }
            });

            const summary = document.querySelector(
                `.size-summary[data-process-id="${processId}"][data-size="${size}"][data-variation-index="${variationIndex}"]`
            );
            const availableBase = parseInt(summary?.dataset.available || 0);
            const remaining = Math.max(availableBase - totalAssigned, 0);

            let value = parseInt(input.value) || 0;

            if (value > remaining) {
                value = remaining;
                input.value = remaining;
                input.classList.add('border-red-500', 'bg-red-50');
                const tip = input.parentElement.querySelector('.over-limit');
                if (tip) {
                    tip.textContent = `Max: ${remaining}`;
                    tip.classList.remove('hidden');
                }
                input.style.transition = 'transform 0.1s';
                input.style.transform = 'scale(1.05)';
                setTimeout(() => input.style.transform = '', 100);
            } else {
                input.classList.remove('border-red-500', 'bg-red-50');
                const tip = input.parentElement.querySelector('.over-limit');
                if (tip) tip.classList.add('hidden');
            }

            // Recalculate total after clamping
            let totalAfter = 0;
            allInputs.forEach(i => {
                const chk = i.closest('tr')?.querySelector('.worker-checkbox');
                if (chk?.checked) totalAfter += parseInt(i.value) || 0;
            });

            if (summary) {
                const newRemaining = Math.max(availableBase - totalAfter, 0);
                summary.textContent = newRemaining;

                summary.classList.remove('text-red-600', 'text-green-700', 'font-bold', 'text-yellow-600', 'font-semibold');
                if (newRemaining === 0) summary.classList.add('text-green-700', 'font-bold');
                else if (newRemaining < availableBase) summary.classList.add('text-yellow-600', 'font-semibold');
                else summary.classList.add('text-red-600', 'font-bold');
            }
        };

        input.addEventListener('input', updateQty);
        input.addEventListener('blur', updateQty);
        input.addEventListener('focus', () => { if (input.value === '0') input.value = ''; });
        input.addEventListener('blur', () => { if (input.value === '') input.value = '0'; updateQty(); });
    });

    // Initialize summaries
    document.querySelectorAll('.worker-qty').forEach(input => input.dispatchEvent(new Event('input')));

    /* ==============================================================
       4️⃣ PARTY (CLIENT) MODAL HANDLING
       ============================================================== */
    const openPartyEditor = document.getElementById('open-party-editor');
    const partyEditor = document.getElementById('party-editor');
    const addNewPartyBtn = document.getElementById('add-new-party-btn');
    const clientModal = document.getElementById('add-client-modal');
    const closeModalBtn = document.getElementById('close-client-modal');
    const cancelBtn = document.getElementById('cancel-client-btn');
    const clientForm = document.getElementById('add-client-form');

    openPartyEditor?.addEventListener('click', () => {
        partyEditor.classList.remove('hidden');
        openPartyEditor.classList.add('hidden');
    });

    addNewPartyBtn?.addEventListener('click', () => {
        clientModal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        clientForm.reset();
        clientForm.querySelectorAll('.text-red-500').forEach(el => {
            el.textContent = ''; el.classList.add('hidden');
        });
    });

    const closeClientModal = () => {
        clientModal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    };
    closeModalBtn?.addEventListener('click', closeClientModal);
    cancelBtn?.addEventListener('click', closeClientModal);

    clientForm?.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(clientForm);
        clientForm.querySelectorAll('.text-red-500').forEach(el => el.textContent = '', el.classList.add('hidden'));

        try {
            const res = await fetch('{{ route('clients.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: fd
            });
            const data = await res.json();

            if (!res.ok) {
                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, msgs]) => {
                        const el = document.getElementById(`${field}_error`);
                        if (el) { el.textContent = msgs[0]; el.classList.remove('hidden'); }
                    });
                }
                throw new Error('Validation failed');
            }

            const client = data.client;
            const label = document.createElement('label');
            label.className = 'flex items-center space-x-2 cursor-pointer';
            label.innerHTML = `
                <input type="checkbox" name="client_ids[]" value="${client.id}" class="party-checkbox rounded" checked>
                <span>${client.name} (${client.category ? client.category.charAt(0).toUpperCase() + client.category.slice(1) : ''})</span>
            `;
            partyEditor.insertBefore(label, addNewPartyBtn);
            alert('Party added & selected!');
            closeClientModal();
        } catch (err) {
            alert('Failed to add party: ' + err.message);
        }
    });

    /* ==============================================================
       5️⃣ FORM SUBMISSION — REMOVED VARIATION VALIDATION ON EDIT
       ============================================================== */
    // On Edit page: variations are already stored in DB
    // No need to validate or re-serialize them
    // Just let the form submit normally
    document.getElementById('batch-form')?.addEventListener('submit', function (e) {
        // Safe to submit — no blocking
        return true;
    });

    /* ==============================================================
       6️⃣ PRODUCT IMAGE AUTO LOAD (Optional)
       ============================================================== */
    const articleId = document.getElementById('article_no')?.value;
    if (articleId && document.getElementById('product-image-card')) {
        // Optional: keep if you want dynamic load, or remove if not needed
    }
});
</script>

@endsection