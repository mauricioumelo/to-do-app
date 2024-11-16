<?php

namespace Tests\Unit\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\TaskService;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class TaskServiceUnitTest extends TestCase
{
    protected $repository;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->repository = Mockery::mock(TaskRepositoryInterface::class);
    }

    public function test_create_task(): void
    {
        $service = new TaskService($this->repository);

        $data = [
            'title' => 'Create Task',
            'description' => 'First task generate',
            'status' => TaskStatus::PENDING,
            'user_id' => $this->user->id,
        ];

        $task = Task::factory()->make($data);

        $this->repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($task);

        $result = $service->create($data);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($data['title'], $result->title);
        $this->assertEquals($data['description'], $result->description);
        $this->assertEquals($data['status'], $result->status);
        $this->assertEquals($data['user_id'], $result->user_id);
    }

    public function test_create_task_fails_validation(): void
    {
        $service = new TaskService($this->repository);

        $data = [
            'description' => 'Missing title',
            'status' => TaskStatus::PENDING,
            'user_id' => $this->user->id,
        ];

        $this->expectException(ValidationException::class);
        $service->create($data);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
