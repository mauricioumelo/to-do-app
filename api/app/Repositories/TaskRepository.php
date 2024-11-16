<?php

namespace App\Repository;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(protected Task $model) {}
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data) {}
    public function delete(int $id) {}
    public function findById(int $id) {}
}
