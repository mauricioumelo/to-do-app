<?php

namespace Tests\Feature;

use App\Http\Controllers\TaskController;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    protected $controller;

    protected $service;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $this->repository = new TaskRepository(new Task);
        $this->service = new TaskService($this->repository);
        $this->controller = new TaskController($this->service);
    }

    public function test_index(): void
    {
        $response = $this->controller->index(new Request);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertNotEmpty($response->collection);
    }

    public function test_store(): void
    {
        $request = new Request([
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'pending',
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->controller->store($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function test_show(): void
    {
        $task = Task::factory()->create();

        $response = $this->controller->show($task->id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function test_update(): void
    {
        $task = Task::factory()->create();

        $request = new Request(['title' => 'Updated Title']);
        $response = $this->controller->update($request, $task->id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function test_destroy(): void
    {
        $task = Task::factory()->create();

        $response = $this->controller->destroy($task->id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
