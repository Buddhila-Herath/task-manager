<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_index_page_is_accessible(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }

    public function test_tasks_are_displayed_on_index_page(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee($task->title);
    }

    public function test_tasks_pagination(): void
    {
        Task::factory()->count(15)->create();

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 10;
        });
    }

    public function test_it_filters_tasks_by_status(): void
    {
        $pendingTask = Task::factory()->create(['status' => 'pending']);
        $completedTask = Task::factory()->create(['status' => 'completed']);

        $response = $this->get(route('tasks.index', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertSee($pendingTask->title);
        $response->assertDontSee($completedTask->title);
    }

    public function test_it_filters_tasks_by_priority(): void
    {
        $highPriorityTask = Task::factory()->create(['priority' => 'high']);
        $lowPriorityTask = Task::factory()->create(['priority' => 'low']);

        $response = $this->get(route('tasks.index', ['priority' => 'high']));

        $response->assertStatus(200);
        $response->assertSee($highPriorityTask->title);
        $response->assertDontSee($lowPriorityTask->title);
    }

    public function test_it_searches_tasks_by_title(): void
    {
        $task1 = Task::factory()->create(['title' => 'Fix Bug']);
        $task2 = Task::factory()->create(['title' => 'Write Documentation']);

        $response = $this->get(route('tasks.index', ['search' => 'Bug']));

        $response->assertStatus(200);
        $response->assertSee('Fix Bug');
        $response->assertDontSee('Write Documentation');
    }

    public function test_create_task_page_is_accessible(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.create');
    }

    public function test_it_stores_a_new_task(): void
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->post(route('tasks.store'), $taskData);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task created successfully');
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_store_validation_fails_for_missing_required_fields(): void
    {
        $response = $this->post(route('tasks.store'), []);

        $response->assertSessionHasErrors(['title', 'status', 'priority']);
    }

    public function test_store_validation_fails_for_invalid_status_and_priority(): void
    {
        $taskData = [
            'title' => 'Task with invalid data',
            'status' => 'invalid_status',
            'priority' => 'invalid_priority',
        ];

        $response = $this->post(route('tasks.store'), $taskData);

        $response->assertSessionHasErrors(['status', 'priority']);
    }

    public function test_edit_task_page_is_accessible(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.edit');
        $response->assertSee($task->title);
    }

    public function test_it_updates_a_task(): void
    {
        $task = Task::factory()->create();

        $updatedData = [
            'title' => 'Updated Task Title',
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => now()->addDays(10)->format('Y-m-d'),
        ];

        $response = $this->put(route('tasks.update', $task), $updatedData);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task updated successfully');
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Task Title']);
    }

    public function test_update_validation_fails_for_invalid_data(): void
    {
        $task = Task::factory()->create();

        $response = $this->put(route('tasks.update', $task), [
            'title' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'status', 'priority']);
    }

    public function test_it_deletes_a_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task deleted successfully');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
