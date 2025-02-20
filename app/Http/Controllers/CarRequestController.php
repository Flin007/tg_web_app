<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest\CreateCarRequest;

use App\Models\CarRequest;
use Illuminate\Http\JsonResponse;


class CarRequestController
{
    /**
     * @param CreateCarRequest $request
     *
     * @return JsonResponse
     */
    public function create(CreateCarRequest $request)
    {
        return response()->json(CarRequest::create($request->validated()));
    }
}
