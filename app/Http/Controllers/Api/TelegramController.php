<?php

namespace App\Http\Controllers\Api;

use App\Helpers\TelegramUserDataValidator;
use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use App\Repositories\TelegramUsersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    private TelegramUsersRepository $telegramUsersRepository;

    public function __construct(TelegramUsersRepository $telegramUsersRepository)
    {
        $this->telegramUsersRepository = $telegramUsersRepository;
    }

    public function checkUserData(Request $request): JsonResponse
    {
        $initialData = json_decode($request->getContent());
        if (!isset($initialData->user)){
            return new JsonResponse('Bad initial data', 400);
        }

        /** @var TelegramUser|null $telegramUser */
        $telegramUser = $this->telegramUsersRepository->findOneUserByUserId($initialData->user->id);
        if (!$telegramUser){
            $telegramUser = $this->telegramUsersRepository->createUserFromCheckUserData((array)$initialData->user);
        }

        $result = [
            'id' => $telegramUser->user_id,
            'username' => $telegramUser->username,
            'first_name' => $telegramUser->first_name,
            'last_name' => $telegramUser->last_name,
            'is_premium' => $telegramUser->is_premium,
            'is_blocked' => $telegramUser->is_blocked,
            'status' => $telegramUser->status,
            'language_code' => $initialData->user->language_code,
            'photo_url' => $initialData->user->photo_url,

        ];

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $result = TelegramUserDataValidator::isSafe(env('TELEGRAM_BOT_TOKEN'), $request->getContent());

        if ($result) {
            return new JsonResponse(true);
        } else {
            return new JsonResponse('Bad Initial Data', 401);
        }
    }
}
