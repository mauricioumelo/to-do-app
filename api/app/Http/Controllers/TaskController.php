<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $response = $this->service->findAll(
            orderBy: $request->get('order_by'),
            order: $request->get('order'),
            filters: $request->get('filters')
        );

        return TaskResource::collection(
            collect($response)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->service->create(
            [...$request->all(), 'user_id' => $request->user()->id]
        );

        return (new TaskResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(string $id): JsonResponse
    {
        $response = $this->service->findById(
            id: $id,
        );

        return (new TaskResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $response = $this->service->update(
            id: $id,
            data: [...$request->all(), 'user_id' => $request->user()->id]
        );

        return (new TaskResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(string $id): Response
    {
        $response = $this->service->delete(
            id: $id
        );

        return response()->noContent();
    }
}
