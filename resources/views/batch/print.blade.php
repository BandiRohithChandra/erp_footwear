<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Batch {{ $batch->batch_no }} - Print</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #000; }
        h1, h2 { text-align: center; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        .no-border td { border: none; }
    </style>
</head>
<body onload="window.print()">

    <h1>Batch Details</h1>

    <table class="no-border">
        <tr>
            <td><strong>Batch No:</strong> {{ $batch->batch_no }}</td>
            <td><strong>PO No:</strong> {{ $batch->po_no ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Article:</strong> {{ $batch->product->name ?? '-' }}</td>
            <td><strong>Article No:</strong> {{ $batch->product->sku ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Status:</strong> {{ ucfirst($batch->status) }}</td>
            <td><strong>Created:</strong> {{ $batch->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Start Date:</strong> {{ $batch->start_date }}</td>
            <td><strong>End Date:</strong> {{ $batch->end_date ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Customer Code:</strong> {{ $batch->customer_code ?? '-' }}</td>
        </tr>
    </table>

    <h2>Sole Details</h2>
    <table>
        <thead>
            <tr><th>Name</th><th>Color</th><th>Subtype</th><th>Price</th></tr>
        </thead>
        <tbody>
            @foreach ($batch->product->soles ?? [] as $sole)
                <tr>
                    <td>{{ $sole['name'] ?? '-' }}</td>
                    <td>{{ $sole['color'] ?? '-' }}</td>
                    <td>{{ $sole['sub_type'] ?? '-' }}</td>
                    <td>₹{{ $sole['price'] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Variations</h2>
    @php
        $variations = $batch->variations ?? [];
        $allSizes = collect($variations)->flatMap(fn($s)=>array_keys($s))->unique()->sort()->toArray();
        $totalQty = collect($variations)->flatMap(fn($s)=>$s)->sum();
    @endphp
    <table>
        <thead>
            <tr>
                <th>Color</th>
                @foreach($allSizes as $size)
                    <th>{{ $size }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($variations as $color => $sizes)
                <tr>
                    <td>{{ $color }}</td>
                    @foreach($allSizes as $size)
                        <td>{{ $sizes[$size] ?? 0 }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total Quantity:</strong> {{ $totalQty }}</p>

    <h2>Assigned Workers</h2>
    <table>
        <thead>
            <tr><th>Worker</th><th>Process</th><th>Qty</th><th>Status</th><th>Start Date</th></tr>
        </thead>
        <tbody>
            @foreach ($batch->assignments as $assign)
                <tr>
                    <td>{{ $assign->worker->name }}</td>
                    <td>{{ $assign->process_name }}</td>
                    <td>{{ $assign->quantity }}</td>
                    <td>{{ ucfirst($assign->labor_status ?? 'pending') }}</td>
                    <td>{{ $assign->start_date ? \Carbon\Carbon::parse($assign->start_date)->format('d M Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
