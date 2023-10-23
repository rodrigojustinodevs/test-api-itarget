<?php

namespace App\Http\Controllers\Api\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\{
    RegistrationStoreRequest,
    RegistrationUpdateRequest
};
use App\Http\Responses\ApiResponse;
use App\Services\Registration\RegistrationService;
use Illuminate\Http\{
    JsonResponse,
    Response
};

class RegistrationController extends Controller
{
    protected $service;

    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    public function index() : JsonResponse
    {
        try {
            $result = $this->service->showAllRegistration();
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

    public function store(RegistrationStoreRequest $request) : JsonResponse
    {
        try {
            $result = $this->service->created($request->validated());

            return ApiResponse::created($result);
        } catch (\Exception $e) {
            return ApiResponse::error($e, $e->getMessage(), $e->getCode());
        }
    }

    public function update(RegistrationUpdateRequest $updateUserRequest, $id) : JsonResponse
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
