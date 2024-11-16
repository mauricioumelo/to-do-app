<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class TaskService
{
    function __construct(protected TaskRepositoryInterface $repository) {}

    public function create(array $data)
    {
        $validator = Validator::make(data: $data, rules: [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => [Rule::enum(TaskStatus::class), 'nullable'],
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->repository->create($data);
    }
}
