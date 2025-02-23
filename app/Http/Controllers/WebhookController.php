<?php
namespace App\Http\Controllers;
use App\Helpers\TelegramBotNotificationHelper;
use App\Models\TelegramUser;
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

        //Если это просто обновление статуса и нет сообщений, дальше не обрабатываем
        $isJustUpdate = $this->checkMemberStatus($webhook);
        if ($isJustUpdate){
            return response(null, 200);
        }

        return response(null, 200);
    }

    /**
     * Проверяем не изменился ли статус пользователя (member, left, kicked)
     * @see https://core.telegram.org/bots/api#chatmembermember
     *
     * @param $webhook
     *
     * @return boolean
     *
     * @throws TelegramSDKException
     */
    private function checkMemberStatus($webhook): bool
    {
        if ($webhook->my_chat_member) {
            $userId = $webhook->my_chat_member->chat->id;
            //Это какая то фигня бота, просто скипаем
            if ($userId < 0) {
                return true;
            }

            /** @var TelegramUser $telegramUser */
            $telegramUser = $this->telegramUsersRepository->findBy(['user_id' => $userId])->first();
            if ($telegramUser){
                $telegramUser->update([
                    'status' =>  $webhook->my_chat_member->new_chat_member->status,
                ]);
                $telegramUser->save();
            }else{
                TelegramBotNotificationHelper::sendLog('Изменился статус юзера, но его нет в базе', $webhook);
            }
            return !(count($webhook->getMessage()) > 0);
        }
        return false;
    }
}
