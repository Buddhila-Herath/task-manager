@extends('layouts.app')

@section('content')
<div class="form-container" style="max-width: 600px; margin: 0 auto;">
    <div class="dashboard-header">
        <h2>Create New Task</h2>
        <p class="text-muted">Fill in the details for your new task</p>
    </div>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div class="form-group">
            <label>Task Title</label>
            <input type="text" name="title" placeholder="What needs to be done?" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Add some details..." rows="4"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" style="flex: 2;">Create Task</button>
            <a href="{{ route('tasks.index') }}" style="flex: 1; text-align: center; padding: 0.8rem; border: 1px solid var(--glass-border); border-radius: 12px; text-decoration: none; color: var(--text-muted); font-weight: 600;">Cancel</a>
        </div>
    </form>
</div>
@endsection
