<div class="process-node">
    <div class="process-header">
        <div>
            <h6 class="process-title">{{ $process->name }}</h6>
            <small class="process-meta">
                {{ $process->progress_percent }}% - 
                <span class="badge 
                    @if($process->status == 'Pending') badge-pending
                    @elseif($process->status == 'In Progress') badge-progress
                    @elseif($process->status == 'Completed') badge-completed
                    @endif">
                    {{ $process->status }}
                </span>
            </small>
        </div>
        <div>
            <!-- Delete -->
            <form action="{{ route('processes.destroy', $process->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    🗑 Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('processes.update', $process->id) }}" method="POST" class="form-inline">
        @csrf @method('PUT')

        <input type="text" name="name" value="{{ $process->name }}">
        <input type="number" name="progress_percent" value="{{ $process->progress_percent }}" min="0" max="100">
        <select name="status">
            <option {{ $process->status == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option {{ $process->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option {{ $process->status == 'Completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button type="submit" class="btn btn-warning">✏ Update</button>
    </form>

    <!-- Add Sub-Process -->
    <form action="{{ route('processes.store') }}" method="POST" class="form-inline">
        @csrf
        <input type="hidden" name="parent_id" value="{{ $process->id }}">
        <input type="text" name="name" placeholder="Sub-process name">
        <button type="submit" class="btn btn-success">➕ Add</button>
    </form>

    <!-- Recursive Children -->
    @if($process->childrenRecursive->count())
        <div class="process-children">
            @foreach($process->childrenRecursive as $child)
                @include('processes.partials.process-node', ['process' => $child])
            @endforeach
        </div>
    @endif
</div>



<style>
/* Card styling */
.process-node {
    border-radius: 8px;
    background: #fff;
    padding: 12px 16px;
    margin-bottom: 15px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

/* Card header (title + actions) */
.process-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Title & status */
.process-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.process-meta {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Status badges */
.badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}
.badge-pending {
    background: #6c757d;
    color: #fff;
}
.badge-progress {
    background: #ffc107;
    color: #212529;
}
.badge-completed {
    background: #28a745;
    color: #fff;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    border-radius: 6px;
    padding: 4px 8px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}
.btn-danger {
    border: 1px solid #dc3545;
    color: #dc3545;
    background: transparent;
}
.btn-danger:hover {
    background: #dc3545;
    color: #fff;
}
.btn-warning {
    border: 1px solid #ffc107;
    color: #856404;
    background: transparent;
}
.btn-warning:hover {
    background: #ffc107;
    color: #212529;
}
.btn-success {
    border: 1px solid #28a745;
    color: #28a745;
    background: transparent;
}
.btn-success:hover {
    background: #28a745;
    color: #fff;
}

/* Forms */
.form-inline {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.form-inline input,
.form-inline select {
    padding: 6px 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.85rem;
}

/* Children */
.process-children {
    border-left: 2px solid #ddd;
    margin-left: 15px;
    padding-left: 15px;
    margin-top: 10px;
}

</style>