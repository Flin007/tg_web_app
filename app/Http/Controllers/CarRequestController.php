<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest\CreateCarRequest;

use App\Http\Requests\CarRequest\UpdateCarRequest;
use App\Models\CarRequest;
use Illuminate\Http\JsonResponse;


class CarRequestController
{
    /**
     * @param CreateCarRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateCarRequest $request): JsonResponse
    {
        return response()->json(CarRequest::create($request->validated()));
    }

    /**
     * @param UpdateCarRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateCarRequest $request): JsonResponse
    {
        try {
            /** @var CarRequest $carRequest */
            $carRequest = CarRequest::query()->where('id', $request->getRequestId())->firstOrFail();
            $carRequest->update(['data' => $request->getRequestData()]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }

        return response()->json('true');
    }
}
