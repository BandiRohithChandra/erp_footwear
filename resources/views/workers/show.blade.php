@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-6">Worker Details</h1>

    <div class="space-y-2">
        <p><strong>ID:</strong> {{ $worker->id }}</p>
        <p><strong>Name:</strong> {{ $worker->name }}</p>
        <p><strong>Email:</strong> {{ $worker->email ?? '-' }}</p>
        <p><strong>Phone:</strong> {{ $worker->phone ?? '-' }}</p>
        <p><strong>Role:</strong> {{ $worker->role }}</p>
        <p><strong>Status:</strong> {{ ucfirst($worker->status) }}</p>
        <p><strong>Hired At:</strong> {{ $worker->hired_at }}</p>
    </div>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('workers.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back</a>
        <a href="{{ route('workers.edit', $worker) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
    </div>
</div>
@endsection






public function updateStockOnBatchCreation($batch)
    {
        DB::transaction(function () use ($batch) {

            $variations = json_decode($batch->variations, true);
            $totalQty = 0;

            foreach ($variations as $colorSizes) {
                foreach ($colorSizes as $size => $qty) {
                    if ($size !== 'sole_color') $totalQty += (int) $qty;
                }
            }

            $product = $batch->product;

            // Helper to map material type to stock type
            $typeMap = [
                'Material' => 'material',
                'Liquid Material' => 'liquid',
                'Sole' => 'sole',
            ];

            $deductStock = function ($itemId, $materialType, $requiredQty) use ($batch, $typeMap) {
                $type = $typeMap[$materialType] ?? strtolower($materialType);

                $stock = Stock::where('item_id', $itemId)
                              ->where('type', $type)
                              ->lockForUpdate()
                              ->first();

                if (!$stock) {
                    throw new \Exception("❌ Stock record not found for {$type} (Item ID: {$itemId}) in Batch {$batch->batch_no}");
                }

                if ($stock->qty_available < $requiredQty) {
                    throw new \Exception("⚠️ Insufficient stock for {$type} (Item ID: {$itemId}). Required: {$requiredQty}, Available: {$stock->qty_available}");
                }

                $stock->qty_available -= $requiredQty;
                $stock->save();

                DB::table('stock_movements')->insert([
                    'batch_id'   => $batch->id,
                    'item_id'    => $itemId,
                    'type'       => $type,
                    'change'     => -$requiredQty,
                    'qty_after'  => $stock->qty_available,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            };

            // Deduct Materials
            foreach ($product->materials as $material) {
                $requiredQty = $totalQty * ($material->qty_per_unit ?? 1);
                $deductStock($material->id, 'Material', $requiredQty);
            }

            // Deduct Liquid Materials
            foreach ($product->liquidMaterials as $liquid) {
                $requiredQty = $totalQty * ($liquid->qty_per_unit ?? 1);
                $deductStock($liquid->id, 'Liquid Material', $requiredQty);
            }

            // Deduct Soles
            foreach ($product->tableSoles as $sole) {
                $requiredQty = $totalQty * ($sole->qty_per_unit ?? 1);
                $deductStock($sole->id, 'Sole', $requiredQty);
            }

            // Save labor usage
            foreach ($product->processes as $process) {
                $assignedQty = $process->pivot->assigned_qty ?? $totalQty;
                $laborRate   = $process->pivot->labor_rate ?? 0;

                DB::table('batch_labor_usage')->insert([
                    'batch_id'    => $batch->id,
                    'process_id'  => $process->id,
                    'quantity'    => $assignedQty,
                    'labor_rate'  => $laborRate,
                    'total_cost'  => $assignedQty * $laborRate,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        });
    }




    
public function reverseStockOnBatchDelete($batch)
    {
        DB::transaction(function () use ($batch) {
            $movements = DB::table('stock_movements')->where('batch_id', $batch->id)->orderBy('id', 'desc')->get();

            foreach ($movements as $movement) {
                $stock = Stock::where('item_id', $movement->item_id)
                              ->where('type', $movement->type)
                              ->lockForUpdate()
                              ->first();

                if ($stock) {
                    $stock->qty_available -= $movement->change; // movement->change is negative
                    $stock->save();
                }
            }

            DB::table('stock_movements')->where('batch_id', $batch->id)->delete();
            DB::table('batch_labor_usage')->where('batch_id', $batch->id)->delete();
            $batch->delete();
        });
}
