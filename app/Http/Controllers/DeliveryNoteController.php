<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\Batch;
use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeliveryNoteController extends Controller
{
    /**
     * Generate full delivery note for the entire batch (legacy flow)
     */
    public function create($batchId)
    {
        $batch = Batch::with(['product', 'clients'])->findOrFail($batchId);

        if ($batch->deliveryNote) {
            return redirect()
                ->route('delivery.note.show', $batch->deliveryNote->id)
                ->with('info', 'Delivery note already exists for this batch.');
        }

       $variations = $batch->variations ?? [];


        $deliveryNote = DeliveryNote::create([
            'delivery_note_no' => 'DN-' . date('YmdHis'),
            'batch_id' => $batch->id,
            'delivery_date' => now(),
            'items' => $variations, // ✅ store as array, not json string
        ]);

        return redirect()
            ->route('delivery.note.show', $deliveryNote->id)
            ->with('success', 'Delivery Note created successfully!');
    }

    /**
     * View delivery note details
     */
    public function show($id)
    {
        $deliveryNote = DeliveryNote::with(['client', 'batch.product', 'batch.clients'])->findOrFail($id);

        // ✅ Make sure items are array
        if (is_string($deliveryNote->items)) {
            $decoded = json_decode($deliveryNote->items, true);
            $deliveryNote->items = is_array($decoded) ? $decoded : [];
        }

        return view('production.delivery_note', compact('deliveryNote'));
    }

    public function updatePartial(Request $request, $id)
    {
        $validated = $request->validate([
            'sizes' => 'required|array',
        ]);

        $deliveryNote = DeliveryNote::findOrFail($id);

        $totalQty = collect($validated['sizes'])
            ->flatMap(fn($sizes) => $sizes)
            ->sum();

        $filteredItems = collect($validated['sizes'])->map(function ($sizes, $index) use ($deliveryNote) {
            $originalItems = is_string($deliveryNote->items)
                ? json_decode($deliveryNote->items, true)
                : ($deliveryNote->items ?? []);

            $color = $originalItems[$index]['color'] ?? '-';
            $soleColor = $originalItems[$index]['sole_color'] ?? '-';

            return [
                'color' => $color,
                'sole_color' => $soleColor,
                'sizes' => collect($sizes)
                    ->filter(fn($qty) => $qty > 0)
                    ->toArray(),
            ];
        })->filter(fn($item) => !empty($item['sizes']))->values()->toArray();

        // ✅ don't encode to JSON manually
        $deliveryNote->update([
            'items' => $filteredItems,
            'assigned_qty' => $totalQty,
        ]);

        return redirect()
            ->route('delivery.note.show', $deliveryNote->id)
            ->with('success', 'Delivery Note updated successfully!');
    }

    /**
     * Generate partial delivery note for selected client and quantity
     */
public function storePartial(Request $request)
{
    Log::info('🔵 storePartial() STARTED', ['input' => $request->all()]);

    $validated = $request->validate([
        'batch_id' => 'required|exists:batches,id',
        'client_id' => 'required|exists:users,id',
        'client_sizes' => 'nullable|array',
        'sizes' => 'nullable|array',
    ]);

    $batch = Batch::with('product')->findOrFail($validated['batch_id']);

    // Decode batch variations
    $batchVariations = is_string($batch->variations)
        ? json_decode($batch->variations, true)
        : ($batch->variations ?? []);
    if (!is_array($batchVariations)) $batchVariations = [];

    // Decode labor assignments
    $laborAssignments = is_string($batch->labor_assignments)
        ? json_decode($batch->labor_assignments, true)
        : ($batch->labor_assignments ?? []);
    if (!is_array($laborAssignments)) $laborAssignments = [];

    Log::info('🧩 Batch Variations Loaded', ['batchVariations' => $batchVariations]);
    Log::info('🧵 Labor Assignments Loaded', ['laborAssignments' => $laborAssignments]);

    // Build built map
    $builtMap = [];
    $fallbackColor = $batchVariations[0]['color'] ?? 'unknown';
    $fallbackSole  = $batchVariations[0]['sole_color'] ?? 'unknown';

    foreach ($laborAssignments as $assign) {
        foreach (($assign['variations'] ?? []) as $v) {

            if (!isset($v['sizes'])) {
                $color = $fallbackColor;
                $sole  = $fallbackSole;
                $sizes = $v;
            } else {
                $color = $v['color'] ?? $fallbackColor;
                $sole  = $v['sole_color'] ?? $fallbackSole;
                $sizes = $v['sizes'] ?? [];
            }

            foreach ($sizes as $size => $qty) {
                if (!is_numeric($size)) continue;
                $key = "{$color}|{$sole}|{$size}";
                $builtMap[$key] = ($builtMap[$key] ?? 0) + (int)$qty;
            }
        }
    }

    Log::info('🏗️ Built Map', ['builtMap' => $builtMap]);

    // Delivered map
    $deliveredMap = [];
    foreach ($batchVariations as $v) {
        $color = $v['color'] ?? $fallbackColor;
        $sole  = $v['sole_color'] ?? $fallbackSole;

        foreach (($v['sizes'] ?? []) as $size => $info) {
            if (!is_numeric($size)) continue;

            $key = "{$color}|{$sole}|{$size}";
            $deliveredMap[$key] = is_array($info) ? (int)($info['delivered'] ?? 0) : 0;
        }
    }

    Log::info('📦 Delivered Map', ['deliveredMap' => $deliveredMap]);

    // Extract chosen sizes
    $sizeData = $request->client_sizes[$validated['client_id']] ?? $request->sizes ?? [];
    Log::info('🟡 sizeData From UI', ['sizeData' => $sizeData]);

    $totalAssigned = 0;
    $filteredItems = [];

    foreach ($sizeData as $vIndex => $sizes) {
        if (empty($sizes)) continue;

        $variation = $batchVariations[$vIndex] ?? [];
        $color = $variation['color'] ?? $fallbackColor;
        $sole  = $variation['sole_color'] ?? $fallbackSole;
        $variationSizes = $variation['sizes'] ?? [];

        $noteSizes = [];

        foreach ($sizes as $size => $assignedQty) {

            $assignedQty = (int)$assignedQty;
            if ($assignedQty <= 0) continue;

            $key = "{$color}|{$sole}|{$size}";
            $built = (int)($builtMap[$key] ?? 0);

            // detect quotation batch
            $current = $variationSizes[$size] ?? 0;
            $isQuotation = is_array($current);

            if ($isQuotation) {
                $ordered    = (int)($current['ordered'] ?? 0);
                $alreadyDel = (int)($current['delivered'] ?? 0);
            } else {
                $ordered    = is_array($current) ? (int)($current['ordered'] ?? 0) : (int)$current;
                $alreadyDel = (int)($deliveredMap[$key] ?? 0);
            }

            $deliverable = max($built - $alreadyDel, 0);

            $assign = min($assignedQty, $deliverable);
            if ($assign <= 0) continue;

            $newDelivered = $alreadyDel + $assign;
            $newAvailable = max($built - $newDelivered, 0);

            $variationSizes[$size] = [
                'ordered'   => $ordered,
                'available' => $newAvailable,
                'delivered' => $newDelivered,
            ];

            $noteSizes[$size] = [
                'ordered'   => $ordered,
                'available' => $newAvailable,
                'delivered' => $newDelivered,
            ];

            $totalAssigned += $assign;
            $deliveredMap[$key] = $newDelivered;
        }

        $batchVariations[$vIndex]['sizes'] = $variationSizes;

        if (!empty($noteSizes)) {
            $filteredItems[] = [
                'color'      => $color,
                'sole_color' => $sole,
                'sizes'      => $noteSizes,
                'source'     => 'quotation',
            ];
        }
    }

    Log::info('🧮 FINAL totalAssigned', ['totalAssigned' => $totalAssigned]);
    Log::info('🧮 filteredItems', ['filteredItems' => $filteredItems]);

    if ($totalAssigned <= 0) {
        return back()->with('error', 'No quantities assigned to deliver.');
    }

    $batch->variations = json_encode($batchVariations);
    $batch->save();

    Log::info('🟢 Batch variations saved successfully');

    $deliveryNote = DeliveryNote::create([
        'delivery_note_no' => 'DN-' . strtoupper(Str::random(6)),
        'batch_id'         => $batch->id,
        'client_id'        => $validated['client_id'],
        'delivery_date'    => now(),
        'assigned_qty'     => $totalAssigned,
        'items'            => $filteredItems,
    ]);

    Log::info('🟢 Delivery Note Created', ['id' => $deliveryNote->id]);

    return redirect()
        ->route('delivery.note.show', $deliveryNote->id)
        ->with('success', 'Delivery note created successfully!');
}


    public function byBatch($batchId)
    {
        $batch = \App\Models\Batch::with(['product', 'clients'])->findOrFail($batchId);

        $deliveryNotes = \App\Models\DeliveryNote::with(['batch.product', 'client'])
            ->where('batch_id', $batchId)
            ->latest()
            ->get()
            ->map(function ($note) {
                if (empty($note->assigned_qty) && is_array($note->items)) {
                    $note->assigned_qty = collect($note->items)->sum(function ($item) {
                        return collect($item['sizes'] ?? [])->sum();
                    });
                }

                $note->client_name = $note->client?->name ?? '—';

                if ($note->client_name === '—' && $note->batch?->clients) {
                    $note->client_name = $note->batch->clients->pluck('name')->join(', ');
                }

                $note->formatted_date = $note->delivery_date
                    ? \Carbon\Carbon::parse($note->delivery_date)->format('d M Y')
                    : '—';

                return $note;
            });

        return view('production.delivery_notes_list', compact('deliveryNotes', 'batch'));
    }
}
