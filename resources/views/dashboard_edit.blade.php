@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-12">
    <h1 class="text-3xl font-extrabold text-gray-900">{{ __('Edit Dashboard') }}</h1>
    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('Back to Dashboard') }}
    </a>
</div>

<form action="{{ route('dashboard.update') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-8 max-w-6xl mx-auto">
    @csrf
    @method('PUT')

    @php
        $cardColors = [
            'finance_summary' => ['bg' => 'bg-gradient-to-r from-blue-50 to-blue-100', 'dot' => 'bg-blue-500'],
            'hr_summary' => ['bg' => 'bg-gradient-to-r from-green-50 to-green-100', 'dot' => 'bg-green-500'],
            'production_kpis' => ['bg' => 'bg-gradient-to-r from-yellow-50 to-yellow-100', 'dot' => 'bg-yellow-500'],
            'low_stock_alerts' => ['bg' => 'bg-gradient-to-r from-red-50 to-red-100', 'dot' => 'bg-red-500'],
            'charts_section' => ['bg' => 'bg-gradient-to-r from-purple-50 to-purple-100', 'dot' => 'bg-purple-500'],
        ];
        $customCardColors = [
    'gray'     => ['bg' => 'bg-gray-50',    'hover' => 'hover:bg-gray-100',   'text' => 'text-gray-900',   'value' => 'text-gray-600',   'dot' => 'bg-gray-500'],
    'blue'     => ['bg' => 'bg-blue-50',    'hover' => 'hover:bg-blue-100',   'text' => 'text-blue-900',   'value' => 'text-blue-600',   'dot' => 'bg-blue-500'],
    'teal'     => ['bg' => 'bg-teal-50',    'hover' => 'hover:bg-teal-100',   'text' => 'text-teal-900',   'value' => 'text-teal-600',   'dot' => 'bg-teal-500'],
    'indigo'   => ['bg' => 'bg-indigo-50',  'hover' => 'hover:bg-indigo-100', 'text' => 'text-indigo-900', 'value' => 'text-indigo-600', 'dot' => 'bg-indigo-500'],
    'purple'   => ['bg' => 'bg-purple-50',  'hover' => 'hover:bg-purple-100', 'text' => 'text-purple-900', 'value' => 'text-purple-600', 'dot' => 'bg-purple-500'],
    'rose'     => ['bg' => 'bg-rose-50',    'hover' => 'hover:bg-rose-100',   'text' => 'text-rose-900',   'value' => 'text-rose-600',   'dot' => 'bg-rose-500'],
    'orange'   => ['bg' => 'bg-orange-50',  'hover' => 'hover:bg-orange-100', 'text' => 'text-orange-900', 'value' => 'text-orange-600', 'dot' => 'bg-orange-500'],
    'lime'     => ['bg' => 'bg-lime-50',    'hover' => 'hover:bg-lime-100',   'text' => 'text-lime-900',   'value' => 'text-lime-600',   'dot' => 'bg-lime-500'],
    'cyan'     => ['bg' => 'bg-cyan-50',    'hover' => 'hover:bg-cyan-100',   'text' => 'text-cyan-900',   'value' => 'text-cyan-600',   'dot' => 'bg-cyan-500'],
    'slate'    => ['bg' => 'bg-slate-50',   'hover' => 'hover:bg-slate-100',  'text' => 'text-slate-900',  'value' => 'text-slate-600',  'dot' => 'bg-slate-500'],
    'fuchsia'  => ['bg' => 'bg-fuchsia-50', 'hover' => 'hover:bg-fuchsia-100','text' => 'text-fuchsia-900','value' => 'text-fuchsia-600','dot' => 'bg-fuchsia-500'],
];
    @endphp

    {{-- Dashboard Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Standard Cards --}}
        @foreach($standardCards as $key => $label)
            @php $colors = $cardColors[$key]; @endphp
            <label class="flex items-center justify-between p-6 rounded-xl {{ $colors['bg'] }} border border-gray-200 shadow hover:shadow-2xl cursor-pointer transition-all duration-300">
                <div class="flex items-center gap-4">
                    <span class="w-3 h-3 rounded-full {{ $colors['dot'] }}"></span>
                    <span class="font-semibold text-gray-900">{{ $label }}</span>
                </div>
                <input type="checkbox" name="cards[]" value="{{ $key }}" @if(in_array($key, $userCards)) checked @endif class="toggle-switch h-6 w-6">
            </label>
        @endforeach

        {{-- Custom Cards --}}
        @foreach($userAddedCustomCards as $key => $label)
            @php
                $customColor = $customLabels[$key]['color'] ?? 'gray';
                $colors = $customCardColors[$customColor] ?? $customCardColors['gray'];
            @endphp
            <label class="flex flex-col justify-between p-6 rounded-xl {{ $colors['bg'] }} border border-gray-200 shadow hover:shadow-2xl cursor-pointer transition-all duration-300">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full {{ $colors['dot'] }}"></span>
                    <input type="text" name="custom_labels[{{ $key }}][label]" value="{{ $label }}" class="font-semibold text-gray-900 border-b border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-400 w-full" placeholder="Card Label">
                </div>
                <input type="text" name="custom_labels[{{ $key }}][url]" value="{{ $customLabels[$key]['url'] ?? '' }}" placeholder="Optional URL" class="mt-2 text-sm text-gray-600 border-b border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-400 w-full">
                <select name="custom_labels[{{ $key }}][color]" class="mt-2 text-sm border-b border-gray-300 bg-transparent focus:outline-none focus:ring-1 focus:ring-blue-400 w-40">
                    @foreach($customCardColors as $colorKey => $color)
                        <option value="{{ $colorKey }}" @if($customColor === $colorKey) selected @endif>{{ ucfirst($colorKey) }}</option>
                    @endforeach
                </select>
                <input type="checkbox" name="cards[]" value="{{ $key }}" checked class="toggle-switch mt-3 h-6 w-6">
            </label>
        @endforeach
    </div>

    {{-- Add New Custom Card --}}
    <div class="mt-12 p-6 border border-gray-200 rounded-xl shadow-md bg-gray-50">
        <h2 class="text-xl font-semibold mb-4">{{ __('Add New Custom Card') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <input type="text" name="new_custom_key" placeholder="Card Key (unique)" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="text" name="new_custom_label" placeholder="Card Label" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="text" name="new_custom_url" placeholder="Optional URL" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <select name="new_custom_color" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @foreach($customCardColors as $colorKey => $color)
                    <option value="{{ $colorKey }}">{{ ucfirst($colorKey) }}</option>
                @endforeach
            </select>
        </div>
        <p class="mt-2 text-sm text-gray-500">Card Key must be unique. URL is optional.</p>
    </div>

    {{-- Save Button --}}
    <div class="mt-10 text-right">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition-all text-white font-semibold px-7 py-3 rounded-xl shadow-lg">
            {{ __('Save Dashboard') }}
        </button>
    </div>
</form>

{{-- Tailwind toggle switch --}}
<style>
.toggle-switch {
  -webkit-appearance: none;
  appearance: none;
  background-color: #d1d5db;
  outline: none;
  width: 36px;
  height: 20px;
  border-radius: 9999px;
  position: relative;
  cursor: pointer;
  transition: background-color 0.2s;
}
.toggle-switch:checked {
  background-color: #3b82f6;
}
.toggle-switch::before {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  border-radius: 9999px;
  top: 2px;
  left: 2px;
  background-color: #fff;
  transition: transform 0.2s;
}
.toggle-switch:checked::before {
  transform: translateX(16px);
}
</style>
@endsection
