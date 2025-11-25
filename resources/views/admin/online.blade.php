@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <a href="{{ route('admin.dashboard.edit') }}" class="edit-btn">Edit Dashboard</a>
    </div>

    <!-- Back Button -->
<!-- <button type="button" onclick="history.back()" 
        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Back
</button> -->


    <!-- Cards Grid -->
    <div class="cards-grid">
       @foreach($cards as $card)
    @php
        $colors = [
            'orders_new' => '#3B82F6',
            'orders_pending' => '#FBBF24',
            'orders_placed' => '#A78BFA',
            'pending_payments' => '#F87171',
            'articles' => '#34D399',
            'total_sales' => '#10B981',
            'default' => '#9CA3AF',
        ];
        $color = $colors[$card->count_type] ?? $colors['default'];

        switch ($card->count_type) {
    case 'orders_pending': $cardLink = route('admin.orders.pending'); break;
    case 'orders_placed': $cardLink = route('admin.orders.placed'); break;
    case 'total_sales': $cardLink = route('admin.sales.total'); break;
    case 'pending_payments': $cardLink = route('admin.orders.pending_payments'); break;
    case 'clients': $cardLink = route('admin.clients.index'); break;   // ✅ add this
    default: $cardLink = $card->link ?? '#'; break;
}

    @endphp

    <a href="{{ $cardLink }}" class="card" style="--accent-color: {{ $color }};" data-type="{{ $card->count_type }}">
        <div class="card-header">
            <div class="icon-wrapper">
                @switch($card->count_type)
                    @case('orders_new') <i class="fas fa-shopping-cart"></i> @break
                    @case('orders_pending') <i class="fas fa-clock"></i> @break
                    @case('orders_placed') <i class="fas fa-box"></i> @break
                    @case('pending_payments') <i class="fas fa-credit-card"></i> @break
                    @case('articles') <i class="fas fa-file-alt"></i> @break
                    @case('total_sales') ₹ @break
                    @default <i class="fas fa-database"></i>
                @endswitch
            </div>
            <span class="card-title">{{ $card->title }}</span>
        </div>

        <div class="card-count">
            {{ $card->display ?? $card->count }}
        </div>
    </a>
    @endforeach
    </div>
</div>

<style>
/* ===== Dashboard CSS ===== */
.container {
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
  padding: 20px;
}

.dashboard-header { 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  margin-bottom: 30px; 
  flex-wrap: wrap; 
  gap: 10px;
}
.dashboard-header h1 { 
  font-size: 2rem; 
  font-weight: 700; 
  color: #111827; 
}
.edit-btn { 
  background-color: #2563EB; 
  color: #fff; 
  padding: 10px 20px; 
  border-radius: 12px; 
  text-decoration: none; 
  font-weight: 600; 
  transition: background 0.3s ease, transform 0.2s ease; 
  box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
  backdrop-filter: blur(8px); 
  white-space: nowrap;
}
.edit-btn:hover { background-color: #1E40AF; transform: translateY(-2px); }

.cards-grid { 
  display: grid; 
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); 
  gap: 25px; 
}
.card { 
  border-radius: 20px; 
  padding: 25px 20px; 
  display: flex; 
  flex-direction: column; 
  justify-content: space-between; 
  background: rgba(255, 255, 255, 0.15); 
  backdrop-filter: blur(16px) saturate(150%); 
  border: 1px solid rgba(255, 255, 255, 0.3); 
  box-shadow: 0 8px 24px rgba(0,0,0,0.08); 
  transition: transform 0.2s ease, box-shadow 0.2s ease; 
  text-decoration: none; 
  min-height: 180px; 
  color: #111827; 
  position: relative; 
  overflow: hidden; 
}
.card::before { 
  content: ""; 
  position: absolute; 
  inset: 0; 
  background: linear-gradient(135deg, var(--accent-color) 20%, transparent 80%); 
  opacity: 0.2; 
  z-index: 0; 
}
.card:hover { 
  transform: translateY(-5px) scale(1.02); 
  box-shadow: 0 12px 32px rgba(0,0,0,0.12); 
}
.card-header { 
  display: flex; 
  align-items: center; 
  margin-bottom: 15px; 
  position: relative; 
  z-index: 1; 
}
.icon-wrapper { font-size: 1.75rem; margin-right: 12px; color: var(--accent-color); }
.card-title { font-weight: 600; font-size: 1.1rem; color: #111827; }
.card-count { font-size: 2.2rem; font-weight: 700; color: var(--accent-color); position: relative; z-index: 1; }

/* ✅ Tablet */
@media (max-width: 768px) {
  .dashboard-header { flex-direction: column; align-items: center; }
  .dashboard-header h1 { font-size: 1.5rem; text-align: center; }
  .edit-btn { width: auto; text-align: center; padding: 8px 16px; font-size: 0.9rem; }
  .card-count { font-size: 1.8rem; }
}

/* ✅ Mobile */
@media (max-width: 480px) {
  .cards-grid { grid-template-columns: 1fr; }
  .card { padding: 20px 15px; min-height: 150px; text-align: center; }
  .card-header { flex-direction: column; justify-content: center; text-align: center; }
  .icon-wrapper { margin-right: 0; margin-bottom: 8px; }
  .card-title { font-size: 0.95rem; }
  .card-count { font-size: 1.5rem; }
  .edit-btn { font-size: 0.85rem; padding: 6px 14px; }
}
</style>

<script>
function refreshDashboardCounts() {
    fetch("{{ route('admin.dashboard.card-counts') }}")
        .then(res => res.json())
        .then(cards => {
            cards.forEach(card => {
                const cardEl = document.querySelector(`.card[data-type="${card.count_type}"] .card-count`);
                if (cardEl) {
                    if (['total_sales', 'pending_payments'].includes(card.count_type)) {
                        cardEl.textContent = '₹' + Number(card.count).toLocaleString('en-IN', { 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 2 
                        });
                    } else {
                        cardEl.textContent = card.count;
                    }
                }
            });
        })
        .catch(err => console.error(err));
}

setInterval(refreshDashboardCounts, 10000);
</script>
@endsection
