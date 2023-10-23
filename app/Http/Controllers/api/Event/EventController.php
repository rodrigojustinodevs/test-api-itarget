<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\{
    EventStoreRequest,
    EventUpdateRequest
};
use App\Http\Responses\ApiResponse;
use App\Services\Event\EventService;
use Illuminate\Http\{
    JsonResponse,
    Response
};

class EventController extends Controller
{
    protected $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function index() : JsonResponse
    {
        try {
            $result = $this->service->showAllEvent();
            $paginationData = $result->additional['pagination'];
            return ApiResponse::success($result, Response::HTTP_OK, 'Sucess', $paginationData);
        } catch (\Exception $e) {
            return ApiResponse::error($e, $e->getMessage(), $e->getCode());
        }
    }

    public function show($id) : JsonResponse
    {
        try {
            $result = $this->service->show($id);

            return ApiResponse::success($result, Response::HTTP_OK, 'Sucess');
        } catch (\Exception $e) {
            return ApiResponse::error($e, $e->getMessage(), $e->getCode());
        }
    }

    public function store(EventStoreRequest $request) : JsonResponse
    {
        try {
            $result = $this->service->created($request->validated());

            return ApiResponse::created($result);
        } catch (\Exception $e) {
            return ApiResponse::error($e, $e->getMessage(), $e->getCode());
        }
    }

    public function update(EventUpdateRequest $updateUserRequest, $id) : JsonResponse
    {
        try {
            $result = $this->service->update($updateUserRequest->validated(), $id);

            return ApiResponse::success($result, Response::HTTP_OK, 'Sucess');
        } catch (\Exception $e) {
            return ApiResponse::error($e, $e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id) : JsonResponse
    {
        try {
            $result = $this->service->destroy($id);

            return ApiResponse::success($result, Response::HTTP_NO_CONTENT, 'Sucess');
        } catch (\Exception $e) {
            return ApiResponse::error($e, $e->getMessage(), $e->getCode());
        }
    }
}
