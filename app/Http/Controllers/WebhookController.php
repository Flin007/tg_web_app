<?php
namespace App\Http\Controllers;
use App\Models\TelegramUser;
use App\Repositories\TelegramUsersRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Payments\PreCheckoutQuery;
use Telegram\Bot\Objects\Update;

class WebhookController extends Controller
{
    protected BotsManager $botsManager;
    protected TelegramUsersRepository $telegramUsersRepository;

    public function __construct(
        BotsManager $botsManager,
        TelegramUsersRepository $telegramUsersRepository
    ) {
        $this->botsManager = $botsManager;
        $this->telegramUsersRepository = $telegramUsersRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws TelegramSDKException
     */
    public function __invoke(Request $request): Response
    {
        $this->botsManager->bot()->commandsHandler(true);
        return response(null, 200);
    }
}
