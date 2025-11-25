@extends('layouts.app')

@section('content')
<div class="bg-gray-50 p-4 sm:p-6 md:p-8 rounded-lg">

    {{-- Breadcrumb --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 sm:space-x-2">
            <li class="inline-flex items-center">
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Inventory</a>
                <svg class="w-4 h-4 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7.05 4.05a.5.5 0 01.7 0l6 6a.5.5 0 010 .7l-6 6a.5.5 0 11-.7-.7L12.29 10 7.05 4.75a.5.5 0 010-.7z"/>
                </svg>
            </li>
            <li class="text-gray-700 font-medium">{{ $product->name }}</li>
        </ol>
    </nav>

    {{-- Main Product Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 sm:p-6 rounded-lg shadow-md mb-8">
        {{-- Product Image --}}
        <div class="flex flex-col sm:flex-row sm:gap-4">
            @php
                $variationsArray = $variations;
                $mainImage = data_get($variationsArray, '0.images.0', null);
            @endphp

            <div class="flex flex-col items-center sm:w-1/4">
                @if($mainImage)
                    <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" class="w-full max-w-[200px] sm:max-w-[100px] object-cover rounded-lg mb-2 sm:mb-0">
                @else
                    <div class="w-48 h-48 sm:w-24 sm:h-24 bg-gray-200 flex items-center justify-center text-gray-400 rounded-lg mb-2 sm:mb-0">No Image</div>
                @endif
            </div>

            <div class="flex-1 flex justify-center sm:justify-start">
                @if($mainImage)
                    <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" class="w-full max-w-[400px] sm:max-w-[350px] object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-full h-64 sm:h-96 bg-gray-200 flex items-center justify-center text-gray-400 rounded-lg shadow-lg">No Image</div>
                @endif
            </div>
        </div>

        {{-- Product Details --}}
        <div class="space-y-3 text-gray-800 text-sm sm:text-base">
            <h1 class="text-2xl font-bold text-gray-900 break-words">{{ $product->name }}</h1>
            <p><span class="font-semibold">SKU:</span> {{ $product->sku ?? '-' }}</p>
            <p><span class="font-semibold">Price:</span> <span class="text-red-600 text-lg font-bold">₹{{ number_format($product->price ?? 0, 2) }}</span></p>
            <p><span class="font-semibold">Unit Price:</span> ₹{{ number_format($product->unit_price ?? 0, 2) }}</p>
            <p><span class="font-semibold">Tax Rate:</span> {{ $product->tax_rate ?? 0 }}%</p>
            <p><span class="font-semibold">Tax Amount:</span> ₹{{ number_format($product->tax_amount ?? 0, 2) }}</p>
            <p><span class="font-semibold">Total Price:</span> ₹{{ number_format($product->total_price ?? 0, 2) }}</p>
            <p><span class="font-semibold">Commission:</span> 
                @if($product->commission !== null)
                    {{ $product->commission }}%
                @else
                    -
                @endif
            </p>
            <p><span class="font-semibold">Description:</span> {!! nl2br(e($product->description ?? '-')) !!}</p>

            <a href="{{ route('products.index') }}" class="inline-block mt-3 px-5 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
                Back to Inventory
            </a>
        </div>
    </div>

    {{-- Variations --}}
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">Variations</h2>
        @if(count($variationsArray) > 0)
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border border-gray-200 text-sm sm:text-base rounded">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border p-2 text-left">Color</th>
                            <th class="border p-2 text-left">Sizes</th>
                            <th class="border p-2 text-left">Price</th>
                            <th class="border p-2 text-left">Unit Price</th>
                            <!-- <th class="border p-2 text-left">GST</th> -->
                            <th class="border p-2 text-left">Quantity</th>
                            <th class="border p-2 text-left">Images</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($variationsArray as $variation)
                            @php
                                $sizes = data_get($variation, 'sizes', []);

                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border p-2 font-medium">{{ data_get($variation, 'color', '-') }}</td>
                                <td class="border p-2">{{ !empty($sizes) ? implode(', ', $sizes) : '-' }}</td>
                                <td class="border p-2 text-red-600 font-semibold">₹{{ number_format(data_get($variation, 'price', 0), 2) }}</td>
                                <td class="border p-2">₹{{ number_format(data_get($variation, 'unit_price', 0), 2) }}</td>
                                <!-- <td class="border p-2">{{ data_get($variation, 'gst', 0) }}%</td> -->
                                <td class="border p-2">{{ data_get($variation, 'quantity', 0) }}</td>
                                <td class="border p-2 flex flex-wrap gap-2">
                                    @if(!empty(data_get($variation, 'images')) && is_array($variation['images']))
                                        @foreach($variation['images'] as $image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ data_get($variation, 'color', '-') }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                        @endforeach
                                    @else
                                        <span class="text-gray-400">No Image</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- Soles Section --}}
<div class="bg-white p-4 sm:p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-lg sm:text-xl font-semibold mb-4">Soles</h2>
    @if(count($soles) > 0)
        <table class="w-full border border-gray-200 text-sm sm:text-base rounded">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border p-2 text-left">Name</th>
                    <th class="border p-2 text-left">Color</th>
                    <th class="border p-2 text-left">Type</th>
                    <th class="border p-2 text-left">Sizes / Qty</th>
                    <th class="border p-2 text-left">Price</th>
                    <th class="border p-2 text-left">Total Amount</th>
                </tr>
            </thead>
          <tbody>
    @foreach($soles as $sole)
        @php
            // Normalize sizes_qty — handle both array and JSON string cases
            $sizesQty = $sole['sizes_qty'] ?? [];
            if (is_string($sizesQty)) {
                $sizesQty = json_decode($sizesQty, true) ?? [];
            }

            // Calculate total quantity safely
            $totalQty = $sole['quantity'] ?? array_sum(
                array_map(fn($o) => $o['qty_available'] ?? 0, $sizesQty)
            );
        @endphp

        <tr>
            <td class="border p-2">{{ $sole['name'] ?? '-' }}</td>
            <td class="border p-2">{{ $sole['color'] ?? '-' }}</td>
            <td class="border p-2">{{ $sole['sole_type'] ?? '-' }}</td>

            <td class="border p-2">
                @if($sole['sole_type'] === 'piece')
                    {{ $sole['quantity'] ?? 0 }}
                @else
                    @if(!empty($sizesQty))
                        @foreach($sizesQty as $size => $obj)
                            <div>{{ $size }} : {{ $obj['qty_available'] ?? 0 }}</div>
                        @endforeach
                    @else
                        -
                    @endif
                @endif
            </td>

            <td class="border p-2">
                ₹{{ number_format($sole['price'] ?? 0, 2) }}
            </td>
            <td class="border p-2">
                ₹{{ number_format(($sole['price'] ?? 0) * $totalQty, 2) }}
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
    @else
        <p class="text-gray-500">No soles for this product.</p>
    @endif
</div>

{{-- Materials Section --}}
<div class="bg-white p-4 sm:p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-lg sm:text-xl font-semibold mb-4">Materials</h2>

    @if($product->materials->count() > 0 || $product->liquidMaterials->count() > 0)
        <table class="w-full border border-gray-200 text-sm sm:text-base rounded">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border p-2 text-left">Material Name</th>
                    <th class="border p-2 text-left">Color</th>
                    <th class="border p-2 text-left">Unit</th>
                    <th class="border p-2 text-left">Quantity</th>
                    <th class="border p-2 text-left">Per Unit Length / Volume</th>
                    <th class="border p-2 text-left">Type</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                {{-- 🧱 Regular Materials --}}
                @foreach($product->materials as $material)
                    <tr>
                        <td class="border p-2 font-medium">{{ $material->name ?? '-' }}</td>
                        <td class="border p-2">{{ $material->color ?? '-' }}</td>
                        <td class="border p-2 capitalize">{{ $material->unit ?? '-' }}</td>
                        <td class="border p-2">{{ $material->quantity ?? '-' }}</td>
                       <td class="border p-2">
    @if($material->unit === 'piece' && !empty($material->per_unit_length))
        {{ rtrim(rtrim(number_format($material->per_unit_length, 2), '0'), '.') }} m
    @else
        -
    @endif
</td>


                        <td class="border p-2 text-blue-700 font-semibold">Material</td>
                    </tr>
                @endforeach

                {{-- 💧 Liquid Materials --}}
                @foreach($product->liquidMaterials as $liquid)
                    <tr class="bg-gray-50">
                        <td class="border p-2 font-medium">{{ $liquid->name ?? '-' }} (Liquid)</td>
                        <td class="border p-2">-</td>
                        <td class="border p-2 capitalize">{{ $liquid->unit ?? '-' }}</td>
                        <td class="border p-2">{{ $liquid->quantity ?? '-' }}</td>
                        <td class="border p-2">
                            @if($liquid->unit === 'piece' && !empty($liquid->per_unit_volume))
                                {{ $liquid->per_unit_volume }} ml / L
                            @else
                                -
                            @endif
                        </td>
                        <td class="border p-2 text-green-700 font-semibold">Liquid</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500">No materials assigned for this product.</p>
    @endif
</div>



            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-4">
                @foreach($variationsArray as $variation)
                    @php
                        $sizes = data_get($variation, 'sizes', []);

                    @endphp
                    <div class="border rounded-lg p-4 shadow-sm">
                        <p><span class="font-semibold">Color:</span> {{ data_get($variation, 'color', '-') }}</p>
                        <p><span class="font-semibold">Sizes:</span> {{ !empty($sizes) ? implode(', ', $sizes) : '-' }}</p>
                        <p><span class="font-semibold">Price:</span> <span class="text-red-600 font-bold">₹{{ number_format(data_get($variation, 'price', 0), 2) }}</span></p>
                        <p><span class="font-semibold">Unit Price:</span> ₹{{ number_format(data_get($variation, 'unit_price', 0), 2) }}</p>
                        <p><span class="font-semibold">GST:</span> {{ data_get($variation, 'gst', 0) }}%</p>
                        <p><span class="font-semibold">Quantity:</span> {{ data_get($variation, 'quantity', 0) }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if(!empty(data_get($variation, 'images')) && is_array($variation['images']))
                                @foreach($variation['images'] as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ data_get($variation, 'color', '-') }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                @endforeach
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No variations available.</p>
        @endif
    </div>

</div>
@endsection
