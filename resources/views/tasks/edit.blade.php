@extends('layouts.app')

@section('content')
<div class="form-container" style="max-width: 600px; margin: 0 auto;">
    <div class="dashboard-header">
        <h2>Edit Task</h2>
        <p class="text-muted">Update the details for "{{ $task->title }}"</p>
    </div>

    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Task Title</label>
            <input type="text" name="title" value="{{ $task->title }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4">{{ $task->description }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="pending" {{ $task->status=='pending'?'selected':'' }}>Pending</option>
                    <option value="in_progress" {{ $task->status=='in_progress'?'selected':'' }}>In Progress</option>
                    <option value="completed" {{ $task->status=='completed'?'selected':'' }}>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="low" {{ $task->priority=='low'?'selected':'' }}>Low</option>
                    <option value="medium" {{ $task->priority=='medium'?'selected':'' }}>Medium</option>
                    <option value="high" {{ $task->priority=='high'?'selected':'' }}>High</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="flex: 2;">Update Task</button>
            <a href="{{ route('tasks.index') }}" style="flex: 1; text-align: center; padding: 0.8rem; border: 1px solid var(--glass-border); border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 600;">Cancel</a>
        </div>
    </form>
</div>
@endsection
