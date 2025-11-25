@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6" style="font-family: 'Inter', Arial, Helvetica, sans-serif;">
  <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

    <!-- 🔹 Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
          📦 Delivery Notes
        </h2>
        <p class="text-sm text-gray-500 mt-1">
          Batch No: <span class="font-medium text-gray-700">{{ $batch->batch_no }}</span>
        </p>
      </div>
      <a href="{{ url()->previous() }}" 
         class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-sm font-medium px-4 py-2 rounded-lg transition-all duration-200">
        ← Back
      </a>
    </div>

    <!-- 🔹 Content -->
    @if($deliveryNotes->isEmpty())
      <div class="flex items-center justify-center py-16 text-gray-500">
        <div class="text-center">
          <svg class="mx-auto mb-3 w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-lg font-medium">No delivery notes found</p>
          <p class="text-sm text-gray-400 mt-1">Try creating a new delivery note for this batch.</p>
        </div>
      </div>
    @else
      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full text-sm text-left border-collapse">
          <thead class="bg-gradient-to-r from-indigo-100 to-indigo-50 border-b border-gray-200">
            <tr>
              <th class="p-3 font-semibold text-gray-700">Note No</th>
              <th class="p-3 font-semibold text-gray-700">Party</th>
              <th class="p-3 font-semibold text-gray-700">Date</th>
              <th class="p-3 font-semibold text-gray-700">Assigned Qty</th>
              <th class="p-3 text-center font-semibold text-gray-700">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($deliveryNotes as $note)
              <tr onclick="window.location='{{ route('delivery.note.show', $note->id) }}'" 
                  class="border-b cursor-pointer hover:bg-indigo-50 transition-all duration-200 hover:shadow-sm group">
                
                <td class="p-3 font-medium text-gray-800 group-hover:text-indigo-600 transition">
                  {{ $note->delivery_note_no }}
                </td>
                <td class="p-3 text-gray-700">{{ $note->client_name }}</td>
                <td class="p-3 text-gray-600">
                  {{ \Carbon\Carbon::parse($note->delivery_date)->format('d M Y') }}
                </td>
                <td class="p-3 font-semibold text-indigo-600">{{ $note->assigned_qty ?? '—' }}</td>
                <td class="p-3 text-center">
                  <a href="{{ route('delivery.note.show', $note->id) }}" 
                     class="inline-flex items-center justify-center gap-1 bg-indigo-500 text-white text-xs font-medium px-3 py-1.5 rounded-full hover:bg-indigo-600 shadow transition-all duration-200">
                    🔍 View
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
