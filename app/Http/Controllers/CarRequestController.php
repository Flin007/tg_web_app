<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest\CreateCarRequest;

use App\Http\Requests\CarRequest\UpdateCarRequest;
use App\Models\CarRequest;
use Illuminate\Http\JsonResponse;
use Telegram\Bot\Laravel\Facades\Telegram;


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

            if ($request->getFinished()) {
                $selectedCarId = json_decode($request->getRequestData())->selectedCarId;
                $text = "<b>Номер обращения #".$carRequest->id."</b>";
                $text .= "\nСпасибо, мы свяжемся с вами в ближайшее время.";
                $text .= "\n<a href='https://t.me/t3zusauto_bot?startapp=car_".$selectedCarId."'>Выбранный автомобиль</a>";
                Telegram::bot()->sendMessage([
                    'chat_id' => $carRequest->user_id,
                    'text' => $text,
                    'parse_mode' => 'HTML'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }

        return response()->json('true');
    }
}
