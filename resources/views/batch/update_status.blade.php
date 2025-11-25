@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-center gap-6 mb-8 justify-between">
      <h2 class="text-3xl font-bold text-gray-900">Update Labors Status</h2>
      <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Back</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg shadow">
        {{ session('success') }}
      </div>
    @endif

    @php
      $batches = $batches->sortByDesc('created_at');
    @endphp

    {{-- ✅ Each batch has its own form --}}
    @foreach($batches as $batch)
    <form action="{{ route('batch.flow.save_status') }}" method="POST" class="batch-form mb-10">
      @csrf
      <input type="hidden" name="selected_batch_id" value="{{ $batch->id }}">

      <div class="bg-white shadow rounded-2xl overflow-x-auto">

        {{-- Batch Header --}}
        <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
          <div class="flex items-center gap-4">
            @php
              $variations = $batch->product->variations ?? [];
              $mainImage = $batch->product->image;
              $variationImage = !empty($variations[0]['images'][0]) ? $variations[0]['images'][0] : null;
              $finalImage = $mainImage ?: $variationImage;
              $imageUrl = $finalImage ? asset('storage/' . $finalImage) : asset('images/default-product.png');
            @endphp

            <img src="{{ $imageUrl }}" alt="{{ $batch->product->name ?? 'No Image' }}"
                 class="w-20 h-20 object-cover rounded-lg border shadow-sm {{ !$finalImage ? 'opacity-60' : '' }}">

            <div>
              <h3 class="text-xl font-semibold text-gray-800">
                {{ $batch->batch_no }}
                <small class="text-gray-500">({{ $batch->product->name ?? '-' }})</small>
              </h3>
              <p class="text-gray-500 text-sm">Created: {{ $batch->created_at->format('d M, Y') }}</p>

              @if(!empty($batch->variations))
                @php
                  $raw = $batch->variations;
                  if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $variations = $decoded ?: explode(',', $raw);
                  } elseif (is_array($raw)) {
                    $variations = $raw;
                  } else {
                    $variations = [];
                  }
                  $flattened = collect($variations)->flatten()->filter()->all();
                @endphp
                <p class="text-gray-600 text-sm mt-1">
                  <strong>Variations:</strong> {{ implode(', ', $flattened) }}
                </p>
              @endif
            </div>
          </div>

          <div class="text-right">
            <p class="text-sm text-gray-600">
              Status:
              <span class="font-medium text-blue-700">{{ ucfirst($batch->status ?? 'N/A') }}</span>
            </p>
            @if($batch->client)
              <p class="text-sm text-gray-600">Client: {{ $batch->client->name }}</p>
            @endif
          </div>
        </div>

        {{-- Workers Table --}}
        @if($batch->workers->count())
        <table class="min-w-full divide-y divide-gray-200 table-auto">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-center">
                <input type="checkbox" class="select-all" data-batch="{{ $batch->id }}">
              </th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Employee ID</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Process</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor Rate</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($batch->workers as $worker)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-center">
                  <input type="checkbox" name="selected_workers[]" class="worker-checkbox" data-batch="{{ $batch->id }}" value="{{ $worker->id }}">
                  <input type="hidden" name="workers[{{ $worker->id }}][batch_id]" value="{{ $batch->id }}">
                  <input type="hidden" name="workers[{{ $worker->id }}][process_id]" value="{{ $worker->pivot->process_id }}">
                </td>
                <td class="px-4 py-2">{{ $worker->employee_id }}</td>
                <td class="px-4 py-2">{{ $worker->name }}</td>
                <td class="px-4 py-2">{{ \App\Models\ProductionProcess::find($worker->pivot->process_id)->name ?? 'N/A' }}</td>
                <td class="px-4 py-2">
                  <input type="number" name="workers[{{ $worker->id }}][quantity]" value="{{ $worker->pivot->quantity ?? 0 }}"
                         class="w-20 px-2 py-1 border rounded-md text-right">
                </td>
                <td class="px-4 py-2">
                  @php
                    $processRate = $batch->product->processes->firstWhere('id', $worker->pivot->process_id)->pivot->labor_rate ?? 0;
                  @endphp
                  ₹{{ number_format($processRate, 2) }}
                  <input type="hidden" name="workers[{{ $worker->id }}][labor_rate]" value="{{ $processRate }}">
                </td>
                <td class="px-4 py-2">
                  @php $status = $worker->pivot->labor_status ?? 'pending'; @endphp
                  <select name="workers[{{ $worker->id }}][labor_status]" 
                          class="px-3 py-2 border rounded-md w-32 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                  </select>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="flex justify-end items-center mt-4 mb-4 pr-4 space-x-4">
          <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all">
            Apply
          </button>
        </div>
        @endif
      </div>
    </form>
    @endforeach
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.select-all').forEach(selectAll => {
    const batchId = selectAll.dataset.batch;
    const checkboxes = document.querySelectorAll(`.worker-checkbox[data-batch="${batchId}"]`);

    selectAll.addEventListener('change', function() {
      checkboxes.forEach(cb => cb.checked = this.checked);
    });
  });
});
</script>
@endsection
