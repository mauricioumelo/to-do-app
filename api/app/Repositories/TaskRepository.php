<?php

namespace App\Repositories;

use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(protected Task $model) {}

    public function findAll(?string $orderBy, ?string $order, ?array $filters = [])
    {
        return $this->model->when(! empty($filters), function ($query) use ($filters) {
            $query->where($filters['column'], $filters['value']);
        })->orderBy($orderBy ?? 'created_at', $order ?? 'desc')->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->model->where('id', $id)->first();

        if (!$task) {
            throw new TaskNotFoundException();
        }
        $task->update($data);

        return $task;
    }

    public function delete(int $id)
    {
        $task = $this->model->where('id', $id)->first();

        if (!$task) {
            throw new TaskNotFoundException();
        }
        $task->delete();

        return true;
    }

    public function findById(int $id)
    {
        $task = $this->model->where('id', $id)->first();

        if (!$task) {
            throw new TaskNotFoundException();
        }
        return $task;
    }
}
