@extends('layouts.app')

@section('content')
{{-- Overlay when onboarding is visible --}}
<div x-show="showCard" class="overlay"></div>

{{-- Onboarding Card --}}
<div x-show="showCard" class="onboarding-card">
    @include('clients.partials.onboarding-card')
</div>

<div class="dashboard-container protected-content" x-data="{ showCard: @json($showOnboarding) }">
    {{-- Dashboard Header --}}
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Welcome! Browse through your product categories</p>
    </div>

    {{-- Toggle Button --}}
<!-- <button 
    @click="showCard = false; markSeenOnboarding()" 
    class="fixed top-4 right-4 z-50 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition"
>
    Close Onboarding
</button> -->


    {{-- Categories Grid --}}
    <div class="categories-grid">
        @php
            $colors = [
                'linear-gradient(135deg, #3b82f6, #6366f1)', 
                'linear-gradient(135deg, #22c55e, #14b8a6)', 
                'linear-gradient(135deg, #ec4899, #f43f5e)', 
                'linear-gradient(135deg, #facc15, #f97316)'
            ];
        @endphp

        @foreach($categories as $index => $category)
            <a href="{{ route('client.category.products', ['category' => $category]) }}" 
               class="category-card" style="background: {{ $colors[$index % count($colors)] }}">
                <div class="category-icon">
                    <svg class="icon" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7h18M3 12h18M3 17h18"></path>
                    </svg>
                </div>
                <p class="category-name">{{ $category }}</p>
            </a>
        @endforeach
    </div>
</div>

{{-- Watermark Overlay --}}
<div class="watermark">
    Confidential - Client Portal - {{ Auth::user()->email ?? 'Guest' }} - {{ now() }}
</div>

{{-- Styles --}}
<style>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 50px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
    z-index: 1;
}

/* Grid & Cards */
.dashboard-header { text-align: center; margin-bottom: 50px; }
.dashboard-header h1 { font-size: 48px; font-weight: 800; color: #111827; margin-bottom: 10px; }
.dashboard-header p { font-size: 18px; color: #6b7280; }
.categories-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; }
.category-card { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; border-radius: 25px; color: white; text-decoration: none; position: relative; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
.category-card:hover { transform: translateY(-10px); box-shadow: 0 15px 25px rgba(0,0,0,0.2); }
.category-icon { width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; transition: background 0.3s ease; }
.category-card:hover .category-icon { background: rgba(255,255,255,0.35); }
.icon { width: 40px; height: 40px; }
.category-name { font-size: 22px; font-weight: 700; text-align: center; text-transform: capitalize; }

/* Overlay for dull effect */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4); /* dulls dashboard */
    z-index: 999; /* below onboarding card */
}

/* Onboarding Card */
.onboarding-card {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000; /* above overlay */
    pointer-events: auto;
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-header h1 { font-size: 36px; }
    .dashboard-header p { font-size: 16px; }
    .category-card { padding: 35px 15px; }
    .category-icon { width: 70px; height: 70px; }
    .icon { width: 35px; height: 35px; }
    .category-name { font-size: 20px; }
}
@media (max-width: 640px) {
    .categories-grid { grid-template-columns: 1fr; }
    .dashboard-header h1 { font-size: 28px; }
    .dashboard-header p { font-size: 14px; }
    .category-card { padding: 25px 15px; }
    .category-icon { width: 60px; height: 60px; }
    .icon { width: 28px; height: 28px; }
    .category-name { font-size: 18px; }
}

/* Screenshot Protection */
.protected-content { user-select: none; }

/* Watermark */
.watermark { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; font-size: 2rem; color: rgba(0,0,0,0.05); transform: rotate(-30deg); white-space: nowrap; }
.watermark::before { content: "Confidential - Client Portal - {{ Auth::user()->email ?? 'Guest' }} - {{ now() }}"; flex: 0 0 100%; text-align: center; }

/* Print */
@media print { body * { display: none !important; } .watermark, .protected-content { display: none !important; } }
</style>

<script>
function markSeenOnboarding() {
    fetch("{{ route('client.markSeen') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              console.log("Onboarding marked as seen");
          }
      }).catch(err => console.error(err));
}
</script>


{{-- Scripts --}}
<script>
document.addEventListener("contextmenu", e => e.preventDefault()); // Block right-click
window.onbeforeprint = function() { alert("Printing is disabled on this page."); };
</script>


@endsection