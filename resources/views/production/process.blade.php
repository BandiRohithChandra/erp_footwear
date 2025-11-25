@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f6fa;
    }

    .process-card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .process-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .process-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .process-title {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #fff;
    }

    .badge-completed { background-color: #28a745; }
    .badge-inprogress { background-color: #ffc107; }
    .badge-pending { background-color: #6c757d; }

    .progress-container {
        margin: 10px 0;
    }

    .progress-bar {
        height: 8px;
        border-radius: 5px;
        background-color: #e0e0e0;
        position: relative;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 5px;
        background-color: #007bff;
        width: 0%;
        transition: width 0.5s ease-in-out;
    }

    .form-inline {
        display: flex;
        gap: 5px;
        margin-top: 10px;
    }

    .form-inline input, .form-inline select, .form-inline button {
        flex: 1;
        padding: 5px 8px;
        font-size: 0.85rem;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .form-inline button {
        background-color: #28a745;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .form-inline button:hover {
        background-color: #218838;
    }

    .kpi-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .kpi-card {
        flex: 1;
        background: #ffffff;
        padding: 15px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .kpi-card h6 {
        color: #6c757d;
        margin-bottom: 5px;
    }

    .kpi-card h3 {
        margin: 0;
    }
</style>



<div class="container py-4">

    <div class="process-header">
        <h2>Process Management</h2>
        <a href="#" class="btn btn-primary">Add New Process</a>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-container">
        <div class="kpi-card">
            <h6>Total Processes</h6>
            <h3>{{ $processes->count() }}</h3>
        </div>
        <div class="kpi-card">
            <h6>Completed</h6>
            <h3>{{ $processes->where('status','Completed')->count() }}</h3>
        </div>
        <div class="kpi-card">
            <h6>In Progress</h6>
            <h3>{{ $processes->where('status','In Progress')->count() }}</h3>
        </div>
        <div class="kpi-card">
            <h6>Pending</h6>
            <h3>{{ $processes->where('status','Pending')->count() }}</h3>
        </div>
    </div>

    <div class="row g-3">
        @foreach($processes as $process)
        <div class="col-md-4">
            <div class="process-card">
                <div class="process-header">
                    <span class="process-title">{{ $process->name }}</span>
                    <span class="badge-status 
                        @if($process->status=='Completed') badge-completed
                        @elseif($process->status=='In Progress') badge-inprogress
                        @else badge-pending @endif">
                        {{ $process->status }}
                    </span>
                </div>

                <p><strong>Stage:</strong> {{ $process->stage }}</p>
                <p><strong>Operator:</strong> {{ $process->operator ?? '-' }}</p>

                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $process->progress_percent ?? 0 }}%"></div>
                    </div>
                    <small>{{ $process->progress_percent ?? 0 }}% Completed</small>
                </div>

                <form action="{{ route('production.process.update', $process->id) }}" method="POST" class="form-inline">
                    @csrf
                    <select name="status">
                        <option @if($process->status=='Pending') selected @endif>Pending</option>
                        <option @if($process->status=='In Progress') selected @endif>In Progress</option>
                        <option @if($process->status=='Completed') selected @endif>Completed</option>
                    </select>
                    <input type="text" name="operator" placeholder="Operator" value="{{ $process->operator }}">
                    <button type="submit">Update</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
