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

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkUserStatus(Request $request): JsonResponse
    {
        /** @var TelegramUser $telegramUser */
        $telegramUser = $this->telegramUsersRepository->findOneUserByUserId($request->get('user_id'));
        if (!$telegramUser) {
            return new JsonResponse('User not found', 404);
        }

        if ($telegramUser->is_blocked) {
            return new JsonResponse('User is blocked', 403);
        }

        return new JsonResponse(true);
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
