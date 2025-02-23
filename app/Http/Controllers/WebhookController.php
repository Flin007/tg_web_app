<?php
namespace App\Http\Controllers;
use App\Helpers\TelegramBotNotificationHelper;
use App\Repositories\TelegramUsersRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Exceptions\TelegramSDKException;

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
        $webhook = $this->botsManager->bot()->commandsHandler(true);

        //Логируем все вебхуки, не связанные с командой
        TelegramBotNotificationHelper::sendLog('Логируем всё', $webhook);

        return response(null, 200);
    }
}
