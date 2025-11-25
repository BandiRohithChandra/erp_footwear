@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
        color: #1f2937;
    }

    .kpi-card {
        background: linear-gradient(145deg, #ffffff, #f3f4f6);
        padding: 1.5rem 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: 0.25s ease;
    }
    .kpi-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.12);
    }
    .kpi-title { font-size: 1.05rem; font-weight: 600; color: #4b5563; }
    .kpi-value { font-size: 2.25rem; font-weight: 700; color: #111827; }

    .chart-card {
        background: #ffffff;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .chart-container {
        position: relative;
        width: 100%;
        height: 400px;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
        font-size: 0.95rem;
    }
    .dashboard-table th {
        background-color: #f3f4f6;
        padding: 1rem;
        font-weight: 600;
        color: #374151;
        border-radius: 12px;
    }
    .dashboard-table td {
        background: #fff;
        padding: 1rem;
        border-radius: 12px;
    }

    .status-badge {
        padding: 0.3rem 0.7rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-progress { background: #dbeafe; color: #1e40af; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .status-delayed { background: #fee2e2; color: #7f1d1d; }

    .flex-wrap-gap {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
</style>

<div class="max-w-7xl mx-auto p-6">

    <h1 class="text-3xl md:text-4xl font-bold text-center mb-10">
        🏭 Footwear ERP — Production Dashboard
    </h1>

    <!-- KPI Cards (Updated for Batch & Process System) -->
    <div class="flex-wrap-gap mb-10">
        @php
            $cards = [
    ['title'=>"Total Batches", 'value'=>$totalBatches],
    ['title'=>"Today's Batches", 'value'=>$todayBatches],
    ['title'=>"Processes In Progress", 'value'=>$inProgressProcesses],
    ['title'=>"Completed Processes", 'value'=>$completedProcesses],
    ['title'=>"Delayed Processes", 'value'=>$delayedProcesses],
];

        @endphp

        @foreach($cards as $card)
            <div class="kpi-card" style="flex:1 1 250px;">
                <div class="kpi-title">{{ $card['title'] }}</div>
                <div class="kpi-value">{{ $card['value'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Process Stats Chart -->
    <div class="chart-card">
        <h2 class="text-center text-xl font-semibold mb-6">
            📊 Production Process Overview
        </h2>
        <div class="chart-container">
            <canvas id="processChart"></canvas>
        </div>
    </div>

    <!-- Recent Batches Table -->
    <div class="chart-card overflow-x-auto">
        <h2 class="text-center text-xl font-semibold mb-6">
            📋 Recent Batches
        </h2>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Batch No</th>
                    <th>Article</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBatches as $batch)
                    <tr>
                        <td>{{ $batch->batch_no }}</td>
                        <td>{{ $batch->product->name ?? 'N/A' }}</td>
                        <td>{{ $batch->quantity }}</td>
                        <td>
                            <span class="status-badge 
                                {{ $batch->status=='pending' ? 'status-pending' : 
                                ($batch->status=='in_progress' ? 'status-progress' : 
                                ($batch->status=='completed' ? 'status-completed' : 'status-delayed')) }}">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </td>
                        <td>{{ $batch->start_date }}</td>
                        <td>{{ $batch->end_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('processChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($batchStatusSummary)) !!},
        datasets: [{
            label: 'Batch Count',
            data: {!! json_encode(array_values($batchStatusSummary)) !!},
            backgroundColor: '#2563eb',
            borderRadius: 8,
            barThickness: 36
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false } 
        },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true }
        }
    }
});
</script>

@endsection
