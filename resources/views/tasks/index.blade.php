@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <h2>Task Dashboard</h2>
    <p class="text-muted">Manage your daily goals and productivity</p>
</div>

<form method="GET" class="filters">
    <div class="filter-group">
        <label>Status</label>
        <select name="status">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Priority</label>
        <select name="priority">
            <option value="">All Priority</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Search</label>
        <input type="text" name="search" placeholder="Search title..." value="{{ request('search') }}">
    </div>

    <button type="submit">Filter Tasks</button>
</form>

<div class="task-grid">
    @forelse($tasks as $task)
        <div class="task-card">
            <div class="task-header">
                <span class="task-title">{{ $task->title }}</span>
                <span class="badge badge-{{ $task->status }}">{{ str_replace('_', ' ', $task->status) }}</span>
            </div>
            
            <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem;">
                {{ Str::limit($task->description, 100) }}
            </p>

            <div class="task-meta">
                <span class="badge badge-{{ $task->priority }}">{{ $task->priority }} Priority</span>
                <div class="task-actions">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn-icon btn-edit" title="Edit Task">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="Delete Task">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="no-tasks">
            <p>No tasks found. Try adjusting your filters or <a href="{{ route('tasks.create') }}">create one</a>.</p>
        </div>
    @endforelse
</div>

<div class="pagination">
    {{ $tasks->appends(request()->query())->links() }}
</div>
@endsection
