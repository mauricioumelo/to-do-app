<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function findAll(string $orderBy, string $order, array $filters);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findById(int $id);
}
